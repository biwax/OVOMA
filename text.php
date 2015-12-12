<?php



/**
 * Extract a text from a text file which start with a text pattern
 */
function extractFromTextFile($file, $textPattern) {
	$fh = fopen($file,'r');
	$menuFound = false;
	$menuText = '';
	
	while ($line = fgets($fh)) {
		if($menuFound) {
			if($line == PHP_EOL) {
				break;
			}
			$menuText = $menuText . $line;
		} else {
			if (strpos(strtoupper($line),strtoupper($textPattern)) !== false) {
				$menuFound = true;
			}
		}
	}
	fclose($fh);
	
	return $menuText;
}

function extractFromTextFile2($file, $textPattern) {
	$fh = fopen($file,'r');
	$menuFound = false;
	$menuText = '';
	
	while ($line = fgets($fh)) {
		if($menuFound) {
			$menuText = $line;
			break;
		} else {
			if (strpos(strtoupper($line),strtoupper($textPattern)) !== false) {
				$menuFound = true;
			}
		}
	}
	fclose($fh);
	
	return $menuText;
}

$day = 'mardi 24';

echo '<br />';

echo utf8_encode(extractFromTextFile('pdf_texts\pinocchio.txt', $day));

echo '<br />';

echo utf8_encode(extractFromTextFile('pdf_texts\pinocchio.txt', 'Le Hit de la semaine'));

echo '<br />';

echo utf8_encode(extractFromTextFile('pdf_texts\pinocchio.txt', 'Menu 3'));

echo '<br />';

echo utf8_encode(extractFromTextFile('pdf_texts\pinocchio.txt', 'Menu 4'));

$day = 'Mardi 1';

echo '<br /><h1>Boc</h1>';

echo utf8_encode(extractFromTextFile2('pdf_texts\boc.txt', $day));


?>