<?php 
include_once("../../config/auto_loader.php");
/*print_r($_REQUEST);
exit;*/
$options='';
$finalGrid='';
if($_REQUEST['id_hotel'] !='' && $_REQUEST['eId']==""){
  $sql= "SELECT * FROM ".TBL_PACKAGE_LINKING." WHERE id_hotel=".$_REQUEST['id_hotel']." AND status=1 ";
  
  $res = mysqli_query($connNew,$sql);
  $options='<div class="col-md-12" ><table style="background-color:#E3E3E3;text-align:left;"  class="table">
            <tr style="background-color:#3C8DBC;color:white;"><th colspan=2>Room-Plan</th><th>Discount Type</th><th>Discount</th></tr>';
   
   $fillArray=array();
   $start=0;
   $end=mysqli_num_rows($res);
   
   if($end==0){
    echo $options='<div class="col-md-12" ><table style="background-color:#E3E3E3;text-align:left;"  class="table">
            <tr style="background-color:#3C8DBC;color:white;"><th colspan=2>Links Not created</th></tr>
            </table></div>
            ';
    exit;
   }


  while($row=mysqli_fetch_object($res)){
    $start++;
    array_push($fillArray,'dis_'.$row->id);

    
    $roomName = selectColumn(TBL_ROOM_TYPE,'name','WHERE id="'.$row->id_room.'" ');
    $planName = selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$row->id_plan.'" ');

    $options.='<tr><td colspan=2>'.strtoupper($roomName).'-'.strtoupper($planName).'<input type="hidden" name="id_link[]" value="'.$row->id.'"></td>
              <td><select name="dis_type_'.$row->id.'" class="form-control"><option value="1" default>Percent</option><option value="2">Flat</option></select></td>
              <td><div class="input-group"><input id="disVal_'.$start.'" name="dis_'.$row->id.'" type="number" min=0 class="form-control" placeholder=""  required>
            <a href="javascript:void(0);" onclick="fillUp('.$start.',0);" class="text-green input-group-addon"><i class="fa fa-chevron-up"></i></a><a href="javascript:void(0);" onclick="fillDown('.$start.','.$end.');" class="text-red input-group-addon"><i class="fa fa-chevron-down"></i></a></div></td><tr>

              ';

   
  }
  echo $options.='</table></div>';
}
else if($_REQUEST['eId']!=''){

  $linkSql="SELECT id AS id_link  FROM ".TBL_ROOM_PLAN_LINKS." WHERE  id IN (".$_REQUEST['id_link'].") ORDER BY display_order ";
  
  $linkRes = mysqli_query($connNew,$linkSql);


  while($rowLink = mysqli_fetch_object($linkRes)){
      
      $mastersql="SELECT valid_from,valid_till,offer_type FROM ".TBL_OFFER_MASTER." WHERE id=".$_REQUEST['id_offer_master']." ";
      $resMaster=mysqli_query($connNew,$mastersql);
      $rowMaster=mysqli_fetch_object($resMaster);
      
      $elseDate = $from = date('d-m-Y',strtotime($rowMaster->valid_from));
      $to = date('d-m-Y',strtotime($rowMaster->valid_till));
           
      $singleGrid='';
      $doubleGrid='';
      $extraBedGrid='';
      $extraChildGrid='';
      $minStayGrid='';
      $maxStayGrid='';
      $dateGrid='';
      $stopSellGrid='';

      if($i%2==0){
        $colorBox = 'box-warning';
      }
      else{
        $colorBox = 'box-danger';
      }

      $sqlGrid="SELECT * FROM ".TBL_OFFER_DETAILS." WHERE id_offer='".$_REQUEST['eId']."' AND id_room_plan_link='".$rowLink->id_link."'  AND effective_date >= '".date('Y-m-d',strtotime($from))."' AND effective_date <= '".date('Y-m-d',strtotime($to))."' AND id_shop='".$_SESSION['shop']."' ORDER BY effective_date ASC ";

      $resGrid = mysqli_query($connNew,$sqlGrid);
      
      if(mysqli_num_rows($resGrid)>0){

        while($rowGrid = mysqli_fetch_object($resGrid)){
           $getOfferType=$rowGrid->discount_type;
          

          $id_room=selectColumn(TBL_PACKAGE_LINKING,'id_room','WHERE id='.$rowLink->id_link.' ');

          $id_plan=selectColumn(TBL_PACKAGE_LINKING,'id_plan','WHERE id='.$rowLink->id_link.' ');

          $roomName=selectColumn(TBL_ROOM_TYPE,'name','WHERE id='.$id_room.' ');

          $planName=selectColumn(TBL_RATE_PLAN,'name','WHERE id='.$id_plan.' ');

          $from = $rowGrid->effective_date;

          $dateGrid.='<th>'.date("l",strtotime($from))."<br>".date("d-M-Y",strtotime($from)).'</th>';

          
          $doubleGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($rowMaster->valid_from)).'\',\''.$from.'\',\'dis_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-left"></i><input name="dis_'.$rowLink->id_link."_".date('Y-m-d',strtotime($from)).'" value="'.$rowGrid->discount_amount.'" class="inputGrid" type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($rowMaster->valid_till)).'\',\'dis_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-right"></i></td>';

          $offerTypeGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($rowMaster->valid_from)).'\',\''.$from.'\',\'offerType_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-left"></i><input name="offerType_'.$rowLink->id_link."_".date('Y-m-d',strtotime($from)).'" value="'.$getOfferType.'" class="inputGrid" type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($rowMaster->valid_till)).'\',\'offerType_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-right"></i></td>';
  

         
          $stopSellGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($rowMaster->valid_from)).'\',\''.$from.'\',\'status_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-left"></i><input name="status_'.$rowLink->id_link."_".date('Y-m-d',strtotime($from)).'" value="'.$rowGrid->status.'" class="inputGrid" type="text" /><i onclick="fillRight(\''.$from.'\',\''.date('Y-m-d',strtotime($rowMaster->valid_till)).'\',\'status_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-right"></i></td>'; 
        }

        
        

        $gridLaytout.='<div class="row">
              
              <div class="col-xs-12">        
                <div class="box '.$colorBox.' ">
                  <div class="box-header">
                    <h4 class="box-title"><b>'.$roomName."-".$planName.' Plan</b></h4>
                  </div>

                  <form id="form'.$rowLink->id_link.'" name="'.$rowLink->id_link.'">
              
            <div class="outer">
        <div class="inner">
          <table >
              <tr>
                <th class="hard_left">Date</th>
                '.$dateGrid.'
                
              </tr>
              <tr  id="singleGrid">
                <th class="hard_left">Type (1:Per,2:Flat)</th>
                '.$offerTypeGrid.'
              </tr>
              <tr id="doubleGrid">
                 <th class="hard_left">Discount ('.($rowLink->discount_type==1?'Percent':'Percent').')</th>
                 '.$doubleGrid.'
              </tr>
              
              <!--<tr id="minStayGrid">
                <th class="hard_left">Min Stay</th>
                '.$minStayGrid.'
              </tr>-->';
              /*if($rowLink->offer_type==3){
              $gridLaytout.=  '<tr id="maxStayGrid">
                <th class="hard_left">Advance Days</th>
                '.$maxStayGrid.'
              </tr>';
              }*/

            $gridLaytout.=  '<tr id="stopSellGrid">
                <th class="hard_left">Stop Sell (0:stop,1:start)</th>
                '.$stopSellGrid.'
              </tr>
        </table>
        </div>
      </div></form></div></div></div>';
      
      }
      else{
        $elseDate=strtotime($elseDate);
        while($elseDate<=strtotime($to)){


          $id_room=selectColumn(TBL_PACKAGE_LINKING,'id_room','WHERE id='.$rowLink->id_link.' ');

          $id_plan=selectColumn(TBL_PACKAGE_LINKING,'id_plan','WHERE id='.$rowLink->id_link.' ');

          $roomName=selectColumn(TBL_ROOM_TYPE,'name','WHERE id='.$id_room.' ');

          $planName=selectColumn(TBL_RATE_PLAN,'name','WHERE id='.$id_plan.' ');

          $dateGrid.='<th>'.date("l",$elseDate)."<br>".date("d-M-Y",$elseDate).'</th>';          
          $doubleGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($rowMaster->valid_from)).'\',\''.date('Y-m-d',$elseDate).'\',\'dis_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-left"></i><input name="dis_'.$rowLink->id_link."_".date('Y-m-d',$elseDate).'" value="0" class="inputGrid" type="text" /><i onclick="fillRight(\''.date('Y-m-d',$elseDate).'\',\''.date('Y-m-d',strtotime($rowMaster->valid_till)).'\',\'dis_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-right"></i></td>';
            
            $offerTypeGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($rowMaster->valid_from)).'\',\''.date('Y-m-d',$elseDate).'\',\'offerType_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-left"></i><input name="offerType_'.$rowLink->id_link."_".date('Y-m-d',$elseDate).'" value="0" class="inputGrid" type="text" /><i onclick="fillRight(\''.date('Y-m-d',$elseDate).'\',\''.date('Y-m-d',strtotime($rowMaster->valid_till)).'\',\'offerType_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-right"></i></td>';

                   
            $stopSellGrid.='<td><i onclick="fillLeft(\''.date('Y-m-d',strtotime($rowMaster->valid_from)).'\',\''.date('Y-m-d',$elseDate).'\',\'status_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-left"></i><input name="status_'.$rowLink->id_link."_".date('Y-m-d',$elseDate).'" value="1" class="inputGrid" type="text" /><i onclick="fillRight(\''.date('Y-m-d',$elseDate).'\',\''.date('Y-m-d',strtotime($rowMaster->valid_till)).'\',\'status_'.$rowLink->id_link.'\');" class="arrows fa fa-angle-double-right"></i></td>'; 

          $elseDate=strtotime('+1 days',$elseDate);
        }

        $id_room=selectColumn(TBL_PACKAGE_LINKING,'id_room','WHERE id='.$rowLink->id_link.' ');

        $id_plan=selectColumn(TBL_PACKAGE_LINKING,'id_plan','WHERE id='.$rowLink->id_link.' ');

        $roomName=selectColumn(TBL_ROOM_TYPE,'name','WHERE id='.$id_room.' ');

        $planName=selectColumn(TBL_RATE_PLAN,'name','WHERE id='.$id_plan.' ');
        $gridLaytout.='<div class="row">
              
              <div class="col-xs-12">        
                <div class="box '.$colorBox.' ">
                  <div class="box-header">
                    <h4 class="box-title"><b>'.$roomName."-".$planName.' Plan</b></h4>
                  </div>

                  <form id="form'.$rowLink->id_link.'" name="'.$rowLink->id_link.'">
              
            <div class="outer">
        <div class="inner">
          <table >
              <tr>
                <th class="hard_left">Date</th>
                '.$dateGrid.'
                
              </tr>
              <tr  id="singleGrid">
                <th class="hard_left">Discount Type (1:Per,2:Flat)</th>
                '.$offerTypeGrid.'
              </tr>
              <tr id="doubleGrid">
                 <th class="hard_left">Discount ('.($rowLink->discount_type==1?'Percent':'Flat').')</th>
                 '.$doubleGrid.'
              </tr>
              
              <!--<tr id="minStayGrid">
                <th class="hard_left">Min Stay</th>
                '.$minStayGrid.'
              </tr>-->';
              /*if($rowLink->offer_type==3){
              $gridLaytout.=  '<tr id="maxStayGrid">
                <th class="hard_left">Advance Days</th>
                '.$maxStayGrid.'
              </tr>';
              }*/

            $gridLaytout.=  '<tr id="stopSellGrid">
                <th class="hard_left">Stop Sell (0:stop,1:start)</th>
                '.$stopSellGrid.'
              </tr>
        </table>
        </div>
      </div></form></div></div></div>';
      
      }

      $idLinks .= $rowLink->id_link.',';
      $i++;     
    }

    echo $gridLaytout.'<input type="hidden" id="id_link_hidden"  value="'.$idLinks.'" />'; 
    
  
}
else{
  echo $options;
}
?>