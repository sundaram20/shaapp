<?php
set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");//die;
$result = socket_bind($socket, 'ls-235f49fc2901dbe9e4e44f452f1c69fb1a321fad.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com', '3306') or die("Could not bind to socket\n");
$result = socket_listen($socket, 3) or die("Could not set up socket listener\n");

while(true) {
    $spawn = socket_accept($socket) or die("Could not accept incoming connection\n");
    while(true) {
        $input = socket_read($spawn, 2048) or die("Could not read input\n");

        echo base64_encode($input)."\r\n";

        $output="OK";
        socket_write($spawn, $output, strlen ($output)) or die("Could not write output\n");
    }
}
//socket_close($spawn);
//socket_close($socket);

?>