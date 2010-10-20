<?php

require_once 'StringIndexer.php';

/**
 * StringSearch
 *
 * @author Marshall
 */
class StringSearch {

	private $stringIndex;

	private $words;
	private $wordPositions;
	private $patternIndex;

	public function  __construct($string) {
		$this->stringIndex = new StringIndexer();
		$this->stringIndex->indexString($string);

	}

	/**
	 *
	 * @param string $string
	 * @return bool
	 */
	public function isStringPresent($string) {
		$words = explode(' ', $string);
		$wordPositions = array();

		foreach($words as $word) {
			$wordPositions[$word] = $this->stringIndex->getIndexArrayOfWord($word);
			if(!$wordPositions[$word]) {
				return false;
			}
		}

		foreach($wordPositions[$words[0]] as $position) {
			$continue = true;
			for($i = 1; $i < count($wordPositions) && $continue; $i++) {

				$wordIsPresent = false;
				for($y = 0; $y < count($wordPositions[$words[$i]]); $y++) {
					
					if($position  + $i == $wordPositions[$words[$i]][$y]) {

						$wordIsPresent = true;
					}
				}
				if(!$wordIsPresent) {
					$continue = false;
				}
			}
		}
		return true;
	}
	
	/**
	 *
	 * @param string $string string to match, %p is replaced with the pattern
	 * @param string $pattern regex pattern
	 * @return array matches to pattern
	 */
	public function searchContainingPattern($string, $pattern) {
		$this->words = explode(' ', $string);
		$this->patternIndex = array_search('%p', $this->words);

		$this->getWordPositions();

		$matchingIndexs = array();

		for($z = 0; $z < count($this->wordPositions[$this->words[0]]); $z++) {
			if($this->areAllWordsPresent($z)) {
				$matchingIndexs[] = $z;
			}
		}

		return $this->getMatchesToPattern($matchingIndexs, $pattern);

	}

	private function getWordPositions() {
		$words = &$this->words;
		$wordPositions = &$this->wordPositions;
		$wordPositions = array();

		for($t = 0; $t < count($words); $t++) {
			$searchResult = array_search($t, $this->patternIndex);

			if($t != $this->patternIndex/*$searchResult && is_bool($searchResult)*/) {
				$wordPositions[$words[$t]] = $this->stringIndex->getIndexArrayOfWord($words[$t]);
				if(!$wordPositions[$words[$t]]) {
					return false;
				}
			}
		}
	}

	private function areAllWordsPresent($z) {
		$continue = true;
		for($i = 1; $i < count($this->wordPositions) && $continue; $i++) {
			if(!$i == $this->patternIndex) {

				if(!$this->isWordPresent($i, $z)) {
					$continue = false;
				}
			}
		}

		return $continue;
	}

	private function isWordPresent($i,$z) {
		$wordIsPresent = false;

		$positions = &$this->wordPositions;
		$words = &$this->words;

		for($y = 0; $y < count($positions[$words[$i]]); $y++) {

			if($positions[$words[0]][$z]  + $i == $positions[$words[$i]][$y]) {

				$wordIsPresent = true;
			}
		}

		return $wordIsPresent;
	}


	private function getMatchesToPattern($matchingIndexs, $pattern) {
		$matches = array();
		foreach($matchingIndexs as $index) {
			$string = $this->stringIndex->getWordAtIndex($this->wordPositions[$this->words[0]][$index] + $this->patternIndex);
			if(preg_match($pattern, $string)) {
				$matches[] = $string;
			}
		}

		return $matches;
	}

    
}


?>
