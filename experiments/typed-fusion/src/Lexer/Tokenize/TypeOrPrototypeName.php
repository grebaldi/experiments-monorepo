<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait TypeOrPrototypeName
{
    /**
     * @param iterable $iterator
     * @return Token
     */
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
}