<?php

namespace dickinsonjl;

class Lorum {

    public $seedFile = 'src/example.txt';
    public $wordsPerPhraseFrequency = array(
        5 => 1,
        6 => 5,
        7 => 4,
        8 => 2
    );
    public $phrasesPerSentenceFrequency = array(
        1 => 4,
        2 => 3,
        3 => 2,
        4 => 1
    );
    public $sentencePerParagraphFrequency = array(
        2 => 1,
        3 => 5,
        4 => 3,
        5 => 1
    );
    public $wordLengthFrequency = array(
        1 => 1,
        2 => 2,
        3 => 5,
        4 => 3,
        5 => 1
    );
    public $wordPool = array(
        1 => array(
            'a',
            'i'
        ),
        2 => array(
            'be',
            'on',
            'of'
        ),
        3 => array(
            'see',
            'for',
            'can',
            'you',
            'end',
            'how'
        ),
        4 => array(
            'dear',
            'when',
            'turn',
            'face'
        ),
        5 => array(
            'which',
            'shine',
            'saved'
        )
    );


    public function __construct(){

    }

    public function buildCache(){
        if (file_exists($this->seedFile)) {
            $this->processSeedFile();
        } else {
            $this->doError('Seed File not found! Using defaults.');
        }
    }

    protected function processSeedFile(){
        $this->ClearIndexes();
        $seedContent = file_get_contents($this->seedFile);

        $paragraphs = explode("\n", trim($seedContent));
        foreach ($paragraphs as $singleParagraph) {
            if(trim($singleParagraph) != ''){
                $sentenceCount = 0;
                $sentences = explode('.', trim($singleParagraph));
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
                                    if(trim($singleWord) != ''){
                                        $wordCount++;
                                        $realWord = strtolower(trim($singleWord));
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
    }

    protected function indexWord($realWord){
        $wordLength = strlen($realWord);
        $this->frequencyIndex('wordLengthFrequency', $wordLength);
        if (!isset($this->wordPool[$wordLength])) {
            $this->wordPool[$wordLength] = array($realWord);
        } else {
            if(!in_array($realWord, $this->wordPool[$wordLength])){
                $this->wordPool[$wordLength][] = $realWord;
            }
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