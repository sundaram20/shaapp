<?php
include_once("../../config/auto_loader.php");

$html='';

if($_REQUEST['id_offer']!=''){
	
	$sql="SELECT * FROM ".TBL_OFFER_MASTER." WHERE id='".$_REQUEST['id_offer']."' ";

	$res = mysqli_query($connNew,$sql);
	$row = mysqli_fetch_object($res);
	$textInc=array();
	if($row->ids_additional_inclusion!=''){
		$incArr = explode(',',$row->ids_additional_inclusion);
		$i=0;
		while($i<count($incArr)){
			$incName=selectColumn(TBL_RATE_INCLUSION,'name','WHERE id="'.$incArr[$i].'" ');
			array_push($textInc,$incName);
		$i++;
		}
	}

	$html='<div class="col-md-12" ><table style="background-color:#E3E3E3;text-align:left;"  class="table">
            <tr style="background-color:#3C8DBC;color:white;"><th colspan=6>Offer Description</th></tr>
            <tr style="background-color:#1C8DBC;color:white;">
            	<th >Offer Name</th>
            	<th width="300px;">Remarks</th>
            	<th width="300px;">Validity</th>
            	<th >Min Stay</th>
            	<th >Advance Days</th>
            	<th style="width:300px;">Additional Inclusions</th>
            </tr>
            <tr >
            	<th >'.$row->offer_name.'</th>
            	<th width="300px;">'.$row->remarks.'</th>
            	<th width="300px;">'.date("d-M-Y",strtotime($row->valid_from)).' to '.date("d-M-Y",strtotime($row->valid_till)).'</th>
            	<th >'.$row->min_stay.'</th>
            	<th >'.$row->advance_days.'</th>
            	<th style="width:300px;">'.implode(",",$textInc).'</th>
            </tr>

            </table>';
}
else{
	$html='';
}

echo $html;
