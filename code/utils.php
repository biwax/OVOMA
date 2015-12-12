<?php

// return the HTML of a given web page
function getHTMLFromURL($url) {
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
		CURLOPT_CONNECTTIMEOUT => 5,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
		CURLOPT_USERAGENT => 'Vertical engine'
	));
	// Send the request & save response to $resp
	$html = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
	return $html;
}

function getCurrentDate() {
	global $testMode, $testDate;
	
	if($testMode) {
		return $testDate;
	}	
	
	return new DateTime();
}

function getCurrentYear() {
	global $testMode, $testDate;
	
	if (setlocale(LC_TIME, 'fr_FR') == '') {
		setlocale(LC_TIME, 'FRA');  //correction problème pour windows
	}
	
	if($testMode) {
		return ucfirst(strftime("%Y", $testDate->getTimestamp()));
	}
	return ucfirst(strftime("%Y"));
}

// return the current day name in french, for example "Vendredi"
function getCurrentDayName() {
	global $testMode, $testDate;
	
	if (setlocale(LC_TIME, 'fr_FR') == '') {
		setlocale(LC_TIME, 'FRA');  //correction problème pour windows
	}
	
	if($testMode) {
		return ucfirst(strftime("%A", $testDate->getTimestamp()));
	}
	return ucfirst(strftime("%A"));
}

/**
 * Return the current day number. For example "24"
 */
function getCurrentDayNumber() {
	global $testMode, $testDate;
		
	if (setlocale(LC_TIME, 'fr_FR') == '') {
		setlocale(LC_TIME, 'FRA');  //correction problème pour windows
	}
	
	$format = "%e";
	
	// Windows fonctionne avec un autre modificateur...
	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		$format = "%#d";
	}
	
	if($testMode) {
		return trim(strftime($format, $testDate->getTimestamp()));
	}
	return trim(strftime($format));
}

/**
 * Return the current day name and day number. For example "Mardi 24"
 */
function getCurrentDayNameNumber() {
	global $testMode, $testDate;
		
	if (setlocale(LC_TIME, 'fr_FR') == '') {
		setlocale(LC_TIME, 'FRA');  //correction problème pour windows
	}
	
	$format = "%A %e";
	
	// Windows fonctionne avec un autre modificateur...
	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		$format = "%A %#d";
	}
	
	if($testMode) {
		return ucfirst(removeMultipleWhiteSpaces(strftime($format, $testDate->getTimestamp())));
	}
	return ucfirst(removeMultipleWhiteSpaces(strftime($format)));
}

/**
 * Return the current month name. For example "novembre"
 */
function getCurrentMonthName() {
	global $testMode, $testDate;
		
	if (setlocale(LC_TIME, 'fr_FR') == '') {
		setlocale(LC_TIME, 'FRA');  //correction problème pour windows
	}
	
	$format = "%B";
	
	if($testMode) {
		return ucfirst(strftime($format, $testDate->getTimestamp()));
	}
	return ucfirst(strftime($format));
}

// check if a given date is between a start and end date
function checkInRange($start_date, $end_date, $date_from_user) {
  // Convert to timestamp
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $user_ts = $date_from_user;

  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

function convertMonthNameToNumber($month) {
	switch($month) {
		case 'janvier':
			return 1;
			break;
		case 'février':
			return 2;
			break;
		case 'mars':
			return 3;
			break;
		case 'avril':
			return 4;
			break;
		case 'mai':
			return 5;
			break;
		case 'juin':
			return 6;
			break;
		case 'juillet':
			return 7;
			break;
		case 'août':
			return 8;
			break;
		case 'septembre':
			return 9;
			break;
		case 'octobre':
			return 10;
			break;
		case 'novembre':
			return 11;
			break;
		case 'décembre':
			return 12;
			break;
	}
}

// check if today's date is in the week-end
function isWeekend() {
	global $testMode, $testDate;
	
	$currentDate = date('m/d/Y h:i:s a', time());
	
	if($testMode) {
		$currentDate = $testDate->format('m/d/Y h:i:s a');;
	}
    return (date('N', strtotime($currentDate)) >= 6);
}

// return a full <a> tag given an URL
function getFormattedURL($url, $isMenuDuJour = false) {
	global $menuFormat, $menuDayFormat;
	if($isMenuDuJour) {
		return sprintf($menuDayFormat, $url);
	} else {
		return sprintf($menuFormat, $url);
	}
}

// return website (base URL) from a full URL
function getBaseURL($url) {
	return parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . '/';
}

// check if an URL exists
function URLExists($url) {
	$file_headers = @get_headers($url);
	if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
		return false;
	}
	else {
		return true;
	}
}

// get URL fallback: a full <a> tag with the menu
function getFallbackURL($url) {
	if(endsInsensitiveWith($url, '.pdf')) {
		return getFormattedURL(getBaseURL($url));
	}
	return getFormattedURL($url);
}

function startsInsensitiveWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strripos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function startsInsensitiveWithArray($string, $words) {
	foreach ($words as $word) {
		if (startsInsensitiveWith($string, $word) !== FALSE) {
			return true;
		}
	}
	return false;
}

function endsInsensitiveWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && stripos($haystack, $needle, $temp) !== FALSE);
}

function removeInsensitiveFromString($string, $words) {
	$copy = $string;
	foreach ($words as $word) {
		$copy = str_ireplace($word, '', $copy);
	}
	return $copy;
}

function removeMultipleWhiteSpaces($string) {
	return preg_replace('/\s+/', ' ',$string);
}

function extractFromTextFile($file, $textPattern, $removeEmptyLines = true) {
	// when we reach one of those words, the text will be returned
	$stopWords = ["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche", "Menu complet", "Service et", "Le Hit de la semaine", "Menu 3", "Menu 4"];
	
	// those words will be removed
	$noiseWords = ["19,50", "17,50", "!\""];
	
	$fh = fopen($file,'r');
	$menuFound = false;
	$menuText = '';
	
	while ($line = fgets($fh)) {
		if($menuFound) {
			$trimmedLine = trim(removeInsensitiveFromString($line, $noiseWords));
			
			if(startsInsensitiveWithArray($trimmedLine, $stopWords)) {
				break;
			}
			if(!$removeEmptyLines || ($removeEmptyLines && !empty($trimmedLine))) {
				$menuText = $menuText . $trimmedLine . '<br />';
			}
		} else {
			if (strpos(strtoupper($line),strtoupper($textPattern)) !== false) {
				$menuFound = true;
			}
		}
	}
	fclose($fh);
	
	if(!$menuFound) {
		return '(menu pas trouvé)';
	}
	
	return rtrim($menuText, '<br />');
}

function findRegExpFromTextFile($file, $regexp) {
	preg_match_all($regexp, file_get_contents($file), $keys, PREG_PATTERN_ORDER);
	return array_unique($keys);
}

abstract class TextFileReturnStatus {
    const NO_TXT_FILE = 1;
    const TXT_NOT_UPTODATE = 2;
    const MENU_NOT_FOUND = 3;
    const MENU_OK = 4;
}

class TextFileMenu {
    public $status;
	public $text;
	
	public function __construct() {
        $this->status = TextFileReturnStatus::NO_TXT_FILE;
		$this->text = null;  		
    }
}


?>