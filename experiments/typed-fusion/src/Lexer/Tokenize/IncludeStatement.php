<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Source\Fragment;
use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait IncludeStatement
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
    private function tokenizeIncludeStatement(iterable $iterator): iterable
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
                    foreach ($this->tokenizeWhitespace($iterator) as $token) {
                        yield $token;
                    }
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