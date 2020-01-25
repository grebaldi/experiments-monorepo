<?php
namespace PackageFactory\TypedFusion;

final class Tokenizer implements \IteratorAggregate
{
    /**
     * @var Source
     */
    private $source;

    /**
     * @param Source $source
     */
    private function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * @param Source $source
     * @return Tokenizer
     */
    public static function create(Source $source): Tokenizer
    {
        return new Tokenizer($source);
    }

    /**
     * @return iterable<Token>
     */
    public function getIterator(): iterable
    {
        $iterator = new \IteratorIterator($this->source);
        $iterator->rewind();

        return $this->tokenizeRoot($iterator);
    }

    private function tokenizeRoot(iterable $iterator): iterable
    {
        $lastFragment = Fragment::createEmpty($this->source);
        while ($fragment = $iterator->current()) {
            $lastFragment = $fragment;
            $value = $fragment->getValue();

            switch (true) {
                case $value === '@':
                case ctype_alpha($value):
                    $token = $this->tokenizeIdentifierOrKeyword($iterator);
                    yield $token;

                    switch ($token->getType()) {
                        case TokenType::KEYWORD_TYPE():
                        case TokenType::KEYWORD_PROTOTYPE():
                            foreach ($this->tokenizeTypeOrPrototypeDeclaration($iterator) as $token) {
                                yield $token;
                            }
                        break;

                        case TokenType::KEYWORD_INCLUDE():
                            foreach ($this->tokenizeInclude($iterator) as $token) {
                                yield $token;
                            }
                        break;

                    }
                break;

                case ctype_space($value):
                    yield $this->tokenizeWhiteSpace($iterator);
                break;

                case ctype_digit($value):
                    yield $this->tokenizeNumber($iterator);
                break;

                case $value === '<':
                    yield Token::createFromFragment(
                        TokenType::OPERATOR_EXTENDS(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '>':
                    yield Token::createFromFragment(
                        TokenType::OPERATOR_RESET(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === ':':
                    foreach ($this->tokenizeTypeAssignment($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '.':
                    foreach ($this->tokenizeRestOrPathSeparator($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '=':
                    foreach ($this->tokenizeValueAssignment($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '{':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_CURLY_OPEN(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '}':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_CURLY_CLOSE(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '\'':
                case $value === '"':
                case $value === '`':
                    foreach ($this->tokenizeString($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '/':
                case $value === '#':
                    foreach ($this->tokenizeComment($iterator) as $token) {
                        yield $token;
                    }
                break;

                default:
                    throw new \Exception('@TODO: Unexpected Fragment: ' . $fragment->getValue());
            }
        }

        yield Token::create(
            TokenType::END_OF_FILE(),
            '',
            $lastFragment->getStart(),
            $lastFragment->getEnd(),
            $this->source
        );
    }

    private function tokenizeIdentifierOrKeyword(iterable $iterator): Token
    {
        $capture = $iterator->current();
        $iterator->next();

        while ($fragment = $iterator->current()) {
            if (ctype_alpha($fragment->getValue())) {
                $capture = $capture->append($fragment);
                $iterator->next();
            } else break;
        }

        switch ($capture->getValue()) {
            case "prototype":
                return Token::createFromFragment(
                    TokenType::KEYWORD_PROTOTYPE(),
                    $capture
                );
            case "include":
                return Token::createFromFragment(
                    TokenType::KEYWORD_INCLUDE(),
                    $capture
                );
            case "type":
                return Token::createFromFragment(
                    TokenType::KEYWORD_TYPE(),
                    $capture
                );
            case "true":
                return Token::createFromFragment(
                    TokenType::KEYWORD_TRUE(),
                    $capture
                );
            case "false":
                return Token::createFromFragment(
                    TokenType::KEYWORD_FALSE(),
                    $capture
                );
            case "null":
                return Token::createFromFragment(
                    TokenType::KEYWORD_NULL(),
                    $capture
                );
            default:
                return Token::createFromFragment(
                    TokenType::IDENTIFIER(),
                    $capture
                );
        }
    }

    private function tokenizeWhiteSpace(iterable $iterator): Token
    {
        $capture = $iterator->current();
        $iterator->next();

        while ($fragment = $iterator->current()) {
            if (ctype_space($fragment->getValue())) {
                $capture = $capture->append($fragment);
                $iterator->next();
            } else break;
        }

        return Token::createFromFragment(
            TokenType::WHITESPACE(),
            $capture
        );
    }

    private function tokenizeTypeOrPrototypeDeclaration(iterable $iterator): iterable
    {
        $bracketsOpen = [
            'arrow' => 0,
            'round' => 0,
            'square' => 0
        ];
        $terminateOnBracket = false;

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case $value === "\n":
                    if ($terminateOnBracket) {
                        yield $this->tokenizeWhiteSpace($iterator);
                    } else {
                        return;
                    }
                break;

                case ctype_space($value):
                    yield $this->tokenizeWhiteSpace($iterator);
                break;

                case ctype_alpha($value):
                    yield $this->tokenizeTypeOrPrototypeName($iterator);
                break;

                case $value === '{':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_CURLY_OPEN(),
                        $fragment
                    );
                    $iterator->next();

                    foreach ($this->tokenizeTypeOrPrototypeDeclaration($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '}':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_CURLY_CLOSE(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '<':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_ARROW_OPEN(),
                        $fragment
                    );
                    $iterator->next();
                    $bracketsOpen['arrow']++;
                    $terminateOnBracket = true;
                break;

                case $value === '>':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_ARROW_CLOSE(),
                        $fragment
                    );
                    $iterator->next();
                    $bracketsOpen['arrow']--;
                break;

                case $value === '(':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_ROUND_OPEN(),
                        $fragment
                    );
                    $iterator->next();
                    $bracketsOpen['round']++;
                    $terminateOnBracket = true;
                break;

                case $value === ')':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_ROUND_CLOSE(),
                        $fragment
                    );
                    $iterator->next();
                    $bracketsOpen['round']--;
                break;

                case $value === '[':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_SQUARE_OPEN(),
                        $fragment
                    );
                    $iterator->next();
                    $bracketsOpen['square']++;
                    $terminateOnBracket = true;
                break;

                case $value === ']':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_SQUARE_CLOSE(),
                        $fragment
                    );
                    $iterator->next();
                    $bracketsOpen['square']--;
                break;

                case $value === '|':
                    yield Token::createFromFragment(
                        TokenType::OPERATOR_TYPE_UNION(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '&':
                    yield Token::createFromFragment(
                        TokenType::OPERATOR_TYPE_INTERSECTION(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '~':
                    yield Token::createFromFragment(
                        TokenType::OPERATOR_EXTENDS(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === ',':
                    yield Token::createFromFragment(
                        TokenType::COMMA(),
                        $fragment
                    );
                    $iterator->next();
                break;

                default: 
                    return;
            }

            if ($terminateOnBracket) {
                if (array_sum($bracketsOpen) === 0) {
                    return;
                }
            }
        }
    }

    private function tokenizeTypeOrPrototypeName(iterable $iterator): Token
    {
        $capture = $iterator->current();

        if (ctype_alpha($capture->getValue())) {
            $iterator->next();
        } else throw new \Exception('@TODO: Unexpected Fragment');

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case ctype_alnum($value):
                case $value === ':':
                case $value === '.':
                    $capture = $capture->append($fragment);
                    $iterator->next();
                break;

                default: 
                    return Token::createFromFragment(
                        TokenType::IDENTIFIER(),
                        $capture
                    );
            }
        }

        return Token::createFromFragment(
            TokenType::IDENTIFIER(),
            $capture
        );
    }

    protected function tokenizeTypeAssignment(iterable $iterator): iterable
    {
        if ($iterator->current()->getValue() === ':') {
            yield Token::createFromFragment(
                TokenType::OPERATOR_ASSIGN_TYPE(),
                $iterator->current()
            );
            $iterator->next();
        } else throw new \Exception('@TODO: Unexpected Fragment');

        foreach ($this->tokenizeTypeOrPrototypeDeclaration($iterator) as $token) {
            yield $token;
        }
    }

    protected function tokenizeValueAssignment(iterable $iterator): iterable
    {
        if ($iterator->current()->getValue() === '=') {
            yield Token::createFromFragment(
                TokenType::OPERATOR_ASSIGN_VALUE(),
                $iterator->current()
            );
            $iterator->next();
        } else throw new \Exception('@TODO: Unexpected Fragment');

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case ctype_space($value):
                    yield $this->tokenizeWhiteSpace($iterator);
                break;

                case ctype_alpha($value):
                    $token = $this->tokenizeTypeOrPrototypeName($iterator);

                    switch ($token->getValue()) {
                        case 'type': yield $token->setType(TokenType::KEYWORD_TYPE()); break;
                        case 'prototype': yield $token->setType(TokenType::KEYWORD_PROTOTYPE()); break;
                        case 'include': yield $token->setType(TokenType::KEYWORD_INCLUDE()); break;
                        case 'true': yield $token->setType(TokenType::KEYWORD_TRUE()); break;
                        case 'false': yield $token->setType(TokenType::KEYWORD_FALSE()); break;
                        case 'null': yield $token->setType(TokenType::KEYWORD_NULL()); break;
                        default: yield $token; break;
                    }
                    return;
                break;

                case ctype_digit($value):
                    yield $this->tokenizeNumber($iterator);
                    return;
                
                case $value === '$':
                    $iterator->next();
                    $next = $iterator->current();

                    if ($next->getValue() === '{') {
                        yield Token::createFromFragment(
                            TokenType::EXPRESSION_START(),
                            $fragment->append($next)
                        );

                        $iterator->next();

                        foreach ($this->tokenizeExpression($iterator) as $token) {
                            yield $token;
                        }

                        $next = $iterator->current();
                        if ($next->getValue() === '}') {
                            yield Token::createFromFragment(
                                TokenType::EXPRESSION_END(),
                                $next
                            );

                            $iterator->next();
                        } else throw new \Exception('@TODO: Unexpected Fragment');
                    } else throw new \Exception('@TODO: Unexpected Fragment');

                
                default: return;
            }
        }
    }

    protected function tokenizeRestOrPathSeparator(iterable $iterator): iterable
    {
        $capture = $iterator->current();

        if ($capture->getValue() === '.') {
            $iterator->next();
        } else throw new \Exception('@TODO: Unexpected Fragment');

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case $value === '.':
                    $capture = $capture->append($fragment);
                    $iterator->next();

                    if ($capture->getLength() === 3) {
                        yield Token::createFromFragment(
                            TokenType::OPERATOR_REST(),
                            $capture
                        );
                        return;
                    } elseif ($capture->getLength() > 3) {
                        throw new \Exception('@TODO: Unexpected Fragment');
                    }
                break;

                default:
                    if ($capture->getLength() === 1) {
                        yield Token::createFromFragment(
                            TokenType::SEPARATOR_PATH(),
                            $capture
                        );
                        return;
                    } elseif ($capture->getLength() > 1) {
                        throw new \Exception('@TODO: Unexpected Fragment');
                    }
                break;
            }
        }
    }

    private function tokenizeString(iterable $iterator): iterable
    {
        $delimiter = $iterator->current();

        /** @var Fragment|null $capture */
        $capture = null;

        yield Token::createFromFragment(
            TokenType::STRING_START(),
            $delimiter
        );

        $iterator->next();

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case $value === '\\':
                    if ($capture !== null) {
                        yield Token::createFromFragment(
                            TokenType::STRING_VALUE(),
                            $capture
                        );

                        $capture = null;
                    }

                    yield Token::createFromFragment(
                        TokenType::STRING_ESCAPE(),
                        $fragment
                    );

                    $iterator->next();

                    if ($fragment = $iterator->current()) {
                        yield Token::createFromFragment(
                            TokenType::STRING_ESCAPED_CHARACTER(),
                            $fragment
                        );

                        $iterator->next();
                    } else throw new \Exception('@TODO: Unexpected end of file.');
                break;

                case $value === $delimiter->getValue():
                    if ($capture !== null) {
                        yield Token::createFromFragment(
                            TokenType::STRING_VALUE(),
                            $capture
                        );

                        $capture = null;
                    }

                    yield Token::createFromFragment(
                        TokenType::STRING_END(),
                        $delimiter
                    );

                    $iterator->next();
                    return;
                break;

                default:
                    if ($capture === null) {
                        $capture = $fragment;
                    } else {
                        $capture = $capture->append($fragment);
                    }
                    $iterator->next();
                break;
            }
        }
    }

    private function tokenizeNumber(iterable $iterator): Token
    {
        $capture = $iterator->current();
        $iterator->next();

        $isFloat = false;

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case ctype_digit($value):
                    $capture = $capture->append($fragment);
                    $iterator->next();
                break;

                case $value === '.':
                    $isFloat = true;
                    $capture = $capture->append($fragment);
                    $iterator->next();
                break;

                default:
                    return Token::createFromFragment(
                        $isFloat ? TokenType::NUMBER_FLOAT() : TokenType::NUMBER_INTEGER(),
                        $capture
                    );
            }
        }
    }

    private function tokenizeExpression(iterable $iterator): iterable
    {
        $bracketsOpen = 0;
        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch(true) {
                case ctype_space($value):
                    yield $this->tokenizeWhiteSpace($iterator);
                break;

                case ctype_alpha($value):
                    yield $this->tokenizeIdentifierOrKeyword($iterator)
                        ->setType(TokenType::IDENTIFIER());
                break;

                case ctype_digit($value):
                    yield $this->tokenizeNumber($iterator);
                break;

                case $value === '{':
                    $bracketsOpen++;
                    yield Token::createFromFragment(
                        TokenType::BRACKET_CURLY_OPEN(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === '}':
                    $bracketsOpen--;

                    if ($bracketsOpen > 0) {
                        $iterator->next();
                    } else return;

                    yield Token::createFromFragment(
                        TokenType::BRACKET_CURLY_CLOSE(),
                        $fragment
                    );
                break;

                case $value === '(':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_ROUND_OPEN(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === ')':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_ROUND_CLOSE(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === '[':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_SQUARE_OPEN(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === ']':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_SQUARE_CLOSE(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === '.':
                    yield Token::createFromFragment(
                        TokenType::PERIOD(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === ':':
                    yield Token::createFromFragment(
                        TokenType::COLON(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === ',':
                    yield Token::createFromFragment(
                        TokenType::COMMA(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === '+':
                    yield Token::createFromFragment(
                        TokenType::EXPRESSION_OPERATOR_ADD(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === '-':
                    yield Token::createFromFragment(
                        TokenType::EXPRESSION_OPERATOR_SUBTRACT(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === '*':
                    yield Token::createFromFragment(
                        TokenType::EXPRESSION_OPERATOR_MULTIPLY(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === '/':
                    yield Token::createFromFragment(
                        TokenType::EXPRESSION_OPERATOR_DIVIDE(),
                        $fragment
                    );

                    $iterator->next();
                break;

                case $value === '=':
                    $iterator->next();
                    $next = $iterator->current();

                    if ($next->getValue() === '>') {
                        yield Token::createFromFragment(
                            TokenType::EXPRESSION_ARROW(),
                            $fragment->append($next)
                        );

                        $iterator->next();
                    } else throw new \Exception('@TODO: Unexpected Fragment');
                break;

                case $value === '\'':
                case $value === '"':
                    foreach ($this->tokenizeString($iterator) as $token) {
                        yield $token;
                    }
                break;
            }
        }
    }

    private function tokenizeComment(iterable $iterator): iterable
    {
        $delimiter = $iterator->current();
        $iterator->next();

        if ($delimiter->getValue() === '#') {
            $isMultiLine = false;

            yield Token::createFromFragment(
                TokenType::COMMENT_START(),
                $delimiter
            );
        } elseif ($delimiter->getValue() === '/') {
            if ($next = $iterator->current()) {
                if ($next->getValue() === '/') {
                    $isMultiLine = false;

                    yield Token::createFromFragment(
                        TokenType::COMMENT_START(),
                        $delimiter->append($next)
                    );
                } elseif($next->getValue() === '*') {
                    $isMultiLine = true;

                    yield Token::createFromFragment(
                        TokenType::COMMENT_START(),
                        $delimiter->append($next)
                    );
                } else throw new \Exception(
                    sprintf(
                        '@TODO: Unexpected Fragment "%s" in line %s, character %s',
                        $next->getValue(),
                        $next->getStart()->getRowIndex(),
                        $next->getStart()->getColumnIndex()
                    )
                );

                $iterator->next();
            } else throw new \Exception('@TODO: Unexpected end of file');
        }
        
        /** @var Fragment|null $capture */
        $capture = null;

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            if ($value === '*' && $isMultiLine) {
                $iterator->next();

                if ($next = $iterator->current()) {
                    if ($next->getValue() === '/') {
                        $isMultiLine = false;

                        if ($capture !== null) {
                            yield Token::createFromFragment(
                                TokenType::COMMENT_CONTENT(),
                                $capture
                            );
                        }

                        yield Token::createFromFragment(
                            TokenType::COMMENT_END(),
                            $fragment->append($next)
                        );

                        $iterator->next();

                        return;
                    }
                }
            } elseif ($value === "\n" && !$isMultiLine) {
                $iterator->next();

                if ($capture !== null) {
                    yield Token::createFromFragment(
                        TokenType::COMMENT_CONTENT(),
                        $capture
                    );
                    return;
                }

                yield Token::createFromFragment(
                    TokenType::END_OF_LINE(),
                    $fragment
                );
            } elseif ($capture === null) {
                $capture = $fragment;
                $iterator->next();
            } else {
                $capture = $capture->append($fragment);
                $iterator->next();
            }
        }
    }

    private function tokenizeInclude(iterable $iterator): iterable
    {
        if ($next = $iterator->current()) {
            if ($next->getValue() === ':') {
                yield Token::createFromFragment(
                    TokenType::SEPARATOR_INCLUDE(),
                    $next
                );

                $iterator->next();
            } else throw new \Exception('@TODO: Unexpected Fragment');
        } else throw new \Exception('@TODO: Unexpected End of File');

        /** @var Fragment|null $capture */
        $capture = null;

        while($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case $value === "\n": 
                    if ($capture !== null) {
                        yield Token::createFromFragment(
                            TokenType::INCLUDE_PATH(),
                            $capture
                        );
                    }
                    return;

                case ctype_space($value):
                    $capture = null;
                    yield $this->tokenizeWhiteSpace($iterator);
                break;


                default:
                    if ($capture === null) {
                        $capture = $fragment;
                    } else {
                        $capture = $capture->append($fragment);
                    }

                    $iterator->next();
                break;
            }
        }
    }
}