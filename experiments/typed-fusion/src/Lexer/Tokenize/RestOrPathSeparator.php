<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Exception\UnexpectedFragmentException;
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
        } else throw UnexpectedFragmentException::
                whileStartingRestOrPathSeparatorDisambiguation($capture);

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
                        throw UnexpectedFragmentException::
                            whileCapturingRestOperator($capture);
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
                        throw UnexpectedFragmentException::
                            whileCapturingPathSeparator($capture);
                    }
                break;
            }
        }
    }
}
