<?php
namespace PackageFactory\TypedFusion;

final class TokenizerContext
{
    private $identifier;
    private static $instances = [];

    private function __construct(
        string $identifier,
        array $escapeCharacters,
        array $stopCharacters,
        callable $onNext
    ) {
        $this->identifier = $identifier;
        $this->escapeCharacters = $escapeCharacters;
        $this->stopCharacters = $stopCharacters;
        $this->onNext = $onNext;
    }

    private static function create(string $identifier)
    {
        if (isset(self::$instances[$identifier])) {
            return self::$instances[$identifier];
        }

        return self::$instances[$identifier] = new TokenizerContext($identifier);
    }

    public static function DECLARATION(): TokenizerContext 
    { 
        $identifier = 'DECLARATION';
        if (isset(self::$instances[$identifier])) {
            return self::$instances[$identifier];
        }

        return self::$instances[$identifier] = new class extends TokenizerContext {

        };
    }

    public static function EXPRESSION(): TokenizerContext 
    { 
        return self::create('EXPRESSION'); 
    }

    public static function DSL(): TokenizerContext 
    { 
        return self::create('DSL'); 
    }
}