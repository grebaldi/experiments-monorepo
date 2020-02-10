<?php
namespace PackageFactory\AtomicFusion\VirtualDom;

final class AttributeList implements \IteratorAggregate, \Countable
{
    private $attributes = [];

    private function __construct(Attribute ...$attributes)
    {
        $this->attributes = $attributes;
    }

    public static function create(Attribute ...$attributes): AttributeList
    {
        return new AttributeList(...$attributes);
    }

    public function getIterator(): iterable
    {
        foreach ($this->attributes as $attribute) {
            yield $attribute->getName() => $attribute;
        }
    }

    public function count(): int
    {
        return count($this->attributes);
    }
}
