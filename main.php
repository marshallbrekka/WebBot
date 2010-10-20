<?php
require_once 'DOMParser.php';
require_once 'StringSearch 2.php';

/**
 * Description of main
 *
 * @author Marshall
 */
class main {

	private $search;
	private $parser;

	private $searchTerms = array(
		'new' => 'New %p',
		'used' => 'Used %p',
		'marketplace' => 'Marketplace %p',
		'semester' => 'Semester %p',
		'quarter' => 'Quarter %p',
		'60day' => '60 day %p'
	);



	public function getPrices($url) {
		$searchResults = array();
		$pattern = '/^\$[0-9]{1,}(.[0-9]{2})?$/i';

		$start = time();

		$this->parse = new DOMParser();
		
		$source = $this->parse->parseFile($url);

		$this->search = new StringSearch($source);


		foreach($this->searchTerms as $key => $value) {
			$result = $this->search->searchContainingPattern($value, $pattern);
			print_r($result);
			if(!$result) {
				echo 'search failed';
			}
			if(!empty($result)) {
				$searchResults[$key] = $result;
			}
		}
		$end = time() - $start;

		echo "\ntime it took in seconds " . $end;
		return $searchResults;

	}



}

$m = new main();
$url = 'http://www.textbooks.com/ISBN/9780321500243/Mario-F-Triola/Elementary-Statistics---With-CD_-_0321500245.php';
print_r($m->getPrices($url));
?>
