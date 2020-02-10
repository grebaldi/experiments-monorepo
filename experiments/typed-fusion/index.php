<?php
require_once 'vendor/autoload.php';

use PackageFactory\TypedFusion\Source\Source;
use PackageFactory\TypedFusion\Lexer\Tokenizer;

$source = Source::createFromFile('./failure.fusion');
$tokenizer = Tokenizer::create($source);

$index = 0;
foreach ($tokenizer as $token) {
    printf(
        "%-30s %-5s %-5s %s\n",
        $token->getType(),
        $token->getStart()->getRowIndex(),
        $token->getStart()->getColumnIndex(),
        str_replace("\n", " ", substr($token->getValue(), 0, 80))
    );
}
