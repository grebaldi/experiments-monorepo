<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Source\Fragment;
use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait Root
{
    /**
     * @param iterable $iterator
     * @return iterable
     */
    private function tokenizeRoot(iterable $iterator): iterable
    {
        $lastFragment = Fragment::createEmpty($this->source);
        while ($fragment = $iterator->current()) {
            $lastFragment = $fragment;
            $value = $fragment->getValue();

            switch (true) {
                case $value === '@':
                case ctype_alpha($value):
                    $token = $this->tokenizeIdentifierOrKeyword($iterator);
                    yield $token;

                    switch ($token->getType()) {
                        case TokenType::KEYWORD_TYPE():
                        case TokenType::KEYWORD_PROTOTYPE():
                            foreach ($this->tokenizeTypeOrPrototypeDeclaration($iterator) as $token) {
                                yield $token;
                            }
                        break;

                        case TokenType::KEYWORD_INCLUDE():
                            foreach ($this->tokenizeIncludeStatement($iterator) as $token) {
                                yield $token;
                            }
                        break;

                    }
                break;

                case ctype_space($value):
                    yield $this->tokenizeWhitespace($iterator);
                break;

                case ctype_digit($value):
                    yield $this->tokenizeNumber($iterator);
                break;

                case $value === '<':
                    yield Token::createFromFragment(
                        TokenType::OPERATOR_EXTENDS(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '>':
                    yield Token::createFromFragment(
                        TokenType::OPERATOR_RESET(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === ':':
                    foreach ($this->tokenizeTypeAssignment($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '.':
                    foreach ($this->tokenizeRestOrPathSeparator($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '=':
                    foreach ($this->tokenizeValueAssignment($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '{':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_CURLY_OPEN(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '}':
                    yield Token::createFromFragment(
                        TokenType::BRACKET_CURLY_CLOSE(),
                        $fragment
                    );
                    $iterator->next();
                break;

                case $value === '\'':
                case $value === '"':
                case $value === '`':
                    foreach ($this->tokenizeStringLiteral($iterator) as $token) {
                        yield $token;
                    }
                break;

                case $value === '/':
                case $value === '#':
                    foreach ($this->tokenizeComment($iterator) as $token) {
                        yield $token;
                    }
                break;

                default:
                    throw new \Exception('@TODO: Unexpected Fragment: ' . $fragment->getValue());
            }
        }

        yield Token::create(
            TokenType::END_OF_FILE(),
            '',
            $lastFragment->getStart(),
            $lastFragment->getEnd(),
            $this->source
        );
    }

}