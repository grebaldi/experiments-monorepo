<?php
include_once './AbstractNode.php';
include_once './Attribute.php';
include_once './AttributeList.php';
include_once './ElementNode.php';
include_once './NodeList.php';
include_once './Renderer.php';
include_once './TextNode.php';
include_once './Util.php';

use PackageFactory\AtomicFusion\VirtualDom\Attribute;
use PackageFactory\AtomicFusion\VirtualDom\Renderer;
use PackageFactory\AtomicFusion\VirtualDom\AttributeList;
use PackageFactory\AtomicFusion\VirtualDom\ElementNode;
use PackageFactory\AtomicFusion\VirtualDom\NodeList;
use PackageFactory\AtomicFusion\VirtualDom\TextNode;

echo Renderer::renderToStaticMarkup(
    ElementNode::create('ul', AttributeList::create(
        Attribute::create('i-d', 'whatever')
    ), NodeList::create(
        ElementNode::create('li', AttributeList::create(), NodeList::create()),
        ElementNode::create('li', AttributeList::create(), NodeList::create(
            ElementNode::create('pre', AttributeList::create(), NodeList::create(
                TextNode::create('
                    HELLO WORLD!
                ')
            ))
        )),
        ElementNode::create('li', AttributeList::create(), NodeList::create(
            TextNode::create('<a href="#">Test</a>')
        ))
    ))
);
