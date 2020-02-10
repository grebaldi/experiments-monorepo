<?php
namespace PackageFactory\AtomicFusion\VirtualDom;

final class ElementNode extends AbstractNode
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var AttributeList
     */
    private $attributes;

    /**
     * @var NodeList
     */
    private $children;

    private function __construct(
        string $name,
        AttributeList $attributes,
        NodeList $children
    ) {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->children = $children;
    }

    public static function create(
        string $name,
        AttributeList $attributes,
        NodeList $children
    ) {
        return new ElementNode($name, $attributes, $children);
    }



    public function getName(): string
    {
        return $this->name;
    }

    public function getAttributes(): AttributeList
    {
        return $this->attributes;
    }

    public function getChildren(): NodeList
    {
        return $this->children;
    }
}
