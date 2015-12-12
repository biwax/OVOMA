<?php

// disable libxml warnings because we are going to parse invalid HTML
libxml_use_internal_errors(true);

/**
 * Base Class which reprensent a Parser which can parse the content of a menu, based on an URL
 */
abstract class BaseParser {
	
	/**
	 * Get a short name of the parser
	 */
	abstract public function getSimpleName();
	
	/**
	 * Get the URL where the content of the menu is
	 * For HTML-parsed menu, this is the HTML page where the menu is
	 * For PDF-parsed menu, this is either the HTML page where the link to the PDF is (dynamic) or directly the linkt to the PDF (fixed)
	 */
	abstract public function getBaseMenuURL();
	
	/**
	 * Get the URL where the menu is
	 * For HTML-parsed menu, this is the same as the BaseMenuURL (so a HTML page)
	 * For PDF-parsed menu, this is the link to the PDF
	 */
	abstract public function getMenuURL();
	
	/**
	 * Get the content of the menu as an HTML string
	 */
    abstract protected function getMenuText();
	
	/**
	 * Check if the menu is valid (not outdated)
	 */
	abstract protected function isMenuValid();
	
   /**
    * Get the website of the restaurant
	*/
	public function getMainURL() {
		return getBaseURL($this->getBaseMenuURL());
	}   
	
	/**
	 * Get what to display (content of the menu or error/warning/info message)
	 */
	public function getContent() {
		$preCheck = $this->preChecks($this->getBaseMenuURL());
	
		if(!is_null($preCheck)) {
			return $preCheck;
		}
		
		return $this->getMenuText();
	}

	/**
	 * Performs data and time validity pre-checks
	 * Return string if problem, null otherwise
	 */
    private function preChecks() {
		global $weekEndText, $websiteNotAvailable;
		
		if(isWeekend()) {
			return $weekEndText;
		}
		
		if(!(is_a($this, 'TextFileParser') || is_a($this, 'TextFileFixedPDFParser'))) {
			if(!URLExists($this->getBaseMenuURL())) {
				return $websiteNotAvailable;
			}
		}
		
		return null;
   }
   
	/**
	 * Is the menu weekly or daily?
	 */
   	protected function isDayMenu() {
		// By default, all menu are weekly menu
		return false;
	}
   
   /**
	* Return the DOMDocument of the base menu URL
	*/
	protected function getDOMDocument() {
		$html = getHTMLFromURL($this->getBaseMenuURL());
		
		$doc = new DOMDocument;
		
		if(empty($html)) {
			return $doc;
		}
		
		$doc->validateOnParse = true;
		$doc->loadHTML($html);
		
		return $doc;
	}
}

?>