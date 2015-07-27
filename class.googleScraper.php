<?php
date_default_timezone_set('utc');
/*Google Scraper Class*/
/**
 argument 1 is to set the no of results per page b/w ranging 1-8
 argument 2 is to set the no pages to be scraped
 argument 3 is the Excel sheet Name to be read for building the query 
 Please ensure there are not spaces in the file name(Excel File)
*/
include ('scraper.lib.php');

class googleScraperX{

	private $category;
	private $resultSet;
	private $city;
	private $handle;

	public function __construct($arg1){
		$this -> handle = fopen('funds-final2.csv', 'a+');
		// fputcsv($this -> handle, array('(ID)','Company Name','Type','Address','City','State','ZIP','Country','Phone','Website','Inception Date','Jurisdiction','SEC Number','Employees (from Statements->Employees, most recent)'	,'Contact Name 1','Contact Title 2'));
		$url = 'https://www.graypools.com/fund-manager/updated/';
		$i=80;
		$flag = 0;
		$handle = fopen('funds.csv', 'r');
		while ($row = fgetcsv($handle)) { 
			if(!empty($row[0]) && $flag == 1)
			{
				echo "\n\n".$urls = $row[0];
				$html = file_get_html($urls,'www.graypools.com');//'
				// $html = str_get_html(file_get_contents('details.html'));
				$this -> initScraperSearch($html);
				$i = $i+1;
			}													  
			if($row[0] == 'https://www.graypools.com/fund-manager/801-55132' && $flag == 0){
				echo $row[0];
				$flag = 1;
			}

		}
		
		
	}

	private function initScraperSearch($arg){
				echo $arg;
				$tmp  = new stdClass();
				$tmp -> Title = str_replace("Fund Manager","",$arg -> find('.container-fluid .row',0) -> plaintext);
				$mainpannel = $arg -> find('.container-fluid .row',3);
				// $tmp -> address = $mainpannel -> find('.row',0) ;//-> find('.gp-panel p') -> plaintext;
				if($mainpannel)
				{
					$address = $mainpannel;
					$details = explode("<br />", $address);
					$tmp -> address = $details[1];
					$zipcitystate = explode(",", $details[2]);
					$tmp -> city = $zipcitystate[0];
					$tmp -> state = $zipcitystate[1];
					$tmp -> zip = $zipcitystate[2];
					$tmp -> country = $details[3];
					if(strpos($details[4]," <a") === false)
						$tmp -> phone = str_replace('Phone:','',$details[4]);
					else{
						$tmp -> phone = '';
						$tmp -> website = $address -> find('a',0) -> href;
					}
					if(!isset($tmp -> website) && $address){
						$tmp -> website = $address -> find('a',0) -> href;
					}
					$other = $mainpannel -> find('.row .gp-3-6-4',0) -> find("#role-list",0);
					if($other){
						$tmp -> contactName = $other -> find('dd',0) -> plaintext;
						$tmp -> contactTitle = $other -> find('dt',0) -> plaintext;
						$tmp -> contactName2 = $other -> find('dd',1) -> plaintext;
						$tmp -> contactTitle2 = $other -> find('dt',1) -> plaintext;
						$tmp -> contactName3 = $other -> find('dd',2) -> plaintext;
						$tmp -> contactTitle3 = $other -> find('dt',2) -> plaintext;
					}
					else{
						$tmp -> contactName = '';
						$tmp -> contactTitle = '';
						$tmp -> contactName2 = '';
						$tmp -> contactTitle2 = '';
						$tmp -> contactName3 = '';
						$tmp -> contactTitle3 = '';
					}
					if( $mainpannel -> find('.row',1) -> find('.gp-3-6-4',1)){
						$other = $mainpannel -> find('.row',1) -> find('.gp-3-6-4',1) -> find("dl",0);
						$tmp -> inspectionDate = $other -> find('dd',0) -> plaintext;
						$tmp -> LastFilling = $other -> find('dd',1) -> plaintext;
						$tmp -> Judriction = $other -> find('dd',2) -> plaintext;
						$tmp -> sec = $other -> find('dd',3) -> plaintext;	
					}
					else{
						$tmp -> inspectionDate = '';
						$tmp -> LastFilling = '';
						$tmp -> Judriction = '';
						$tmp -> sec = '';
					}
					
					$other = $mainpannel -> find('.row',1) -> find('.gp-3-6-5',0) -> find(".tab-content #employees",0);
					if($other)
						$tmp -> Employee = $other -> find('tr',1) -> find('td',1) -> plaintext;
					print_r(array($tmp -> sec,$tmp -> Title, 'Funds Manager',$tmp -> address,$tmp -> city,$tmp->state,$tmp->zip,$tmp->country,$tmp->phone,$tmp->website,$tmp->inspectionDate,$tmp->LastFilling,$tmp -> Judriction,$tmp->sec,$tmp->Employee,$tmp->contactName,$tmp->contactTitle,$tmp->contactName2,$tmp->contactTitle2,$tmp->contactName3,$tmp->contactTitle3));
					fputcsv($this -> handle, array($tmp -> sec,$tmp -> Title, 'Funds Manager',$tmp -> address,$tmp -> city,$tmp->state,$tmp->zip,$tmp->country,$tmp->phone,$tmp->website,$tmp->inspectionDate,$tmp->LastFilling,$tmp -> Judriction,$tmp->sec,$tmp->Employee,$tmp->contactName,$tmp->contactTitle,$tmp->contactName2,$tmp->contactTitle2,$tmp->contactName3,$tmp->contactTitle3));
				}
				sleep(2);
				// $arg = $arg -> find('#gp-navigation-results .row');
				// foreach ($arg as $key => $value) {
				// 	$link = $value -> find('a',0) -> href;
				// 	$name = $value -> find('a',0) -> plaintext;
				// 	$details = $value -> find('.gp-expand',0) -> plaintext;
				// 	$details = explode(",", $details);
				// 	$state = $details[1];
				// 	$city = $details[0];

				// 	print_r(array($link,$name,$state,$city));
				// 	fputcsv($this -> handle, array($link,$name,$state,$city));
				// }
				// if(is_object($arg))
				// {	
				// 	$tmp ->  url = $arg1;
				// 	$tmp -> Title = $this -> cleanString(html_entity_decode($arg -> find('h1.kennel',0) -> plaintext));
				// 	$tmp -> breederOf = $this -> cleanString(html_entity_decode($arg -> find('ul.bullet',0) -> plaintext));
				// 	$tmp -> address = $this -> getAddress($arg);
				// 	$tmp -> email = is_object($arg -> find('p.header a',0)) ? $this -> getValues($arg -> find('p.header a'),'email') : '';
				// 	$tmp -> website = is_object($arg -> find('p.header a',2)) ? $this -> redirectedURL($arg -> find('p.header a',2) -> href) : 'NA';
				// 	print_r($tmp);
				// 	fputcsv($this -> handle, array($tmp -> url, $tmp -> Title , trim($tmp -> breederOf), $tmp -> address, $tmp -> email, $tmp -> website));	
				// 	unset($tmp);		
				// }
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