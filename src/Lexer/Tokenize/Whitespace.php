<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait Whitespace
{
    /**
     * @param iterable $iterator
     * @return Token
     */
    private function tokenizeWhitespace(iterable $iterator): Token
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
}