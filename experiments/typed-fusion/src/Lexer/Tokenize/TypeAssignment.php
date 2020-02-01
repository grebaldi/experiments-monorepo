<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait TypeAssignment
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
    private function tokenizeTypeAssignment(iterable $iterator): iterable
    {
        if ($iterator->current()->getValue() === ':') {
            yield Token::createFromFragment(
                TokenType::OPERATOR_ASSIGN_TYPE(),
                $iterator->current()
            );
            $iterator->next();
        } else throw new \Exception('@TODO: Unexpected Fragment');

        foreach ($this->tokenizeTypeOrPrototypeDeclaration($iterator) as $token) {
            yield $token;
        }
    }
}