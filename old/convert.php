<?php

require_once 'unirest/Unirest.php';

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

// download all pdfs to a location
// (use common code from the aggregator)

// convert each PDF to an image

// These code snippets use an open-source library.
/*$response = Unirest\Request::post("https://pdf2jpg-pdf2jpg.p.mashape.com/convert_pdf_to_jpg.php",
  array(
    "X-Mashape-Key" => "4vQThd6WoJmshrauqDPfPu1tlWvvp1g826MjsnxIB4i4LNPhaf"
  ),
  array(
    "pdf" => Unirest\file::add(getcwd() . "/boc.pdf"),
    "resolution" => 150
  )
);*/

$response = Unirest\Request::post("http://mockbin.org/bin/800a818b-5fb6-40d4-a342-75a1fb8599db?foo=bar&foo=baz",
  array(
    "X-Mashape-Key" => "4vQThd6WoJmshrauqDPfPu1tlWvvp1g826MjsnxIB4i4LNPhaf"
  ),
  array(
    "pdf" => Unirest\Request::get("http://www.le-pinocchio.ch/view/data/3070/Menu%20de%20la%20semaine%202015.pdf"),
    "resolution" => 150
  )
);



if($response->code == 200) {
	echo $response->body[0]->color;
} else {
	echo 'error while converting';
}
  
  
  ?>