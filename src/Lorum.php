<?php

namespace Dickinsonjl\Lorum;

class Lorum {

    public $punctuationFrequency = array(
        '.' => 10,
        '!' => 2,
        '?' => 3,
    );
    public $wordsPerPhraseFrequency = array(
        5 => 1,
        6 => 5,
        7 => 4,
        8 => 2,
    );
    public $phrasesPerSentenceFrequency = array(
        1 => 4,
        2 => 3,
        3 => 2,
        4 => 1,
    );
    public $sentencePerParagraphFrequency = array(
        2 => 1,
        3 => 5,
        4 => 3,
        5 => 1,
    );
    public $wordLengthFrequency = array(
        1 => 1,
        2 => 2,
        3 => 5,
        4 => 3,
        5 => 1,
        6 => 1,
    );
    public $wordPool = array(
        1 => array(
            'a',
            'I'
        ),
        2 => array(
            'be',
            'on',
            'of',
            'to',
            'go',
            'no',
            'oh',
            'he',
            'no',
            'hi',
        ),
        3 => array(
            'see',
            'for',
            'can',
            'you',
            'end',
            'how',
            'yes',
            'run',
            'tin',
            'van',
        ),
        4 => array(
            'dear',
            'when',
            'turn',
            'face',
            'dish',
            'rain',
            'made',
            'here',
            'space',
            'pond',
            'hand',
            'bath',
            'joke',
        ),
        5 => array(
            'which',
            'shine',
            'saved',
            
        ),
        6 => array(
            'friend',
            'animal',
            'golfer',
            'people',
            'jumper',
            '',
        )
    );


    public function __construct(){

    }

    public function giveMeMultiParagraph($numberOfParagraphs){
        return trim($this->generateMultiParagraphs($numberOfParagraphs));
    }

    public function generateMultiParagraphs($numberOfParagraphs){
        $mutliParagraphText = '';
        for ($i=0; $i < $numberOfParagraphs; $i++) {
            $mutliParagraphText .= $this->generateParagraph();
        }
        return $mutliParagraphText;
    }

    public function giveMeParagraph(){
        return trim($this->generateParagraph());
    }

    protected function generateParagraph(){
        $paragraphText = '';
        // random number of sentences,
        $numberOfSentences = $this->findALikely($this->sentencePerParagraphFrequency);
        for ($s=0; $s < $numberOfSentences; $s++) {
            $paragraphText .= $this->generateSentence();
        }
        $paragraphText = trim($paragraphText) . PHP_EOL . PHP_EOL;
        return $paragraphText;
    }

    public function giveMeSentence(){
        return trim($this->generateSentence());
    }

    protected function generateSentence(){
            // with random number of phrases,
            $numberOfPhrases = $this->findALikely($this->phrasesPerSentenceFrequency);
            $sentenceText = '';
            $firstPhrase = true;
            for ($p=0; $p < $numberOfPhrases; $p++) {
                if(!$firstPhrase){
                    $sentenceText .= ',';
                }
                $sentenceText .= $this->generatePhrase();
                $firstPhrase = false;
            }
            $sentenceText .= $this->findALikely($this->punctuationFrequency);
            // first word of each sentence should have capital first letter
            return ' ' . ucfirst(trim($sentenceText));
    }

    public function giveMePhrase(){
        return ucfirst(trim($this->generatePhrase()));
    }

    protected function generatePhrase(){
            // with random number of phrases,
            $numberOfPhrases = $this->findALikely($this->phrasesPerSentenceFrequency);
            $phraseText = '';
            // with random number of words,
            $numberOfWords = $this->findALikely($this->wordsPerPhraseFrequency);
            $lastWordLength = 0;
            for ($w=0; $w < $numberOfWords; $w++) {
                // of random length,
                $lengthOfWord = $this->findALikely($this->wordLengthFrequency);
                while(count($this->wordPool) > 1 && $lengthOfWord == $lastWordLength){
                    $lengthOfWord = $this->findALikely($this->wordLengthFrequency);
                }
                // picked at random from the pool
                $theWord = $this->findAWordOfLength($lengthOfWord);
                $lastWordLength = $lengthOfWord;
                $phraseText .= ' ' . $theWord;
            }
            return $phraseText;
    }

    public function giveMeWord($wordLength){
        return trim($this->findAWordOfLength($wordLength));
    }

    protected function findAWordOfLength($theLength){
        if(!isset($this->wordPool[$theLength])){
            $this->doError('No words found of length:' . $theLength);
            return str_repeat('?', $theLength);
        }
        $theWordIndex = array_rand($this->wordPool[$theLength]);
        // echo $theWord;
        return $this->wordPool[$theLength][$theWordIndex];
    }

