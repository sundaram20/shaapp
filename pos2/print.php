<?php

/*$php_content='Test Print';

$handle = printer_open("\\\\192.168.2.4\\EPSON L365 Series");

printer_write($handle, $php_content);

print($handle);
die;*/
echo $ouput = 'Test print...';

 //$getprt = printer_list( PRINTER_ENUM_LOCAL | PRINTER_ENUM_SHARED );

print_r($getprt);

echo '11111111';
die;



echo '11111111';
$_POST['order']=1;
if(isset($_POST['order'])){
$print_output= $_POST['order'];
}
try
{
    $fp=pfsockopen("192.168.1.100", '9100');
    fputs($fp, $print_output);
    fclose($fp);

    echo 'Successfully Printed';
}
catch (Exception $e) 
{
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

//namespace Mike42\Escpos\PrintConnectors;

/**
 * Wrap multiple connectors up, to print to several printers at the same time.
 */
/*class MultiplePrintConnector implements PrintConnector {
    private $connectors;

    public function __construct(PrintConnector ...$connectors)
    {
        $this -> connectors = $connectors;
    }

    public function finalize()
    {
        foreach($this -> connectors as $connector) {
            $connector -> finalize();
        }
    }

    public function read($len)
    {
        // Cannot write
        return false;
    }

    public function write($data)
    {
        foreach($this -> connectors as $connector) {
            $connector -> write($data);
        }
    }

    public function __destruct()
    {
        // Do nothing
    }
}

require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\MultiplePrintConnector;
use Mike42\Escpos\Printer;

$kitchenPrinter = new NetworkPrintConnector("10.1.2.3", 9100);
$barPrinter = new NetworkPrintConnector("192.168.2.3", 9100);

$connector = new MultiplePrintConnector($kitchenPrinter, $barPrinter);
$printer = new Printer($connector);
$printer -> text("Hello World\n");
$printer -> cut();
$printer -> close();*/