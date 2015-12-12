<?php

class SportsCafeParser extends HTMLParser {
	
	public function getSimpleName() {
		return 'sports_cafe';
	}
	
	public function getBaseMenuURL() {
		return 'http://sports-cafe.ch/';
	}
	
	protected function isMenuValid() {
		return true;
	}	
	
	/**
	 * pattern: <div class="vertical left">$dayNameInFrench</div>
	 */
	protected function parseHTML($doc) {		
		$currentDayFrench = getCurrentDayName();
		
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

		return getFallbackURL($this->getBaseMenuURL());	
	}
}

class SanMarcoParser extends HTMLParser {
	
	public function getSimpleName() {
		return 'san_marco';
	}
	
	public function getBaseMenuURL() {
		return 'http://www.piazza-san-marco.ch/';
	}
	
	protected function isMenuValid() {
		return true;
	}
	
	/**
	 * pattern: <div id="menu_jour">
	 */
	protected function parseHTML($doc) {
		$node = $doc->getElementById('menu_jour');
		
		if(is_null($node)) {
			return getFallbackURL($this->getBaseMenuURL());
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
}

class LePinocchioParser extends TextFileParser {
	
	public function getSimpleName() {
		return 'pinocchio';
	}	
	
	public function getBaseMenuURL() {
		return 'http://www.le-pinocchio.ch';
	}
	
	/**
	 * pattern: first <a> with href containing "Menu%20de%20la%20semaine"
	 */
	protected function getPDFURL() {
		$doc = parent::getDOMDocument();
		$finder = new DomXPath($doc);
	
		$spaner = $finder->query("//a[contains(@href,'Menu%20de%20la%20semaine')]");
		
		if($spaner->length > 0) {
			// get first <a>
			$pdfLink = $spaner->item(0);
		
			return $this->getBaseMenuURL() . $pdfLink->getAttribute('href');
		}
		
		return null;
	}
	
	protected function isMenuValid() {
		// Check that the date in the format [day name] [day number] [month name]. Example: Mercredi 2 décembre
		// Exception: Mardi 1er décembre
		
		$dayNumber = getCurrentDayNumber();
		if($dayNumber == '1') {
			$dayNumber = '1er';
		}
		
		$currentDate = getCurrentDayName() . ' ' . $dayNumber . ' ' . getCurrentMonthName();
		
		$menu = file_get_contents('pdf_texts/' . $this->getSimpleName() . '.txt');
		
		return (stripos($menu, $currentDate) !== false);
	}
	
	protected function getMenuFromTextFile() {
		$textFileMenu = new TextFileMenu();
		
		if(!$this->isMenuValid()) {
			$textFileMenu->status = TextFileReturnStatus::TXT_NOT_UPTODATE;
			return $textFileMenu;
		}
		
		$text = '<h6>Menu 1</h6>';
		$text = $text . utf8_encode(extractFromTextFile('pdf_texts/' . $this->getSimpleName() . '.txt', getCurrentDayNameNumber()));

		$text = $text . '<br /><br /><h6>Le Hit de la semaine</h6>';
		$text = $text . utf8_encode(extractFromTextFile('pdf_texts/' . $this->getSimpleName() . '.txt', 'Le Hit de la semaine'));

		$text = $text . '<br /><br /><h6>Menu 3</h6>';
		$text = $text . utf8_encode(extractFromTextFile('pdf_texts/' . $this->getSimpleName() . '.txt', 'Menu 3'));

		$text = $text . '<br /><br /><h6>Menu 4</h6>';
		$text = $text . utf8_encode(extractFromTextFile('pdf_texts/' . $this->getSimpleName() . '.txt', 'Menu 4'));
		
		$textFileMenu->status = TextFileReturnStatus::MENU_OK;
		$textFileMenu->text = $text;
		
		return $textFileMenu;
	}
}

class LeBoccalinoParser extends TextFileParser {
	
	public function getSimpleName() {
		return 'boccalino';
	}		
	
	public function getBaseMenuURL() {
		return 'http://www.boccalino.ch/fr';
	}
	
	/**
	 * pattern: first link which contains a "Menu%20du%20jour.pdf" in the href
	 */
	protected function getPDFURL() {
		$doc = parent::getDOMDocument();
		$finder = new DomXPath($doc);
		
		$spaner = $finder->query('//a[contains(@href,"Menu%20du%20jour")]');
		
		// get first <a>
		if($spaner->length > 0) {
			$pdfLink = $spaner->item(0);
		
			return $pdfLink->getAttribute('href');
		}
		
		return null;
	}
	
	protected function isMenuValid() {
		return true;
	}

	protected function getMenuFromTextFile() {
		$textFileMenu = new TextFileMenu();
		
		$text = '<h6>Menu</h6>';
		$text = $text . utf8_encode(extractFromTextFile('pdf_texts/' . $this->getSimpleName() . '.txt', getCurrentDayNameNumber()));
		
		$textFileMenu->status = TextFileReturnStatus::MENU_OK;
		$textFileMenu->text = $text;
		
		return $textFileMenu;
	}
}

class LePirateParser extends TextFileParser {
	
	public function getSimpleName() {
		return 'pirate';
	}	
	
	public function getBaseMenuURL() {
		return 'http://www.aulac.ch/menus-fr1375.html';
	}
	
	/**
	 * pattern: second <a> with class 'download'
	 */ 
	protected function getPDFURL() {
		$doc = parent::getDOMDocument();
		$finder = new DomXPath($doc);
		
		$spaner = $finder->query("//*[contains(@class, 'download')]");
		
		// get second <a>
		if($spaner->length > 1) {
			$pdfLink = $spaner->item(1);
		
			return getBaseURL($this->getBaseMenuURL()) . $pdfLink->getAttribute('href');
		}
		
		return null;
	}
	
	protected function isMenuValid() {
		return true;
	}	
	
	protected function getMenuFromTextFile() {
		$textFileMenu = new TextFileMenu();
		
		$menu = utf8_encode(extractFromTextFile('pdf_texts/' . $this->getSimpleName() . '.txt', getCurrentDayNameNumber(), false));
		$menu = str_ireplace('<br /><br />', 'BRTAGPLACEHOLDER<h6>Menu poisson</h6>', $menu);
		$menu = str_ireplace('BRTAGPLACEHOLDER', '<br /><br />', $menu);
		
		$text = '<h6>Menu viande</h6>';
		$text = $text . $menu;
		$text = str_ireplace('<h6>Menu viande</h6><br />', '<h6>Menu viande</h6>', $text);
		
		$textFileMenu->status = TextFileReturnStatus::MENU_OK;
		$textFileMenu->text = $text;
		
		return $textFileMenu;
	}
}

class LeMilanParser extends FixedPDFParser {
	
	public function getSimpleName() {
		return 'milan';
	}	
	
	public function getBaseMenuURL() {
		return 'http://www.le-milan.ch/menu.pdf';
	}
	
	protected function isDayMenu() {
		return true;
	}
}

class CafeEuropeParser extends TextFileFixedPDFParser {
	
	public function getSimpleName() {
		return 'europe';
	}	
	
	public function getBaseMenuURL() {
		return 'http://www.cafedeleurope.ch/cartes/menu.pdf';
	}
	
	protected function isMenuValid() {
		// We have the following pattern to search for the menu validity:
		// Du [day_number] au [day_number] [month_name]
		$keys = findRegExpFromTextFile('pdf_texts/' . $this->getSimpleName() . '.txt', "/Du\\s+\\d*\\sau\\s+\\d*\\s+.+/");
		
		$rangeCheck = true;
		
		if($keys) {
			$first = current(array_filter($keys));
			if($first) {
				$line = current(array_filter($first));
			}
		}
		
		if($line) {
			// [0] = Du, [1] = startDay number, [2] = au, [3] enDay number, [4] = month name
			$parts = preg_split('/\s+/', trim($line));
			
			if(count($parts) == 5) {
				$startDayNumber = intval($parts[1]);
				$endDayNumber = intval($parts[3]);
				$monthNumber = convertMonthNameToNumber(strtolower($parts[4]));
				
				$startDate = $startDayNumber . '-' . $monthNumber . '-' . getCurrentYear() . ' 00:01';
				$endDate = $endDayNumber . '-' . $monthNumber . '-' . getCurrentYear() . ' 23:59';
				
				$currentDate = getCurrentDate();
				
				$rangeCheck = false;
				$rangeCheck = checkInRange($startDate, $endDate, intval($currentDate->format('U')));
			}
		}
		
		return $rangeCheck;
	}
	
	protected function getMenuFromTextFile() {
		$textFileMenu = new TextFileMenu();
		
		if(!$this->isMenuValid()) {
			$textFileMenu->status = TextFileReturnStatus::TXT_NOT_UPTODATE;
			return $textFileMenu;
		}
		
		$menu = utf8_encode(extractFromTextFile('pdf_texts/' . $this->getSimpleName() . '.txt', getCurrentDayName()));
		$menu = str_ireplace('<br />', '', $menu);
		$menu = str_ireplace('MENU N°1', '<h6>Menu 1</h6>', $menu);
		$menu = str_ireplace('MENU N°2', '<br /><br /><h6>Menu 2</h6>', $menu);
		
		$textFileMenu->status = TextFileReturnStatus::MENU_OK;
		$textFileMenu->text = $menu;
		
		return $textFileMenu;
	}
}

class WhiteHorseParser extends PDFParser {
	
	public function getSimpleName() {
		return 'white_horse';
	}
	
	public function getBaseMenuURL() {
		return 'http://www.elblanco.ch/crbst_4.html';
	}
	
	protected function isMenuValid() {
		return true;
	}
	
	/**
	 * pattern: first <a> with class 'wa-but-txt '
	 */
	protected function getPDFURL() {
		$doc = parent::getDOMDocument();
		$finder = new DomXPath($doc);
		
		$spaner = $finder->query("//*[contains(@class, 'wa-but-txt ')]");
		
		// get first <a>
		if($spaner->length > 0) {
			$pdfLink = $spaner->item(0);
		
			return getBaseURL($this->getBaseMenuURL()) . $pdfLink->getAttribute('href');
		}

		return null;
	}
}

?>