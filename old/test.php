<?php

$whitelist = array(
    '127.0.0.1',
    '::1'
);

if(is_writable("generated_image_menu")) {
		echo 'writable!';
} else {
	echo "not writable";
}

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    exec("convert pirate.pdf -resize 120 pirate.png", $output, $returnVar);
} else {
	exec('"C:\Program Files\ImageMagick-6.9.2-Q16\convert" pirate.pdf -density 300 -resize 2000 generated_image_menu\pirate.png', $output, $returnVar);
}



print_r($output);

echo $returnVar;

?>