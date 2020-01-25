<?php
require_once 'vendor/autoload.php';

use PackageFactory\TypedFusion\Source;
use PackageFactory\TypedFusion\Tokenizer;

$source = Source::createFromFile('./meta.fusion');
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

    #if ($index++ > 10) die;
}
// foreach ($source as $fragment) {
//     printf(
//         "%-10s %-5s %-5s\n", 
//         $fragment->getValue(),
//         $fragment->getStart()->getRowIndex(),
//         $fragment->getStart()->getColumnIndex()
//     ); 
// }
