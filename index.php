<?php
require "src/Lorum.php";
// require "vendor/autoload.php";

use dickinsonjl\Lorum;


$lorum = new Lorum();
$lorum->buildCache();

// var_dump($lorum->wordPool);
// var_dump($lorum->wordsPerPhraseFrequency);
// var_dump($lorum->phrasesPerSentenceFrequency);
// var_dump($lorum->wordLengthFrequency);
echo $lorum->giveMeWord(4) . PHP_EOL;
echo $lorum->giveMeSentence() . PHP_EOL;
echo $lorum->giveMeParagraph() . PHP_EOL;