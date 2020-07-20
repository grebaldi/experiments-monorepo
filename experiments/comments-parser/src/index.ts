const iterator = document.createNodeIterator(document.body, NodeFilter.SHOW_COMMENT, null);

let node: Node | null;
while (node = iterator.nextNode()) {
    const [key, ...value] = node.nodeValue?.split(':');

    if (key.trim() === 'Node') {
        console.log(JSON.parse(value.join(':')));
    }
    else {
        console.log(key, key === 'Node', node.nodeValue);
    }

    // @Question: 
    console.log((node as Comment).nextElementSibling);
    console.log(node.constructor.name);
}