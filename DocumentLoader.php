<?php

/**
 * DocumentLoader
 *
 * @author Marshall
 */
class DocumentLoader {
    private $urls = array();
	private $pages = array();
	private $directory;

	public function  __construct($urls = array()) {
		$this->urls = $urls;
	}

	public function addUrls($urls) {
		$this->urls = array_merge($this->urls, $urls);
	}

	public function resetUrls() {
		$this->urls = array();
	}

	public function getUrlsAsArray() {
		$this->loadUrls('array');
	}

	public function getUrlsAsFiles($directory) {

	}

	private function loadUrls($destination) {
		$this->pages = array();

		foreach($this->urls as $url) {
			$data = $this->loadWebPage($url);
			if(!$data) {
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

	private function writeToFile($fileName, $data) {

	}
}
?>
