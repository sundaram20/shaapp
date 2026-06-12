<?php
include_once("../config/auto_loader.php"); 
$demoData = array(
        array("id"=>encryptor(encrypt,'12'),'type'=>'Deluxe Room','room_no' => '101','status' => '1','res_id'=>'wh201','guest' => 'Shubhi','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'2','type'=>'Deluxe Room','room_no' => '102','status' => '1','res_id'=>'wh202','guest' => 'Hitesh Aloney','folio' => 'FO-89','checkin'=>'13-Mar-2020','checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'3','type'=>'Deluxe Room','room_no' => '103','status' => '1','res_id'=>'wh203','guest' => 'Sumit Kumar','folio' => 'FO-99','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

         array("id"=>encryptor(encrypt,'21'),'type'=>'Deluxe Room','room_no' => '104','status' => '1','res_id'=>'wh204','guest' => 'Sundaram','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'5','type'=>'Deluxe Room','room_no' => '105','status' => '2','res_id'=>'','guest' => '','folio' => '','checkin'=>'','checkout'=>'','action'=>'0'),

        array("id"=>encryptor(encrypt,'12'),'type'=>'Suit Room','room_no' => '101','status' => '1','res_id'=>'wh201','guest' => 'Shubhi','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'2','type'=>'Suit Room','room_no' => '102','status' => '1','res_id'=>'wh202','guest' => 'Hitesh Aloney','folio' => 'FO-89','checkin'=>'13-Mar-2020','checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'3','type'=>'Suit Room','room_no' => '103','status' => '1','res_id'=>'wh203','guest' => 'Sumit Kumar','folio' => 'FO-99','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array("id"=>encryptor(encrypt,'21'),'type'=>'Suit Room','room_no' => '104','status' => '1','res_id'=>'wh204','guest' => 'Sundaram','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'5','type'=>'Suit Room','room_no' => '105','status' => '2','res_id'=>'','guest' => '','folio' => '','checkin'=>'','checkout'=>'','action'=>'0'),
);

echo json_encode($demoData);