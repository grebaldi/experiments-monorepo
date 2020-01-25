<?php
namespace PackageFactory\TypedFusion;

final class TokenType
{
    private $identifier;
    private static $instances = [];
    private function __construct(string $identifier) 
    {
        $this->identifier = $identifier;
    }

    private static function create(string $identifier)
    {
        if (isset(self::$instances[$identifier])) {
            return self::$instances[$identifier];
        }

        return self::$instances[$identifier] = new TokenType($identifier);
    }

    public static function IDENTIFIER(): TokenType { return self::create('IDENTIFIER'); }
    public static function KEYWORD_TYPE(): TokenType { return self::create('KEYWORD_TYPE'); }
    public static function KEYWORD_INCLUDE(): TokenType { return self::create('KEYWORD_INCLUDE'); }
    public static function KEYWORD_PROTOTYPE(): TokenType { return self::create('KEYWORD_PROTOTYPE'); }
    public static function KEYWORD_TRUE(): TokenType { return self::create('KEYWORD_TRUE'); }
    public static function KEYWORD_FALSE(): TokenType { return self::create('KEYWORD_FALSE'); }
    public static function KEYWORD_NULL(): TokenType { return self::create('KEYWORD_NULL'); }
    public static function BRACKET_ROUND_OPEN(): TokenType { return self::create('BRACKET_ROUND_OPEN'); }
    public static function BRACKET_ROUND_CLOSE(): TokenType { return self::create('BRACKET_ROUND_CLOSE'); }
    public static function BRACKET_SQUARE_OPEN(): TokenType { return self::create('BRACKET_SQUARE_OPEN'); }
    public static function BRACKET_SQUARE_CLOSE(): TokenType { return self::create('BRACKET_SQUARE_CLOSE'); }
    public static function BRACKET_CURLY_OPEN(): TokenType { return self::create('BRACKET_CURLY_OPEN'); }
    public static function BRACKET_CURLY_CLOSE(): TokenType { return self::create('BRACKET_CURLY_CLOSE'); }
    public static function BRACKET_ARROW_OPEN(): TokenType { return self::create('BRACKET_ARROW_OPEN'); }
    public static function BRACKET_ARROW_CLOSE(): TokenType { return self::create('BRACKET_ARROW_CLOSE'); }
    public static function COMMA(): TokenType { return self::create('COMMA'); }
    public static function PERIOD(): TokenType { return self::create('PERIOD'); }
    public static function COLON(): TokenType { return self::create('COLON'); }
    public static function QUOTE_SINGLE(): TokenType { return self::create('QUOTE_SINGLE'); }
    public static function QUOTE_DOUBLE(): TokenType { return self::create('QUOTE_DOUBLE'); }
    public static function QUOTE_BACKTICK(): TokenType { return self::create('QUOTE_BACKTICK'); }
    public static function WHITESPACE(): TokenType { return self::create('WHITESPACE'); }
    public static function COMMENT_LINE_START(): TokenType { return self::create('COMMENT_LINE_START'); }
    public static function COMMENT_LINE_CONTENTS(): TokenType { return self::create('COMMENT_LINE_CONTENTS'); }
    public static function COMMENT_BLOCK_START(): TokenType { return self::create('COMMENT_BLOCK_START'); }
    public static function COMMENT_BLOCK_CONTENTS(): TokenType { return self::create('COMMENT_BLOCK_CONTENTS'); }
    public static function COMMENT_BLOCK_END(): TokenType { return self::create('COMMENT_BLOCK_END'); }
}