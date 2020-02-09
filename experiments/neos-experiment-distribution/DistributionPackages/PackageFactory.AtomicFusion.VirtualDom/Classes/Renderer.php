<?php
namespace PackageFactory\AtomicFusion\VirtualDom;

final class Renderer
{
    public static function renderToStaticMarkup($node, $runtime): string
    {
        if ($node instanceof UncachedSegment) {
            $node = $node->resolve($runtime);
        }

        switch (true) {
            case $node instanceof ComponentNode:
                return self::renderComponentNode($node);

            case $node instanceof ElementNode:
                return self::renderElementNode($node);

            case $node instanceof PortalEntryNode:
                return self::renderPortalEntryNode($node);

            case $node instanceof PortalExitNode:
                return self::renderPortalExitNode($node);

            case $node instanceof TextNode:
                return self::renderTextNode($node);

            default:
                return (string) Util::assertNode($node);
        }
    }

    private static function renderComponentNode(ComponentNode $node): string
    {

    }

    private static function renderElementNode(ElementNode $node): string
    {

    }

    private static function renderPortalEntryNode(PortalEntryNode $node): string
    {

    }

    private static function renderPortalExitNode(PortalExitNode $node): string
    {

    }

    private static function renderTextNode(TextNode $node): string
    {

    }
}
