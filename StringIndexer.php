<?php


/**
 * StringIndexer
 *
 * @author Marshall
 */
class StringIndexer {

	private $positionsOfWordIndex = array();
	private $wordAtPositionIndex = array();

	private $string;
	private $stringLength;
	private $lastIndex = 0;
	private $wordIndex = 0;

	public function indexString($string) {
		$this->resetVariables();
		$this->string = trim($string);
		$this->stringLength = strlen($string);
		$word = $this->getNextWord();
		while($word) {
			
			$this->logWord($word);
			$word = $this->getNextWord();
		}


	}

	private function resetVariables() {
		$this->lastIndex = 0;
		$this->wordIndex = 0;
		$this->positionsOfWordIndex = array();
		$this->wordAtPositionIndex = array();
	}

	private function getNextWord() {
		if($this->lastIndex == $this->stringLength) {
			return false;
		}
		
		$index = strpos($this->string , ' ', $this->lastIndex + 1);

		$length = $this->getWordLength($index);



		if($length) {
			$word = substr($this->string, $this->lastIndex, $length);
			$this->lastIndex += $length;
			return trim($word);
		} else {
			return false;
		}

	}

	private function getWordLength($index) {
		$length = 0;
		if($index) {
			 $length = $index - $this->lastIndex;
		} else if($this->stringLength - $index) {
			$length = $this->stringLength - $this->lastIndex;
		}
		return $length;
	}

	private function logWord($word) {
		$comma = '';
		$this->positionsOfWordIndex[$word];

		if(!empty($this->positionsOfWordIndex[$word])) {
			$comma = ',';
		}

		$this->positionsOfWordIndex[$word] .= $comma . $this->wordIndex;

		$this->wordAtPositionIndex[$this->wordIndex] = &$word;
		$this->wordIndex++;
	}

	public function getIndexArrayOfWord($word) {
		if(isset($this->positionsOfWordIndex[$word])) {
			return explode(',', $this->positionsOfWordIndex[$word]);
		}
		return false;
	}

	public function getWordAtIndex($index) {
		if(isset($this->wordAtPositionIndex[$index])) {
			return $this->wordAtPositionIndex[$index];
		}
		return false;
	}


}


?>
