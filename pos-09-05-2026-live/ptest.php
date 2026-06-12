	<?php 
	echo $tmpdir = 'C:\Users\shash\AppData\Local\Temp';//sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
echo '==='.$file =  tempnam($tmpdir, 'kot');    # nama file temporary yang akan dicetak
$handle = fopen($file, 'w');
$condensed = Chr(27) . Chr(33) . Chr(4);
$bold1 = Chr(27) . Chr(69);
$bold0 = Chr(27) . Chr(70);
$initialized = chr(27).chr(64);
$condensed1 = chr(15);
$condensed0 = chr(18);
$Data  = $initialized;
$Data .= $condensed1;
$Data .= "==========================\n";
$Data .= "|    HEADING    |\n";
$Data .= "==========================\n";

$Data .= "Test KOT Print\n";
$Data .= "Test KOT Print\n";
$Data .= "Test KOT Print\n";
$Data .= "Test KOT Print\n";

echo $Data .= "--------------------------\n";
echo fwrite($handle, $Data);
fclose($handle);
copy($file, "//localhost/HP LaserJet Professional P1102");  # Lakukan cetak
//unlink($file);

?>


