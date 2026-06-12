<?php
$host = "49.249.121.6";
$port    = 9100; //default listening port for printer
$message = "This text is going to print by printer";
// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, 0);
if(!$socket){
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    socket_close($socket);
   die("Could not create socket: [$errorcode] $errormsg\n");
}
// connect to server
$result = socket_connect($socket, $host, $port);  
if(!$result){
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
    socket_close($socket);
    die("Could not connect to server: [$errorcode] $errormsg\n");
}
// send string to server
$socket_wrt = socket_write($socket, $message, strlen($message));
if(!$socket_wrt){
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    socket_close($socket);
    die("Could not send data to server: [$errorcode] $errormsg\n");
}
// get server response
$result = socket_read($socket, 1024);
if(!$result){
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    socket_close($socket);
    die("Could not read server response: [$errorcode] $errormsg\n");
}
echo "Reply From Server:".$result;
// close socket
socket_close($socket);
?>

<?php die;
$port = "8081";
$host = "49.249.121.6";
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if ($socket === false) {
    echo "socket_create() failed, reason: " . socket_strerror(socket_last_error    ()) . "\n";
} else {
    echo "OK.\n";
}
$result = socket_connect($socket, $host, $port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror    (socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}
socket_write($socket, $finalvar);
socket_close($socket);

die;
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, '49.249.121.16');
$connection = socket_connect($sock, 'app.roomstatushub.in',80);
if( $connection ){
    echo 'ONLINE';
} else {
    echo 'OFFLINE: ' . socket_strerror(socket_last_error( $socket ));
}
die;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, '192.168.1.100');
$connection =  @socket_connect($socket, 'XX.XX.XX.XX', 7550);

if( $connection ){
    echo 'ONLINE';
} else {
    echo 'OFFLINE: ' . socket_strerror(socket_last_error( $socket ));
}die;

$outputString = "Hello World!";
echo $Ip = "ssl://49.249.121.6";
echo $port = "8081";
  $fp = fsockopen($Ip, $port, $errno, $errstr, 120);
if (!$fp) {
  echo json_encode("$errstr ($errno)<br />\n");
} else {
  try {
    $kk=fwrite($fp, $outputString, strlen($outputString));
    fclose($fp);
  } catch (Exception $e) {
    echo json_encode('Caught exception: ', $e->getMessage(), "\n");
  } 
}
die;
if(!function_exists("allow_url_open")) {
 echo "Function Exists";
 }
 else{
 echo "function not exists";}
$rc=@fsockopen( "ssl://www.google.com", 80, $errno, $errstr, 60 );
echo "errno: $errno, $errstr, rc=$rc";
die;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$connection =  @socket_connect($socket, 'ssl://49.249.121.6', 8081);

if( $connection ){
    echo 'ONLINE';
} else {
    echo 'OFFLINE: ' . socket_strerror(socket_last_error( $socket ));
}


die;
//fsockopen('49.249.121.6', '8081');
$outputString = "Hello World!";
echo $Ip = "ssl://49.249.121.6";
echo $port = "8081";
  $fp = fsockopen($Ip, $port, $errno, $errstr, 120);
if (!$fp) {
  echo json_encode("$errstr ($errno)<br />\n");
} else {
  try {
    $kk=fwrite($fp, $outputString, strlen($outputString));
    fclose($fp);
  } catch (Exception $e) {
    echo json_encode('Caught exception: ', $e->getMessage(), "\n");
  } 
}
?>