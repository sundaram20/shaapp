<?php
	
	include_once( dirname( __FILE__ ) . '/../class/Database.class.php' );
	//$pdo = Database::getInstance()->getPdoObject();
	
	$name = $_POST[ 'name' ];
	$message = $_POST[ 'message' ];
	//echo 'INSERT INTO messages VALUES( name,message )';
 $addSql = "   	INSERT INTO `messages` SET
			               `name` = '".addslashes($name)."',
						    `message` = '".addslashes($message)."'";
	$query =mysqli_query($conn, $addSql);
	//$query->execute( array( 'name' => $name, 'message' => $message ) );
	$k=array();
	$k[]= array( 'name' => 'jas', 'message' =>'sdasdas' );
echo json_encode($k);
?>