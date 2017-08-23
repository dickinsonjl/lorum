<?php
require "src/Lorum.php";
use Dickinsonjl\Lorum\Lorum;
$lorum = new Lorum();
$lorum->buildCache();
echo $lorum->giveMeMultiParagraph(5) . PHP_EOL;