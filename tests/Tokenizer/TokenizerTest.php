<?php
declare(strict_types=1);

use PackageFactory\TypedFusion\Source\Source;
use PackageFactory\TypedFusion\Lexer\Tokenizer;

use PackageFactory\TypedFusion\Tests\BaseTestCase;

final class TokenizerTest extends BaseTestCase
{
    /**
     * @test
     * @dataProvider fixtures
     */
    public function test($filename): void
    {
        $source = Source::createFromFile($filename);
        $tokenizer = Tokenizer::create($source);

        $lines = [''];
        $lines[] = sprintf(
            "%-30s %-5s %-5s %-5s %-5s %-5s %-5s %s",
            'TYPE',
            'S_IDX',
            'S_ROW',
            'S_COL',
            'E_IDX',
            'E_ROW',
            'E_COL',
            'VALUE'
        );
        foreach ($tokenizer as $token) {
            $lines[] = sprintf(
                "%-30s %-5s %-5s %-5s %-5s %-5s %-5s %s",
                $token->getType(),
                $token->getStart()->getIndex(),
                $token->getStart()->getRowIndex(),
                $token->getStart()->getColumnIndex(),
                $token->getEnd()->getIndex(),
                $token->getEnd()->getRowIndex(),
                $token->getEnd()->getColumnIndex(),
                str_replace("\n", " ", $token->getValue())
            );
        }

        $lines[] = '';
        $stream = implode("\n", $lines);

        $this->assertMatchesSnapshot($stream);
    }
}