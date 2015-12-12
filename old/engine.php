<?php

/*
* Performs data and time validity pre-checks
* Return string if problem, null otherwise
*/
function preCheck($url) {
	global $weekEndText, $websiteNotAvailable;
	
	if(isWeekend()) {
		return $weekEndText;
	}
	
	if(!URLExists($url)) {
		return $websiteNotAvailable;
	}
	
	return null;
}

/*
* Return a DOMDocument given an URL
*/
function getDOMDocumentFromURL($url) {
	$html = getHTMLFromURL($url);
	
	$doc = new DOMDocument;
	
	if(empty($html)) {
		return $doc;
	}
	
	$doc->validateOnParse = true;
	$doc->loadHTML($html);
	
	return $doc;
}

/*
* San Marco
* URL = http://www.piazza-san-marco.ch/
* Pattern = <div id="menu_jour">
* Remove = <h1>*</h1>; <a>*</a>
*/
function getSanMarco($baseMenuURL) {	
	$preCheck = preCheck($baseMenuURL);
	
	if(!is_null($preCheck)) {
		return $preCheck;
	}
	
	$doc = getDOMDocumentFromURL($baseMenuURL);
	
	// parsing
	$node = $doc->getElementById('menu_jour');
	
	if(is_null($node)) {
		return getFallbackURL($baseMenuURL);
	}
	
	// Remove first h1
	$allH1 = $node->getElementsByTagName('h1');
	if($allH1->length > 0) {
		$divFirst = $allH1->item(0);
		$divFirst->parentNode->removeChild($divFirst);
	}
	
	$text = $node->ownerDocument->saveHTML($node);
	$text = str_replace('<h2>', '<br /><h6>', $text);
	$text = str_replace('</h2>', '</h6>', $text);
	$text = preg_replace('/<br>/', '', $text, 1);
	$text = str_replace('<br><br>', '', $text);
	$text = str_replace('<br>', '<br />', $text);
	$text = str_replace('16.00', '<br /><h6>Menu 2</h6>', $text);
	$text = str_replace('17.00', '', $text);
	$text = str_replace('21.50', '', $text);
	$text = '<h6>Menu 1</h6>' . strip_tags($text, '<br><br /><h6>');	

	return $text;
}

/*
* Sport's Café
* URL = http://sports-cafe.ch/
* Pattern = <div class="vertical left">$dayNameInFrench</div>
*/
function getSportsCafe() {
	global $weekEndText, $websiteNotAvailable, $menuFormat, $SportsCafeBaseMenuURL;
	
	if(isWeekend()) {
		return $weekEndText;
	}
	
	$fallback = sprintf($menuFormat, $SportsCafeBaseMenuURL);
	
	$html = getHTMLFromURL($SportsCafeBaseMenuURL);
	
	if(empty($html)) {
		return $websiteNotAvailable;
	}
	
	$currentDayFrench = getCurrentDayName();
	
	$doc = new DOMDocument;
	$doc->loadHTML($html);
	$finder = new DomXPath($doc);
    $spaner = $finder->query("//*[contains(@class, 'day')]");
	foreach ($spaner as $tag) {
		if (strpos($tag->nodeValue,$currentDayFrench) !== false) {
			$day = $tag;
			break;
		}
	}
	
	if(isset($day)) {
		// remove first div
		$allDiv = $day->getElementsByTagName('div');
		if($allDiv->length > 0) {
			$divFirst = $allDiv->item(0);
			$divFirst->parentNode->removeChild($divFirst);
		}
		
		//remove last div
		$count = $day->getElementsByTagName('div')->length;
		if($count > 0) {
			$divLast = $day->getElementsByTagName('div')->item($count-1);
			$divLast->parentNode->removeChild($divLast);
		}
		
		$text = $day->ownerDocument->saveHTML($day);
		$text = str_replace('<br>', '<br />', $text);
		$text = preg_replace('/<\\/div>/', '<br /><br /><h6>Menu 2</h6>', $text, 1);
		
		$text = '<h6>Menu 1</h6>' . strip_tags($text, '<br><br /><h6>');
			
		return $text;
	}

	return $fallback;
}

/*
* Le Pirate
* URL = http://www.aulac.ch/menus-fr1375.html
* Pattern = second <a> with class 'download'
*/
function getLePirate() {
	global $weekEndText, $websiteNotAvailable, $menuFormat, $LePirateBaseMenuURL;
	
	if(isWeekend()) {
		return $weekEndText;
	}
	
	$fallback = sprintf($menuFormat, $LePirateBaseMenuURL);
	
	$html = getHTMLFromURL($LePirateBaseMenuURL);
	
	if(empty($html)) {
		return $fallback;
	}
	
	$doc = new DOMDocument;
	$doc->loadHTML($html);
	$finder = new DomXPath($doc);
    $spaner = $finder->query("//*[contains(@class, 'download')]");
	
	// get second <a>
	if($spaner->length > 1) {
		$pdfLink = $spaner->item(1);
	
		return sprintf($menuFormat, getBaseURL($LePirateBaseMenuURL) . $pdfLink->getAttribute('href'));
	}
	
	return $fallback;
}

