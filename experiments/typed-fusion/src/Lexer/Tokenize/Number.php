<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait Number
{
    /**
     * @param iterable $iterator
     * @return Token
     */
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
}