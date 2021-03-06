<?php
require "vendor/autoload.php";

use Dickinsonjl\Lorum\Lorum;


$lorum = new Lorum();
$lorum->buildCache(); // build text Catalogue from LorumSeed file


echo $lorum->giveMeWord(1) . PHP_EOL; // specify word length as argument
echo $lorum->giveMeWord(2) . PHP_EOL;
echo $lorum->giveMeWord(3) . PHP_EOL;
echo $lorum->giveMeWord(4) . PHP_EOL;
echo $lorum->giveMeWord(5) . PHP_EOL;
echo $lorum->giveMePhrase() . PHP_EOL;
echo $lorum->giveMeSentence() . PHP_EOL;
echo $lorum->giveMeSentence(1) . PHP_EOL; // specify number of phrases for the sentence
echo $lorum->giveMeParagraph() . PHP_EOL;
echo $lorum->giveMeMultiParagraph(5) . PHP_EOL; // specify number of paragraphs