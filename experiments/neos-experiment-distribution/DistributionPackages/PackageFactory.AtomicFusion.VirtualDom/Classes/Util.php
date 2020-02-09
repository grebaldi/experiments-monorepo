<?php
namespace PackageFactory\AtomicFusion\VirtualDom;

final class Util
{
    public static function assertNode($value): void
    {
        switch (true) {
            case $value instanceof ComponentNode:
            case $value instanceof ElementNode:
            case $value instanceof TextNode:
            case $value instanceof PortalEntryNode:
            case $value instanceof PortalExitNode:
                return;

            default:
                throw new \Exception('@TODO: Exception');
        }
    }
}
