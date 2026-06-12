<?php 
include_once("../../config/auto_loader.php");
/*print_r($_REQUEST);
exit;*/


$gridLaytout='';

$finalGrid ='';

$i=0;
if($_REQUEST['id_room']!='' && $_REQUEST['id_hotel']!='' ){

  
  while($i < count($_REQUEST['id_room'])){
  	 
    $dateArr=explode(' to ',$_REQUEST['effective_date']);
    $from = date('d-m-Y',strtotime($dateArr[0]));
    $to = date('d-m-Y',strtotime($dateArr[1]));
      		
  	$singleGrid='';
  	/*$doubleGrid='';
  	$extraBedGrid='';
  	$extraChildGrid='';
  	$minStayGrid='';
  	$maxStayGrid='';*/
  	$dateGrid='';
    $stopSellGrid='';

    if($i%2==0){
      $colorBox = 'box-warning';
    }
    else{
      $colorBox = 'box-danger';
    }

  	 $sqlGrid="SELECT * FROM ".TBL_BE_INVENTORY." WHERE id_mst_hotels='".$_REQUEST['id_hotel']."' AND id_mst_room_types='".$_REQUEST['id_room'][$i]."'  AND allocation_date BETWEEN '".date('Y-m-d',strtotime($from))."' AND '".date('Y-m-d',strtotime($to))."' AND id_shop='".$_SESSION['shop']."' ORDER BY allocation_date ASC ";
    
    $max_date=selectColumn(TBL_BE_INVENTORY,'MAX(allocation_date)'," WHERE id_mst_hotels='".$_REQUEST['id_hotel']."' AND id_mst_room_types='".$_REQUEST['id_room'][$i]."'");
    
    $resGrid = mysqli_query($connNew,$sqlGrid);
    if(mysqli_num_rows($resGrid)>0){

    	while($rowGrid = mysqli_fetch_object($resGrid)){
        

        $roomName=selectColumn(TBL_ROOM_TYPE,'name','WHERE id='.$rowGrid->id_mst_room_types.' ');


        $from = $rowGrid->allocation_date;

    		$dateGrid.='<th>'.date("l",strtotime($from))."<br>".date("d-M-Y",strtotime($from)).'</th>';

        $singleGrid.='<td "><i onclick="fillLeft(\''.date('Y-m-d',strtotime($dateArr[0])).'\',\''.$from.'\',\'room_'.$rowGrid->id_mst_room_types.'\');" class="arrows fa fa-angle-double-left"></i><input class="inputGrid" name="room_'.$rowGrid->id_mst_room_types."_".date('Y-m-d',strtotime($from)).'" value="'.$rowGrid->crs_available.'"  type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($dateArr[1])).'\',\'room_'.$rowGrid->id_mst_room_types.'\');" class="arrows fa fa-angle-double-right"></i></td>';

        $stopSellGrid.='<td "><i onclick="fillLeft(\''.date('Y-m-d',strtotime($dateArr[0])).'\',\''.$from.'\',\'status_'.$rowGrid->id_mst_room_types.'\');" class="arrows fa fa-angle-double-left"></i><input class="inputGrid" name="status_'.$rowGrid->id_mst_room_types."_".date('Y-m-d',strtotime($from)).'" value="'.$rowGrid->status.'"  type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($dateArr[1])).'\',\'status_'.$rowGrid->id_mst_room_types.'\');" class="arrows fa fa-angle-double-right"></i></td>'; 

    		/*$doubleGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($dateArr[0])).'\',\''.$from.'\',\'double_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-left"></i><input name="double_'.$_REQUEST['id_link'][$i]."_".date('Y-m-d',strtotime($from)).'" value="'.$rowGrid->double_pax_price.'" class="inputGrid" type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($dateArr[1])).'\',\'double_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-right"></i></td>';

    		$extraBedGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($dateArr[0])).'\',\''.$from.'\',\'extraBed_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-left"></i><input name="extraBed_'.$_REQUEST['id_link'][$i]."_".date('Y-m-d',strtotime($from)).'"  value="'.$rowGrid->extra_bed_price.'" class="inputGrid"  type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($dateArr[1])).'\',\'extraBed_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-right"></i></td>';

    		$extraChildGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($dateArr[0])).'\',\''.$from.'\',\'extraChild_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-left"></i><input name="extraChild_'.$_REQUEST['id_link'][$i]."_".date('Y-m-d',strtotime($from)).'" value="'.$rowGrid->extra_child_price.'" class="inputGrid" type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($dateArr[1])).'\',\'extraChild_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-right"></i></td>';*/

    		//$minStayGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($dateArr[0])).'\',\''.$from.'\',\'min_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-left"></i><input name="min_'.$_REQUEST['id_link'][$i]."_".date('Y-m-d',strtotime($from)).'" value="'.$rowGrid->min_stay.'" class="inputGrid" type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($dateArr[1])).'\',\'min_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-right"></i></td>';

    		//$maxStayGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($dateArr[0])).'\',\''.$from.'\',\'max_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-left"></i><input name="max_'.$_REQUEST['id_link'][$i]."_".date('Y-m-d',strtotime($from)).'" value="'.$rowGrid->max_stay.'" class="inputGrid" type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($dateArr[1])).'\',\'max_'.$_REQUEST['id_link'][$i].'\');" class="arrows fa fa-angle-double-right"></i></td>';

           

          
    	}

      

      $gridLaytout.='<div class="row">
            <div class="col-xs-12">        
              <div class="box '.$colorBox.' ">
                <div class="box-header">
                  <h4 class="box-title"><b>'.$roomName.' (Added till '.date('d-M-Y',strtotime($max_date)).')</b></h4>
                </div>
                <form id="form'.$_REQUEST['id_room'][$i].'" name="'.$_REQUEST['id_room'][$i].'">
          
          <div class="outer">
      <div class="inner">
        <table >
            <tr>
              <th class="hard_left">Date</th>
              '.$dateGrid.'
              
            </tr>
            <tr id="singleGrid">
              <th class="hard_left">Rooms</th>
              '.$singleGrid.'
            </tr>
            <!--<tr id="doubleGrid">
               <th class="hard_left">Double</th>
               '.$doubleGrid.'
            </tr>
            <tr id="extraBedGrid">
              <th class="hard_left">Extra Bed</th>
              '.$extraBedGrid.'
            </tr>
            <tr id="extraChildGrid">
              <th class="hard_left">Extra Child</th>
              '.$extraChildGrid.'
            </tr>
            <tr id="minStayGrid">
              <th class="hard_left">Min Stay</th>
              '.$minStayGrid.'
            </tr>
            <tr id="maxStayGrid">
              <th class="hard_left">Max Stay</th>
              '.$maxStayGrid.'
            </tr>-->

              <tr id="stopSellGrid">
                <th class="hard_left">Stop Sell<br>(stop:0;start:1)</th>
                '.$stopSellGrid.'
              </tr>
      </table>
      </div>
    </div></form></div></div></div>';
  	}

    $i++;     
  }
  if($gridLaytout=='')
    echo $gridLaytout='<div><h3 style="color:red;">Inventory Not Found, Please Add Inventory!</h3><div>';
  else
    echo $gridLaytout;
}
else{
  echo $gridLaytout;

}

unset($_REQUEST);
?> 
