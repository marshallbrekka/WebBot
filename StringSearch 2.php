<?php
require_once 'DOMParser.php';
require_once 'StringIndexer.php';

/**
 * StringSearch
 *
 * @author Marshall
 */
class StringSearch {

	private $stringIndex;

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
	 * @return string matched pattern
	 */
	public function searchContainingPattern($string, $pattern) {
		echo "********\n\nsearch started\n\n\n";
		$words = explode(' ', $string);
		$wordPositions = array();

		$patternIndex = array_search('%p', $words);

		print_r($words);

		$matchingIndexs = array();

		for($t = 0; $t < count($words); $t++) {
			if($t != $patternIndex) {
				$wordPositions[$words[$t]] = $this->stringIndex->getIndexArrayOfWord($words[$t]);
				print_r($wordPositions[$words[$t]]);
				echo "\n****\n post print\n";
				if(!$wordPositions[$words[$t]]) {

					return false;
				}
			}
		}
		print_r($wordPositions);
		for($z = 0; $z < count($wordPositions[$words[0]]); $z++) {
			$continue = true;
			for($i = 1; $i < count($wordPositions) && $continue; $i++) {
				if(!$i == $patternIndex) {
					$wordIsPresent = false;
					for($y = 0; $y < count($wordPositions[$words[$i]]); $y++) {

						if($wordPositions[$words[0]][$z]  + $i == $wordPositions[$words[$i]][$y]) {

							$wordIsPresent = true;
						}
					}
					if(!$wordIsPresent) {
						$continue = false;
					}
				}

				
			}
			if($continue) {
				$matchingIndexs[] = $z;
			}
		}

		print_r($matchingIndexs);

		foreach($matchingIndexs as $index) {
			$string = $this->stringIndex->getWordAtIndex($wordPositions[$words[0]][$index] + $patternIndex);
			if(preg_match($pattern, $string)) {
				$words[$patternIndex] = $string;
				return implode(' ', $words);
			}
		}
		return false;
	}
	


    
}


?>
