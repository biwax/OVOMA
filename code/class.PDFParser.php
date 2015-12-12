<?php

/**
 * PDF parser which extract a menu from an URL page
 * The PDF URL is dynamic (parsed from an HTML page)
 */
abstract class PDFParser extends BaseParser {
	
	/**
	 * Get the raw PDF URL
	 */
	abstract protected function getPDFURL();
	
    /**
	 * Get the PDF URL (the validity of the URL is checked)
	 */
	public function getMenuURL() {
		$PDFURL = $this->getPDFURL();
		
		$UrlValid = false;
		
		if(!is_null($PDFURL) && URLExists($PDFURL)) {
			return $PDFURL;
		}
		
		return null;
	}
	
    /**
	 * Get an HTML-formatted link to the PDF of the menu
	 */
	protected function getMenuFormattedLink() {
		$menuURL = $this->getMenuURL();
		
		if(!is_null($menuURL)) {
			return getFormattedURL($this->getMenuURL(), $this->isDayMenu());
		} else {
			return getFallbackURL($this->getBaseMenuURL());
		}
	}
	
	protected function getMenuText() {
		// By default, we always return the formatted link
		// It can be overriden to provide the content of the PDF file
		return $this->getMenuFormattedLink();
	}
}

/**
 * PDF parser with fixed PDF URL
 */
abstract class FixedPDFParser extends PDFParser {
	
	protected function getPDFURL() {
		// for fixed PDF, we always return the base menu URL
		return $this->getBaseMenuURL();
	}
	
	protected function isMenuValid() {
		return true;
	}
}

/**
 * PDF parser which extract a menu from a text file
 */
abstract class TextFileParser extends PDFParser {
	
	abstract protected function getMenuFromTextFile();
	
	protected function getMenuText() {
		
		$textFileMenu = $this->getMenuFromTextFile();
		
		switch($textFileMenu->status) {
			case TextFileReturnStatus::MENU_OK:
				return $textFileMenu->text;
				break;
			case TextFileReturnStatus::TXT_NOT_UPTODATE:
				return 'Menu pas à jour sur le site du restaurant<br />Essaie ici: ' . $this->getMenuFormattedLink();
				break;
			default:
				return $this->getMenuFormattedLink();
				break;
		}
	}
}

/**
 * PDF parser with fixed PDF URL which extract a menu from a text file
 */
abstract class TextFileFixedPDFParser extends TextFileParser {
	
	protected function getPDFURL() {
		// for fixed PDF, we always return the base menu URL
		return $this->getBaseMenuURL();
	}
}

?>