/*
* Le Pinocchio
* URL = http://www.le-pinocchio.ch/
* Pattern = first <a> with href containing "Menu%20de%20la%20semaine"
*/
function getLePinocchio() {
	global $weekEndText, $websiteNotAvailable, $menuFormat, $LePinocchioBaseMenuURL;
	
	if(isWeekend()) {
		return $weekEndText;
	}
	
	$fallback = sprintf($menuFormat, $LePinocchioBaseMenuURL);
	
	$html = getHTMLFromURL($LePinocchioBaseMenuURL);
	
	if(empty($html)) {
		return $LePinocchioBaseMenuURL;
	}
	
	$doc = new DOMDocument;
	$doc->loadHTML($html);
	$finder = new DomXPath($doc);
	
    $spaner = $finder->query("//a[contains(@href,'Menu%20de%20la%20semaine')]");
	
	if($spaner->length > 0) {
		// get first <a>
		$pdfLink = $spaner->item(0);
	
		return sprintf($menuFormat, $LePinocchioBaseMenuURL . $pdfLink->getAttribute('href'));
	}
	
	return $fallback;
}

/*
* Le Boccalino
* URL = http://www.boccalino.ch/
* Pattern = first <a> with title having "Menu de la semaine"
*/
function getLeBoccalinoPDFurl() {
	global $weekEndText, $websiteNotAvailable, $menuFormat, $LeBoccalinoBaseMenuURL;
	
	$html = getHTMLFromURL($LeBoccalinoBaseMenuURL);
	
	if(empty($html)) {
		return null;
	}
	
	$doc = new DOMDocument;
	$doc->loadHTML($html);
	$finder = new DomXPath($doc);
	
    $spaner = $finder->query("//*[contains(@title, 'Menu de la semaine')]");
	
	// get first <a>
	if($spaner->length > 0) {
		$pdfLink = $spaner->item(0);
	
		return $pdfLink->getAttribute('href');
	}
	
	return null;
}

/*
* Le Boccalino
* URL = http://www.boccalino.ch/
* Pattern = first <a> with title having "Menu de la semaine"
*/
function getLeBoccalino() {
	global $weekEndText, $websiteNotAvailable, $menuFormat, $LeBoccalinoBaseMenuURL;
	
	if(isWeekend()) {
		return $weekEndText;
	}
	
	$fallback = sprintf($menuFormat, $LeBoccalinoBaseMenuURL);
	
	$url = getLeBoccalinoPDFurl();
	
	$html = getHTMLFromURL($LeBoccalinoBaseMenuURL);
	
	if(empty($html)) {
		return $LeBoccalinoBaseMenuURL;
	}
	
	$doc = new DOMDocument;
	$doc->loadHTML($html);
	$finder = new DomXPath($doc);
	
    $spaner = $finder->query("//*[contains(@title, 'Menu de la semaine')]");
	
	// get first <a>
	if($spaner->length > 0) {
		$pdfLink = $spaner->item(0);
	
		return sprintf($menuFormat, $pdfLink->getAttribute('href'));
	}
	
	return $fallback;
}

/*
* Le Milan
* URL = http://www.le-milan.ch/menu.pdf
* Pattern = hardcoded
*/
function getLeMilan() {
	global $weekEndText, $websiteNotAvailable, $menuFormat, $LeMilanBaseMenuURL;
	
	if(isWeekend()) {
		return $weekEndText;
	}
	return sprintf($menuFormat, $LeMilanBaseMenuURL);
}

/*
* Café de l'Europe
* URL = http://www.cafedeleurope.ch/cartes/menu.pdf
* Pattern = hardcoded
*/
function getCafeEurope() {
	global $weekEndText, $websiteNotAvailable, $menuFormat, $CafeEuropeBaseMenuURL;
	
	if(isWeekend()) {
		return $weekEndText;
	}
	
	return sprintf($menuFormat, $CafeEuropeBaseMenuURL);
}

/*
* Le White Horse
* URL = http://www.elblanco.ch/crbst_4.html
* Pattern = first <a> with class 'wa-but-txt '
*/
function getWhiteHorse() {
	global $weekEndText, $websiteNotAvailable, $menuFormat, $WhiteHorseBaseMenuURL;
	
	if(isWeekend()) {
		return $weekEndText;
	}
	
	$fallback = sprintf($menuFormat, $WhiteHorseBaseMenuURL);
	
	$html = getHTMLFromURL($WhiteHorseBaseMenuURL . 'crbst_4.html');
	
	if(empty($html)) {
		return $fallback;
	}
	
	$doc = new DOMDocument;
	$doc->loadHTML($html);
	$finder = new DomXPath($doc);
    $spaner = $finder->query("//*[contains(@class, 'wa-but-txt ')]");
	
	// get first <a>
	if($spaner->length > 0) {
		$pdfLink = $spaner->item(0);
	
		return sprintf($menuFormat, getBaseURL($WhiteHorseBaseMenuURL) . $pdfLink->getAttribute('href'));
	}

	return $fallback;
}

?>