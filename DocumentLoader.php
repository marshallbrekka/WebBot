<?php

/**
 * DocumentLoader
 *
 * @author Marshall
 */
class DocumentLoader {
    private $urls = array();
	private $pages = array();
	private $internalPointer = 0;
	private $directory;

	public function addUrls($urls) {
		$this->urls = array();
		$this->urls = array_merge($this->urls, $urls);
	}

	public function getNext() {
		if($this->internalPointer < size($this->pages)) {
			return $this->pages[$this->internalPointer++];
		} else {
			return false;
		}
		
	}

	private function loadUrls() {
		$this->pages = array();

		foreach($this->urls as $url) {
			$data = $this->loadWebPage($url);
			if($data) {
				$this->pages[] = array($url, $data);
			}
		}
	}
	
	private function loadWebPage($url) {
		$timeout = 5;
		$curlObject = curl_init();

		
		curl_setopt($curlObject, CURLOPT_URL, $url);
		curl_setopt($curlObject, CURLOPT_FAILONERROR, true);
		curl_setopt($curlObject, CURLOPT_CONNECTTIMEOUT, $timeout);

		$data = curl_exec($curlObject);

		curl_close($curlObject);

		return $data;
	}

}

?>
