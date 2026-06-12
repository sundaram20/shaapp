<?php 


include_once("../../config/auto_loader.php");



//debugData($_REQUEST);
//debugData($_SESSION);
?>

<?php

/*
session_start();

$host = "localhost";    /* Host name */
//$user = "root";         /* User */
/*$password = "";         /* Password */
//$dbname = "app";   /* Database name */

// Create connection
//$con = mysqli_connect($host, $user, $password,$dbname);

// Check connection
/*if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

else{
    echo "connected";
}
*/

if(isset($_POST['search'])){
    $search = mysqli_real_escape_string($connNew,$_POST['search']);

    $query = "SELECT * FROM pos_guest WHERE name like'%".$search."%'";
    $result = mysqli_query($connNew,$query);
    
    while($row = mysqli_fetch_array($result) ){
        $response[] = array("value"=>$row['mobile'],"label"=>$row['name']);
    }

    echo json_encode($response);
}

exit;


?>