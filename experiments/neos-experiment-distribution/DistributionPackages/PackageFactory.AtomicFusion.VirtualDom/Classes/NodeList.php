<?php
namespace PackageFactory\AtomicFusion\VirtualDom;

final class NodeList implements \IteratorAggregate, \Countable
{
    /**
     * @var array<AbstractNode>
     */
    private $nodes;

    private function __construct(AbstractNode ...$nodes)
    {
        $this->nodes = $nodes;
    }

    public static function create(AbstractNode ...$nodes): NodeList
    {
        return new NodeList(...$nodes);
    }

    public function getIterator(): iterable
    {
        foreach ($this->nodes as $node) {
            yield $node;
        }
    }

    public function count(): int
    {
        return count($this->nodes);
    }
}
