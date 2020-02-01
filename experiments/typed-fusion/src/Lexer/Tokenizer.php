<?php
namespace PackageFactory\TypedFusion\Lexer;

use PackageFactory\TypedFusion\Source\Source;
use PackageFactory\TypedFusion\Source\Fragment;

final class Tokenizer implements \IteratorAggregate
{
    use Tokenize\Root;

    use Tokenize\Comment;
    use Tokenize\Expression;
    use Tokenize\IdentifierOrKeyword;
    use Tokenize\IncludeStatement;
    use Tokenize\Number;
    use Tokenize\RestOrPathSeparator;
    use Tokenize\StringLiteral;
    use Tokenize\TypeAssignment;
    use Tokenize\TypeOrPrototypeDeclaration;
    use Tokenize\TypeOrPrototypeName;
    use Tokenize\ValueAssignment;
    use Tokenize\Whitespace;

    /**
     * @var Source
     */
    private $source;

    /**
     * @param Source $source
     */
    private function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * @param Source $source
     * @return Tokenizer
     */
    public static function create(Source $source): Tokenizer
    {
        return new Tokenizer($source);
    }

    /**
     * @return iterable<Token>
     */
    public function getIterator(): iterable
    {
        $iterator = new \IteratorIterator($this->source);
        $iterator->rewind();

        return $this->tokenizeRoot($iterator);
    }
}