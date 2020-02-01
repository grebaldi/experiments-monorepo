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
        $capture = null;

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            if ($value === "\n") {
                if ($capture !== null) {
                    yield Token::createFromFragment(
                        TokenType::WHITESPACE(),
                        $capture
                    );            

                    $capture = null;
                }

                yield Token::createFromFragment(
                    TokenType::END_OF_LINE(),
                    $fragment
                );

                $iterator->next();
            } elseif (ctype_space($value)) {
                if ($capture === null) {
                    $capture = $fragment;
                } else {
                    $capture = $capture->append($fragment);
                }

                $iterator->next();
            } else break;
        }

        if ($capture !== null) {
            yield Token::createFromFragment(
                TokenType::WHITESPACE(),
                $capture
            );
        }
    }
}