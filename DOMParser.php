<?php


class DOMParser {
	
	private $bannedTags = array('false','script','style');
	private $blockTags = array('false','option','div','p','h1','h2','h3','h4','h5','h6','br','nobr');

	

	private $stripped = '';

	private function stripTags($element) {

		if(!$this->isElementAllowed($element)){
		
			return false;
		
		} else if($element->hasChildNodes()) {
		
			if(array_search($element->tagName, $this->blockTags)) {
				$this->stripped .= " ";
			}
		
			foreach($element->childNodes as $child) {
			
				$this->stripTags($child);
			
			}
		
		} else {
		
			if($this->isImg($element)) $this->extractAltTag($element);
		
			$this->stripped .= $element->nodeValue;
		
			if(array_search($element->tagName, $this->blockTags)) {
				$this->stripped .= " ";
			}
		
		}
	}

	private function isElementAllowed($element) {
		/* node type 8 is an html comment */
		if($element->nodeType == 8) {
			return false;
		} else if(array_search($element->tagName, $this->bannedTags)) {
			return false;
		} else {
			return true;
		}
	}

	private function isImg($element) {
		return $element->tagName == 'img';
	}

	private function extractAltTag($element){
		if($element->hasAttribute('alt')) {
			$this->stripped .= $element->getAttribute('alt') . " ";
		}	
	}

	private function stripPunctuation() {
		$this->stripped = preg_replace('/[,\|-]|(\.(?![0-9]))/',' ', $this->stripped);
		$this->stripped = preg_replace('/ \?/',' ', $this->stripped);
	}

	private function stripWhitespace() {
		$this->stripped = preg_replace('/\s+/',' ', $this->stripped);
	}
	public function parseFile($fileName) {
		$html = file_get_contents($fileName);
		return $this->parseString($html);
	}

	public function parseString($html) {
		$this->stripped = '';
		$doc = new DOMDocument(); 
		$doc->loadHTML($html);
		$children = $doc->getElementsByTagName('body');
		foreach($children as $child) {
			$this->stripTags($child);
		}
	
		$this->stripPunctuation();
		$this->stripWhitespace();

		return $this->stripped;
	}

}

?>