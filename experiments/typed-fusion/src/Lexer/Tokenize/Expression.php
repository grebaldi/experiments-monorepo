<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait Expression
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
    private function tokenizeExpression(iterable $iterator): iterable
    {
        $bracketsOpen = 0;
        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch(true) {
                case ctype_space($value):
                    yield $this->tokenizeWhitespace($iterator);
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
                    foreach ($this->tokenizeStringLiteral($iterator) as $token) {
                        yield $token;
                    }
                break;
            }
        }
    }
}