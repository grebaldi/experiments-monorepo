<?php
declare(strict_types=1);
namespace PackageFactory\TypedFusion\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

abstract class BaseTestCase extends TestCase
{
    use MatchesSnapshots;

    public function fixtures()
    {
        $fixtures = new \DirectoryIterator(
            dirname((new \ReflectionClass($this))->getFileName()) .
            DIRECTORY_SEPARATOR .
            'fixtures'
        );

        foreach ($fixtures as $fixture) {
            if (!$fixture->isDir()) {
                yield $fixture->getFilename() => [$fixture->getPathname()];
            }
        }
    }

    protected function getSnapshotDirectory(): string
    {
        return dirname((new \ReflectionClass($this))->getFileName()).
            DIRECTORY_SEPARATOR.
            'snapshots';
    }
}