<?php
namespace PackageFactory\AtomicFusion\VirtualDom;

final class TextNode extends AbstractNode
{
    private $content;

    private function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function create(string $rawContent): TextNode
    {
        return new TextNode(
            htmlspecialchars($rawContent)
        );
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
