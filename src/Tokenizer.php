<?php
namespace PackageFactory\TypedFusion;

final class Tokenizer implements \IteratorAggregate
{
    /**
     * @var Source
     */
    private $source;

    /**
     * @param Source $source
     */
    private function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * @param Source $source
     * @return Tokenizer
     */
    public static function create(Source $source): Tokenizer
    {
        return new Tokenizer($source);
    }

    /**
     * @return iterable<Token>
     */
    public function getIterator(): iterable
    {
        $iterator = new \IteratorIterator($this->source);
        $iterator->rewind();

        return $this->tokenizeRoot($iterator);
    }

    private function tokenizeRoot(iterable $iterator): iterable
    {
        while ($fragment = $iterator->current()) {
            switch (true) {
                case ctype_alpha($fragment->getValue()):
                    yield $this->tokenizeIdentifierOrKeyword($iterator);
                break;
                default:
                break;
            }
        }

        yield;
    }

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