    protected function findALikely($frequencyIndex){
        $totalFrequency = 0;
        foreach ($frequencyIndex as $fKey => $fValue) {
            $totalFrequency +=$fValue;
        }
        $randomPick = rand(1, $totalFrequency);
        foreach ($frequencyIndex as $fKey => $fValue) {
            $randomPick -= $fValue;
            if($randomPick < 1){
                return $fKey;
            }
        }
    }

    public function buildCache(){
        try{
            $seedClass = new LorumSeed();
            if(!is_null($seedClass)){
                $this->processSeedContent($seedClass->seedText);
            }
        } catch (Exception $e) {
            echo 'LorumSeed error:' . $e->getMessage();
        }
    }

    protected function processSeedContent($seedContent){
        $this->ClearIndexes();

        $paragraphs = explode("\n", trim($seedContent));
        foreach ($paragraphs as $singleParagraph) {
            if(trim($singleParagraph) != ''){
                $sentenceCount = 0;
                $sentences = preg_split( "/(\?|\.|!)/", trim($singleParagraph));
                foreach ($sentences as $singleSentence) {
                    if(trim($singleSentence) != ''){
                        $phraseCount = 0;
                        $sentenceCount++;
                        $phrases = explode(',', trim($singleSentence));
                        foreach ($phrases as $singlePhrase) {
                            if(trim($singlePhrase) != ''){
                                $wordCount = 0;
                                $phraseCount++;
                                $words = explode(' ', trim($singlePhrase));
                                foreach ($words as $singleWord) {
                                    $singleWord = preg_replace("/[^A-Za-z0-9'’′ ]/", "", $singleWord);
                                    if(trim($singleWord) != ''){
                                        $wordCount++;
                                        $realWord = strtolower(trim($singleWord, "' \t\n\r\0\x0B"));
                                        $wordLength = strlen($realWord);
                                        $this->indexWord($realWord); // catalogue unique words found in text
                                    }
                                }
                                $this->indexWordsPerPhrase($wordCount); // as each word is catalogued, update frequency of word length index
                            }
                        }
                        $this->indexPhrasesPerSentence($phraseCount); // as each sentence is processed, i.e. full stop found, update index of words per sentence
                    }
                }
                $this->indexSentencePerParagraph($sentenceCount); // as each paragraph is processed, i.e. return char found, update index of sentences per paragraph
            }
        }
        $this->indexPunctuation($seedContent);
    }

    protected function indexWord($realWord){
        $wordLength = strlen($realWord);
        $this->frequencyIndex('wordLengthFrequency', $wordLength);
        if (!isset($this->wordPool[$wordLength])) {
            $this->wordPool[$wordLength] = array($realWord);
        } else {
            if(!in_array($realWord, $this->wordPool[$wordLength])){
                if($realWord == 'i'){
                    $realWord = 'I';
                }
                $this->wordPool[$wordLength][] = $realWord;
            }
        }
    }

    protected function indexPunctuation($content){
        $punctuations = array('.', '!', '?');
        foreach ($punctuations as $symbol) {
            $occurs = substr_count($content, $symbol);
            $this->punctuationFrequency[$symbol] = $occurs;
        }
    }

    protected function indexWordsPerPhrase($wordCount){
        $this->frequencyIndex('wordsPerPhraseFrequency', $wordCount);
    }

    protected function indexPhrasesPerSentence($phraseCount){
        $this->frequencyIndex('phrasesPerSentenceFrequency', $phraseCount);
    }

    protected function indexSentencePerParagraph($sentenceCount){
        $this->frequencyIndex('sentencePerParagraphFrequency', $sentenceCount);
    }

    protected function frequencyIndex($indexName, $value){
        // index name is "wordsPerPhraseFrequency" etc.
        // value is what we are keeping track of, as duplicated of value are processed we do: index[value]++
        if(!isset($this->$indexName)){
            $this->doError('Cound not find frequency tracker "' . $indexName . '"');
            return;
        }

        if(!isset($this->$indexName[$value])){
            $this->$indexName[$value] = 1;
        } else {
            $this->$indexName[$value]++;
        }
    }

    protected function ClearIndexes(){
        $this->wordsPerPhraseFrequency = array();
        $this->phrasesPerSentenceFrequency = array();
        $this->sentencePerParagraphFrequency = array();
        $this->wordLengthFrequency = array();
        $this->wordPool = array();
    }

    protected function doError($errorTxt){
        echo $errorTxt;
    }
}