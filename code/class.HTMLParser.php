<?php

/**
 * HTML Parser which extract the content of a menu in an HTML page
 */
abstract class HTMLParser extends BaseParser {
	
	abstract protected function parseHTML($doc);
	
	public function getMenuURL() {
		// For HTML-parsed menu, we simply give the HTML page where the menu is (base Menu URL)
		return $this->getBaseMenuURL();
	}
	
	protected function getMenuText() {
		$doc = parent::getDOMDocument();
		return $this->parseHTML($doc);
	}

}

?>