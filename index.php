<?php
require_once 'vendor/autoload.php';

use PackageFactory\TypedFusion\Source;
use PackageFactory\TypedFusion\Tokenizer;

$source = Source::createFromFile('./component.fusion');
$tokenizer = Tokenizer::create($source);

foreach ($tokenizer as $token) {
    var_dump($token);
}

// foreach ($source as $fragment) {
//     printf(
//         "%-10s %-5s %-5s\n", 
//         $fragment->getValue(),
//         $fragment->getStart()->getRowIndex(),
//         $fragment->getStart()->getColumnIndex()
//     ); 
// }
