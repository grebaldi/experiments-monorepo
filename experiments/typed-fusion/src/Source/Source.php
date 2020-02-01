<?php
namespace PackageFactory\TypedFusion\Source;

final class Source implements \IteratorAggregate
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $contents;

    /**
     * @param string $filename
     * @param string $contents
     */
    private function __construct(
        string $filename,
        string $contents
    ) {
        $this->filename = $filename;
        $this->contents = $contents;
    }

    /**
     * @param string $contents
     * @return Source
     */
    public static function createFromString(string $contents): Source
    {
        return new Source(':memory:', $contents);
    }

    /**
     * @param string $filename
     * @return Source
     */
    public static function createFromFile(string $filename): Source
    {
        return new Source(
            $filename,
            file_get_contents($filename)
        );
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @return iterable<Fragment>
     */
    public function getIterator(): iterable
    {
        $rowIndex = 0;
        $columnIndex = 0;
        $length = strlen($this->contents);

        for ($index = 0; $index < $length; $index++) {
            $character = $this->contents{ $index };

            yield Fragment::create(
                $character,
                Position::create($index, $rowIndex, $columnIndex),
                Position::create($index, $rowIndex, $columnIndex),
                $this
            );

            if ($character === "\n") {
                $rowIndex++;
                $columnIndex = 0;
            } else {
                $columnIndex++;
            }
        }
    }
}