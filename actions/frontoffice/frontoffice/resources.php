<?php
$dataArr = array(
    array('id'=> 'a', 'title'=> 'Deluxe Room', 'eventColor'=>'yellow'),
    array('id'=> 'b', 'title'=> 'Maharni Suit', 'eventColor'=> 'red','backgroundColor'=>'green'),
    array('id'=> 'd', 'title'=> 'Maharaj Suit'),
    array('id' =>'d1','parentId'=>'d', 'title'=>'Room 101'),
    array('id'=> 'con', 'parentId'=> 'a', 'title'=>'Confirmed (20)'),
    array('id'=> 'ten','eventColor'=> 'green', 'parentId'=> 'a', 'title'=>'Tentative (10)'),
    array('id'=> 'a3', 'parentId'=> 'a', 'title'=>'Waitlisted'),
    array('id'=> 'a4', 'parentId'=> 'a', 'title'=>'Net Booked'),
    array('id'=> 'e',  'title'=>'Occupany %')
);

echo json_encode($dataArr);
?>