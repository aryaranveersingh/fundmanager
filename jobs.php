<?php
date_default_timezone_set('utc');
/*Google Scraper Class*/
/**
 argument 1 is to set the no of results per page b/w ranging 1-8
 argument 2 is to set the no pages to be scraped
 argument 3 is the Excel sheet Name to be read for building the query 
 Please ensure there are not spaces in the file name(Excel File)
*/
include ('scraper.lib1.php');

class googleScraperX{

	private $category;
	private $resultSet;
	private $categoryLinks;
	private $handle;

	public function __construct($arg1){
		$categoryLinks = array();
		$this -> handle = fopen('jobs.csv', 'a+');
		
		// fputcsv($this -> handle, array('(ID)','Company Name','Type','Address','City','State','ZIP','Country','Phone','Website','Inception Date','Jurisdiction','SEC Number','Employees (from Statements->Employees, most recent)'	,'Contact Name 1','Contact Title 2'));
		$url = 'https://www.jobsbank.gov.sg/ICMSPortal/portlets/JobBankHandler/loadCategoryValues.do?funcType=category&funcSubType=&index=';
		
		$html = file_get_html($url,array('www.jobsbank.gov.sg','0'));//'
		// $html = str_get_html(file_get_contents('jobs.html'));
		$category = json_decode($html);
		$tmpcategoryLinks = array();
		foreach ($category -> CategoryVal as $key => $value) {
			$tmpcategoryLinks[] = $value -> key;
		}
		
		foreach ($tmpcategoryLinks as $key => $value) {
			$base = "https://www.jobsbank.gov.sg/ICMSPortal/portlets/JobBankHandler/SearchResult.do?tabSelected=CATEGORY&amp;aTabFunction=aTabFunction&amp;Function=".urlencode(str_replace("/","%2F",$value));
			$this -> categoryLinks[] = $base;
		}
		
		foreach ($this -> categoryLinks as $key => $value) {
			
			$html = file_get_html($value,array('www.jobsbank.gov.sg',1));
			$data = $html -> find('section#searchresult .jobDesActive');
			foreach ($data as $keyin => $valuein) {
				echo $newbase = 'https://www.jobsbank.gov.sg'.$valuein -> find('a',0) ->href;
				$tmp = explode("?id=", $valuein -> find('a',0) ->href);
				$key = count($tmp) - 1;
				$jobid = $tmp[$key];
				$mewhtml = str_get_html($this -> getURLSource($newbase,$jobid));
				$this -> initScraperSearch($mewhtml);
			}
		};


		
	}

	private function initScraperSearch($arg){
				$tmp  = new stdClass();
				$tmp -> Title = $arg -> find('.jd_header1',0) -> plaintext;
				$tmp -> Desc = $arg -> find('.jobScope',0) -> plaintext;
				$tmp -> Req = $arg -> find('.requDetails',0) -> plaintext;
				$tmp -> email = $arg -> find('.jd_contentLeft',0) -> plaintext;
				print_r($tmp);
				exit();
				sleep(2);

	}

	private function cleanString($input){
		return preg_replace("/[^a-zA-Z\ ]+/", "", $input);
	}

