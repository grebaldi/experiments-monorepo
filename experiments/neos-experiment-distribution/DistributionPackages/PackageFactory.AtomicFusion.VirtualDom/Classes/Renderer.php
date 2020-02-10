<?php
namespace PackageFactory\AtomicFusion\VirtualDom;

final class Renderer
{
    const VOID_ELEMENTS = [
        'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link',
        'meta', 'param', 'source', 'track', 'wbr'
    ];

    public static function renderToStaticMarkup($node): string
    {
        switch (true) {
            case $node instanceof ElementNode:
                return self::renderElementNode($node);

            case $node instanceof TextNode:
                return self::renderTextNode($node);

            default:
                return (string) Util::assertNode($node);
        }
    }

    private static function renderNodeList(NodeList $nodes): string
    {
        $result = '';

        foreach ($nodes as $node) {
            switch (true) {
                case $node instanceof ElementNode:
                    $result .= self::renderElementNode($node);
                break;

                case $node instanceof TextNode:
                    $result .= self::renderTextNode($node);
                break;

                default:
                    return (string) Util::assertNode($node);
            }
        }

        return $result;
    }

    private static function renderElementNode(ElementNode $node): string
    {
        if (in_array($node->getName(), self::VOID_ELEMENTS) && count($node->getChildren()) === 0) {
            return vsprintf('<%s%s/>', [
                $node->getName(),
                ($attributes = self::renderAttributeList($node->getAttributes())) ?
                    sprintf(' %s', $attributes) : ''
            ]);
        }

        return vsprintf('<%s%s>%s</%1$s>', [
            $node->getName(),
            ($attributes = self::renderAttributeList($node->getAttributes())) ?
                sprintf(' %s', $attributes) : '',
            self::renderNodeList($node->getChildren())
        ]);
    }

    private static function renderTextNode(TextNode $node): string
    {
        return $node->getContent();
    }

    private static function renderAttributeList(AttributeList $attributes): string
    {
        $result = [];

        foreach ($attributes as $attribute) {
            $result[] = self::renderAttribute($attribute);
        }

        return implode(' ', $result);
    }

    private static function renderAttribute(Attribute $attribute): string
    {
        return sprintf('%s="%s"', $attribute->getName(), $attribute->getValue());
    }
}
