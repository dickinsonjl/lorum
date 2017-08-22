<?php
require "src/Lorum.php";
// require "vendor/autoload.php";

use dickinsonjl\Lorum;


$lorum = new Lorum();
$lorum->buildCache();

var_dump($lorum->wordPool);
var_dump($lorum->wordsPerPhraseFrequency);
var_dump($lorum->phrasesPerSentenceFrequency);
var_dump($lorum->wordLengthFrequency);