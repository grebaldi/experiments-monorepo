<?php
namespace PackageFactory\AtomicFusion\VirtualDom;

final class Attribute
{
    private $name;

    private $value;

    private function __construct(string $name, string $value = null)
    {
        $this->name = mb_strtolower($name);

        if ($value !== null) {
            $this->value = htmlspecialchars($value);
        }
    }

    public static function create(string $name, string $value = null): Attribute
    {
        if (empty($name)) {
            throw new \Exception('Attribute Name must not be empty.');
        }

        if (preg_match('/\x{0000}/', $name)) {
            throw new \Exception('Illegal NULL in attribute name.');
        }

        if (preg_match('/\x{0022}/', $name)) {
            throw new \Exception('Illegal QUOTATION MARK in attribute name.');
        }

        if (preg_match('/\x{0027}/', $name)) {
            throw new \Exception('Illegal APOSTROPHE in attribute name.');
        }

        if (preg_match('/\x{003E}/', $name)) {
            throw new \Exception('Illegal GREATER-THAN SIGN in attribute name.');
        }

        if (preg_match('/\x{002F}/', $name)) {
            throw new \Exception('Illegal SOLIDUS in attribute name.');
        }

        if (preg_match('/\x{003D}/', $name)) {
            throw new \Exception('Illegal EQUALS SIGN in attribute name.');
        }

        if (preg_match('/\s/', $name)) {
            throw new \Exception('Illegal WHITESPACE in attribute name.');
        }

        return new Attribute($name, $value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
