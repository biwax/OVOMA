<?php

exec("pdftotext pirate.pdf", $output, $returnVar);

print_r($output);

echo $returnVar;

?>