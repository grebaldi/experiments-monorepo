<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait Whitespace
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
    private function tokenizeWhitespace(iterable $iterator): iterable
    {
        $capture = $iterator->current();
        $iterator->next();

        while ($fragment = $iterator->current()) {
            if (ctype_space($fragment->getValue())) {
                $capture = $capture->append($fragment);
                $iterator->next();
            } else break;
        }

        yield Token::createFromFragment(
            TokenType::WHITESPACE(),
            $capture
        );
    }
}