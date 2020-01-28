<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Source\Fragment;
use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait Comment
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
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
}