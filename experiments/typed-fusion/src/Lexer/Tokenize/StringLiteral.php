<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Source\Fragment;
use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait StringLiteral
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
    private function tokenizeStringLiteral(iterable $iterator): iterable
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
}