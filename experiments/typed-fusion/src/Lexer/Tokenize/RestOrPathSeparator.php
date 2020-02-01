<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait RestOrPathSeparator
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
    private function tokenizeRestOrPathSeparator(iterable $iterator): iterable
    {
        $capture = $iterator->current();

        if ($capture->getValue() === '.') {
            $iterator->next();
        } else throw new \Exception('@TODO: Unexpected Fragment');

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case $value === '.':
                    $capture = $capture->append($fragment);
                    $iterator->next();

                    if ($capture->getLength() === 3) {
                        yield Token::createFromFragment(
                            TokenType::OPERATOR_REST(),
                            $capture
                        );
                        return;
                    } elseif ($capture->getLength() > 3) {
                        throw new \Exception('@TODO: Unexpected Fragment');
                    }
                break;

                default:
                    if ($capture->getLength() === 1) {
                        yield Token::createFromFragment(
                            TokenType::SEPARATOR_PATH(),
                            $capture
                        );
                        return;
                    } elseif ($capture->getLength() > 1) {
                        throw new \Exception('@TODO: Unexpected Fragment');
                    }
                break;
            }
        }
    }
}