<?php
require __DIR__ . '/../autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

error_reporting(E_ALL);

/* Fill in your own connector here */
//$connector = null;
$connector = new WindowsPrintConnector("BILL");


$date = "Monday 6th of April 2015 02:56:25 PM";

/* Start the printer */
////$logo = EscposImage::load("resources/escpos-php.png", false);
$printer = new Printer($connector);




/* Name of shop */
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text("ExampleMart Ltd.\n");
$printer -> selectPrintMode();
$printer -> text("Shop No. 42.\n");
$printer -> feed();

$printer -> cut();
$printer -> pulse();

$printer -> close();

/* A wrapper to do organise item names & prices into columns */




?>


<?php 

echo '111';
die;?>



