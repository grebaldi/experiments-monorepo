<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait ValueAssignment
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
    private function tokenizeValueAssignment(iterable $iterator): iterable
    {
        if ($iterator->current()->getValue() === '=') {
            yield Token::createFromFragment(
                TokenType::OPERATOR_ASSIGN_VALUE(),
                $iterator->current()
            );
            $iterator->next();
        } else throw new \Exception('@TODO: Unexpected Fragment');

        while ($fragment = $iterator->current()) {
            $value = $fragment->getValue();

            switch (true) {
                case ctype_space($value):
                    foreach ($this->tokenizeWhitespace($iterator) as $token) {
                        yield $token;
                    }
                break;

                case ctype_alpha($value):
                    $token = $this->tokenizeTypeOrPrototypeName($iterator);

                    switch ($token->getValue()) {
                        case 'type': yield $token->setType(TokenType::KEYWORD_TYPE()); break;
                        case 'prototype': yield $token->setType(TokenType::KEYWORD_PROTOTYPE()); break;
                        case 'include': yield $token->setType(TokenType::KEYWORD_INCLUDE()); break;
                        case 'true': yield $token->setType(TokenType::KEYWORD_TRUE()); break;
                        case 'false': yield $token->setType(TokenType::KEYWORD_FALSE()); break;
                        case 'null': yield $token->setType(TokenType::KEYWORD_NULL()); break;
                        default: yield $token; break;
                    }
                    return;
                break;

                case ctype_digit($value):
                    yield $this->tokenizeNumber($iterator);
                    return;
                
                case $value === '$':
                    $iterator->next();
                    $next = $iterator->current();

                    if ($next->getValue() === '{') {
                        yield Token::createFromFragment(
                            TokenType::EXPRESSION_START(),
                            $fragment->append($next)
                        );

                        $iterator->next();

                        foreach ($this->tokenizeExpression($iterator) as $token) {
                            yield $token;
                        }

                        $next = $iterator->current();
                        if ($next->getValue() === '}') {
                            yield Token::createFromFragment(
                                TokenType::EXPRESSION_END(),
                                $next
                            );

                            $iterator->next();
                        } else throw new \Exception('@TODO: Unexpected Fragment');
                    } else throw new \Exception('@TODO: Unexpected Fragment');

                
                default: return;
            }
        }
    }
}