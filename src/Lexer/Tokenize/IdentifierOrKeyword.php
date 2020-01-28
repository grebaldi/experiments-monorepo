<?php
namespace PackageFactory\TypedFusion\Lexer\Tokenize;

use PackageFactory\TypedFusion\Lexer\Token;
use PackageFactory\TypedFusion\Lexer\TokenType;

trait IdentifierOrKeyword
{
    /**
     * @param iterable $iterator
     * @return Token
     */
    private function tokenizeIdentifierOrKeyword(iterable $iterator): Token
    {
        $capture = $iterator->current();
        $iterator->next();

        while ($fragment = $iterator->current()) {
            if (ctype_alpha($fragment->getValue())) {
                $capture = $capture->append($fragment);
                $iterator->next();
            } else break;
        }

        switch ($capture->getValue()) {
            case "prototype":
                return Token::createFromFragment(
                    TokenType::KEYWORD_PROTOTYPE(),
                    $capture
                );
            case "include":
                return Token::createFromFragment(
                    TokenType::KEYWORD_INCLUDE(),
                    $capture
                );
            case "type":
                return Token::createFromFragment(
                    TokenType::KEYWORD_TYPE(),
                    $capture
                );
            case "true":
                return Token::createFromFragment(
                    TokenType::KEYWORD_TRUE(),
                    $capture
                );
            case "false":
                return Token::createFromFragment(
                    TokenType::KEYWORD_FALSE(),
                    $capture
                );
            case "null":
                return Token::createFromFragment(
                    TokenType::KEYWORD_NULL(),
                    $capture
                );
            default:
                return Token::createFromFragment(
                    TokenType::IDENTIFIER(),
                    $capture
                );
        }
    }
}