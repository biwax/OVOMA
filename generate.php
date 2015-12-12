<?php

include('code/utils.php');
include('code/class.BaseParser.php');
include('code/class.HTMLParser.php');
include('code/class.PDFParser.php');
include('code/class.Restaurants.php');

$whitelist = array(
    '127.0.0.1',
    '::1'
);

// We can only execute this page locally
if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
	echo 'Cannot execute script<br />';
	exit();
}

function downloadPDF($url, $name) {
	if(!is_null($url)) {
		file_put_contents('pdf_menus/' . $name . '.pdf', fopen($url, 'r'));
		echo $name . ' PDF downloaded<br />';
		return true;
	} else {
		echo 'No ' . $name . ' PDF URL!<br />';
		return false;
	}
}

function convertPDFToText($name) {
	if(file_exists('pdf_menus/' . $name . '.pdf')) {
		exec('C:\xpdf\bin64\pdftotext.exe C:\xampp\htdocs\ovoma\pdf_menus\\' . $name . '.pdf C:\xampp\htdocs\ovoma\pdf_texts\\' . $name . '.txt', $output, $returnVar);
		if($returnVar == 0) {
			echo $name . ' text file generated<br />';
			return true;
		} else {
			echo 'Error while generating ' . $name . ' text file<br />';
			return false;
		}
	}
}

function processPDF($parser) {	
	$url = $parser->getMenuURL();
	$name = $parser->getSimpleName();
	
	if(!is_null($url)) {
		if(downloadPDF($url, $name)) {
			convertPDFToText($name);
		}
	}
}

processPDF(new LePinocchioParser);
processPDF(new LeBoccalinoParser);
processPDF(new LePirateParser);
processPDF(new LeMilanParser);
processPDF(new CafeEuropeParser);
processPDF(new WhiteHorseParser);

?>