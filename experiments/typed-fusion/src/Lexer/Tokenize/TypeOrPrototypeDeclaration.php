<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait TypeOrPrototypeDeclaration
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
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
                    yield Token::createFromFragment(
                        TokenType::END_OF_LINE(),
                        $fragment
                    );

                    $iterator->next();

                    if (!$terminateOnBracket) {
                        return;
                    }
                break;

                case ctype_space($value):
                    foreach ($this->tokenizeWhitespace($iterator) as $token) {
                        yield $token;
                    }
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

                case $value === '\'':
                case $value === '"':
                    foreach ($this->tokenizeStringLiteral($iterator) as $token) {
                        yield $token;
                    }
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
}