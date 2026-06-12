<?php
include_once("../config/auto_loader.php"); 

$demoData = array(
    array("id"=>encryptor(encrypt,'12'),"reservation_id"=>"wh201","guest"=>"Shubhi","source"=>"cox and kings","roomType"=> array("Delux Room","Suit Room"),"persons"=>"4 | 2","booked"=>"4","pending"=>"2"),
    array("id"=>"2","reservation_id"=>"wh202","guest"=>"Hitesh","source"=>"sita travel","roomType"=>array("Delux Room"),"persons"=>"4 | 2","booked"=>"4","pending"=>"2"),
    array("id"=>"3","reservation_id"=>"wh203","guest"=>"Sumit","source"=>"cox and kings","roomType"=>array("Delux Room","Super Delux Room"),"persons"=>"4 | 2","booked"=>"4","pending"=>"2"),
    array("id"=>encryptor(encrypt,'21'),"reservation_id"=>"wh204","guest"=>"Sundaram","source"=>"cox and kings","roomType"=>array("Delux Room","Suit Room"),"persons"=>"4 | 2","booked"=>"4","pending"=>"2"),
    array("id"=>"5","reservation_id"=>"wh205","guest"=>"Shafeer","source"=>"cox and kings","roomType"=> array("Delux Room","Suit Room"),"persons"=>"4 | 2","booked"=>"4","pending"=>"2"),
    array("id"=>"6","reservation_id"=>"wh206","guest"=>"Vipin","source"=>"cox and kings","roomType"=> array("Delux Room","Suit Room"),"persons"=>"4 | 2","booked"=>"4","pending"=>"2"),
);

echo json_encode($demoData);

?>