	private function getAddress($arg){
		if(is_object($arg -> find('p.header',0))){
			$text  = $arg -> find('p.header',0);
			$text -> find('a', 0) -> innertext = '';
			$text -> find('a', 1) -> innertext = '';
			$text = explode("<br>",($text -> innertext));
			$text[0] = '';
			$text = str_get_html(implode(" ", $text));
			$arg =  $this -> cleanString(html_entity_decode($text -> plaintext));
		}
		
		return $arg;
	}
	 //EXECUTING CURL METHID TO GET THE DATA FROM THE WEB
	private function getURLSource($url_to_scan, $next = "") {
		$headers = array();
		$headers[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		$headers[] = 'Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3'; 
		$headers[] = 'Accept-Language:en-US,en;q=0.8';
		$headers[] = 'Cache-Control:max-age=0';
		$headers[] = 'Connection:keep-alive';
		$headers[] = 'Host:www.jobsbank.gov.sg';
		$headers[] = 'Referer:'.$url_to_scan;
		
		$agent = $this -> getRandomUserAgent();
		
		// $cURL = curl_init();
		// curl_setopt($cURL, CURLOPT_URL, 'https://www.jobsbank.gov.sg/ICMSPortal/portlets/JobBankHandler/SearchDetail.do?id='.$next);
		// curl_setopt($cURL, CURLOPT_HTTPHEADER, $headers);
		// curl_setopt($cURL, CURLOPT_HEADER, TRUE);
  //   	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, 1);
		// curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT,20);
		// curl_setopt($cURL, CURLOPT_TIMEOUT,30);
		// curl_setopt($cURL, CURLOPT_USERAGENT, $agent);
		// curl_setopt($cURL, CURLOPT_COOKIEJAR,"thaipost.txt"); 
		// curl_setopt($cURL, CURLOPT_COOKIEFILE,"thaipost.txt");
		// $data = curl_exec($cURL);
		// curl_close($cURL);
		?>
		
		<?php
echo "<img src='https://www.jobsbank.gov.sg/_Incapsula_Resource?SWHANEDL=8911442444680583929,7036256012373642858,6249913319570971896,237812' />";

		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_URL, 'https://www.jobsbank.gov.sg/_Incapsula_Resource?SWHANEDL=8911442444680583929,7036256012373642858,6249913319570971896,237812');
    	curl_setopt($cURL, CURLOPT_HEADER, TRUE);
    	curl_setopt($cURL, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT,20);
		curl_setopt($cURL, CURLOPT_TIMEOUT,30);
		curl_setopt($cURL, CURLOPT_POST, 1);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, NULL);
		curl_setopt($cURL, CURLOPT_USERAGENT, $agent);
		curl_setopt($cURL, CURLOPT_COOKIEJAR,"thaipost.txt"); 
		curl_setopt($cURL, CURLOPT_COOKIEFILE,"thaipost.txt");
		$data = curl_exec($cURL);
		curl_close($cURL);
		echo $data;
echo 'https://www.jobsbank.gov.sg/ICMSPortal/portlets/JobBankHandler/SearchDetail.do?id='.$next;
		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_URL, 'https://www.jobsbank.gov.sg/ICMSPortal/portlets/JobBankHandler/SearchDetail.do?id='.$next);
		curl_setopt($cURL, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($cURL, CURLOPT_HEADER, TRUE);
    	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT,20);
		curl_setopt($cURL, CURLOPT_TIMEOUT,30);
		curl_setopt($cURL, CURLOPT_USERAGENT, $agent);
		curl_setopt($cURL, CURLOPT_COOKIEJAR,"thaipost.txt"); 
		curl_setopt($cURL, CURLOPT_COOKIEFILE,"thaipost.txt");
		$data = curl_exec($cURL);
		curl_close($cURL);

		echo $data;
		return $data;
	
	}

	private function getRandomUserAgent() {
	    $user_agent[0] = "Mozilla/5.001 (windows; U; NT4.0; en-us) Gecko/25250101";
	    $user_agent[1] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)";
	    $user_agent[2] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
	    $user_agent[3] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
	    $user_agent[4] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.4) Gecko/20070515 Firefox/2.0.0.4";
	    $user_agent[5] = "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3";  
	    $user_agent[6] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.132 Safari/537.36";
	    return $user_agent[rand(0,6)];
	}
	private function redirectedURL($url){
		$url = html_entity_decode($url);
		echo "\n\nFetching Original URL..".$url;
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:  text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'Connection:  keep-alive', 'Host:'.parse_url($url,PHP_URL_HOST), 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36'));
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
	    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    $data = curl_exec($ch);
		if(curl_errno($ch)){
			return '';
		}

		$st = str_get_html($data);
		if(is_object($st)) $url = $st -> find('body a',0) -> href;
		return $redir = !empty($url) ? trim($url) : $url;
	}

	private function getValues($ag,$type){
		foreach ($ag as $key => $value) {
			if($type == 'email'){
					$email  = (strpos($value -> href, 'mailto:') === 0) ? str_replace("mailto:", "", $value -> href) : null;
					if(!is_null($email) && $type == 'email') return $email;
				}
			if($type == 'website'){
					$website = (strpos($value -> href, 'maps.google.com') > 0) ? null : $value -> href;
					if(!is_null($website) && $type == 'website') return $website;
			}

		}
		var_dump($website);
		if(empty($email) && $type == 'email') return '';
		if(empty($website) && $type == 'website') return '';

	}

}


$tmp = new googleScraperX('http://www.construction.co.uk/directory.asp?dirtype=2');	