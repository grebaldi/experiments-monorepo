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

    public function __toString()
    {
        return $this->identifier;
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

    public static function STRING_START(): TokenType { return self::create('STRING_START'); }
    public static function STRING_VALUE(): TokenType { return self::create('STRING_VALUE'); }
    public static function STRING_ESCAPE(): TokenType { return self::create('STRING_ESCAPE'); }
    public static function STRING_ESCAPED_CHARACTER(): TokenType { return self::create('STRING_ESCAPED_CHARACTER'); }
    public static function STRING_END(): TokenType { return self::create('STRING_END'); }

    public static function NUMBER_INTEGER(): TokenType { return self::create('NUMBER_INTEGER'); }
    public static function NUMBER_FLOAT(): TokenType { return self::create('NUMBER_FLOAT'); }

    public static function WHITESPACE(): TokenType { return self::create('WHITESPACE'); }
    public static function COMMENT(): TokenType { return self::create('COMMENT'); }

    public static function OPERATOR_RESET(): TokenType { return self::create('OPERATOR_RESET'); }
    public static function OPERATOR_EXTENDS(): TokenType { return self::create('OPERATOR_EXTENDS'); }
    public static function OPERATOR_ASSIGN_TYPE(): TokenType { return self::create('OPERATOR_ASSIGN_TYPE'); }
    public static function OPERATOR_ASSIGN_VALUE(): TokenType { return self::create('OPERATOR_ASSIGN_VALUE'); }
    public static function OPERATOR_REST(): TokenType { return self::create('OPERATOR_REST'); }
    public static function OPERATOR_TYPE_UNION(): TokenType { return self::create('OPERATOR_TYPE_UNION'); }
    public static function OPERATOR_TYPE_INTERSECTION(): TokenType { return self::create('OPERATOR_TYPE_INTERSECTION'); }

    public static function SEPARATOR_PATH(): TokenType { return self::create('SEPARATOR_PATH'); }
    public static function SEPARATOR_INCLUDE(): TokenType { return self::create('SEPARATOR_INCLUDE'); }

    public static function EXPRESSION_START(): TokenType { return self::create('EXPRESSION_START'); }
    public static function EXPRESSION_ARROW(): TokenType { return self::create('EXPRESSION_ARROW'); }
    public static function EXPRESSION_OPERATOR_ADD(): TokenType { return self::create('EXPRESSION_OPERATOR_ADD'); }
    public static function EXPRESSION_OPERATOR_SUBTRACT(): TokenType { return self::create('EXPRESSION_OPERATOR_SUBTRACT'); }
    public static function EXPRESSION_OPERATOR_MULTIPLY(): TokenType { return self::create('EXPRESSION_OPERATOR_MULTIPLY'); }
    public static function EXPRESSION_OPERATOR_DIVIDE(): TokenType { return self::create('EXPRESSION_OPERATOR_DIVIDE'); }
    public static function EXPRESSION_END(): TokenType { return self::create('EXPRESSION_END'); }

    public static function COMMENT_START(): TokenType { return self::create('COMMENT_START'); }
    public static function COMMENT_CONTENT(): TokenType { return self::create('COMMENT_CONTENT'); }
    public static function COMMENT_END(): TokenType { return self::create('COMMENT_END'); }

    public static function INCLUDE_PATH(): TokenType { return self::create('INCLUDE_PATH'); }

    public static function END_OF_LINE(): TokenType { return self::create('END_OF_LINE'); }
    public static function END_OF_FILE(): TokenType { return self::create('END_OF_FILE'); }
}