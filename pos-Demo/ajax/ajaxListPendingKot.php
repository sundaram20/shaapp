<style>
  .online-status {
    width: 15px;
    height: 15px;
    margin : auto;
   
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    margin-top : 3px!important;
  }

  /* .online-status-off{
    background: #ff0000cf;
  } */


  /* switch buttons */

  .switch {
  position: relative;
  display: inline-block;
  width: 100%;   /* Adjusted width */
  height: 20px;  /* Adjusted height */
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 14px; /* Adjusted height */
  width: 14px;  /* Adjusted width */
  left: 1px;    /* Adjusted left position */
  bottom: 3px;  /* Adjusted bottom position */
  background-color: white;
  transition: 0.4s;
    border-radius: 50%;
    left: 2px;

}

input:checked + .slider:before {
  /*transform: translateX(1px);*/ /* Adjusted translation */
}

/* Rounded sliders */
.slider.round {
  border-radius: 12px; /* Adjusted border radius */
}

.slider.round:before {
  border-radius: 50%;
}




@media screen and (max-width: 992px) {
#listPendingKot .tabbox .table td{
  font-size : 1.2rem;
  padding : 1rem 0.4rem!important;
}
#listPendingKot th{
  font-size : 1.2rem!important;
}

.KotNoTd{
  width : 15%!important;
}


.slider.round {
  border-radius: 100px; /* Adjusted border radius */
}

.slider:before {
  position: absolute;
  content: "";
  height: 22.5px; /* Adjusted height */
  width: 22.5px;  /* Adjusted width */
  left: 1px;    /* Adjusted left position */
  bottom: 3px;  /* Adjusted bottom position */
  background-color: white;
  transition: 0.4s;
    border-radius: 50%;
    left: 3.5px;

}

.switch {
  position: relative;
  display: inline-block;
  width: 55.5px;   /* Adjusted width */
  height: 30px;  /* Adjusted height */
}


.online-status {
    width: 20px;
    height: 20px;
    margin: auto;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    margin-top: 3px!important;
}

.tab-container .tbsteward h6, .tab-container .tbsteward h4 {
    margin: 5px;
    font-size: 17px;
}

}
</style>


<?php include_once("../../config/auto_loader.php");




if($_REQUEST['id_attribute_table'] != ''){

  $statuscase .= " AND id_attribute_table='".$_REQUEST['id_attribute_table']."'" ;

}
$statuscase .= " AND kot_status='Pending'" ;

$SQL="SELECT *  from
( select pp.*, ppp.id_mst_items ,ppp.item_description,ppp.qty,ppp.id as id_pos_purch_details,ppp.cook_status,ppp.serve_status,ppp.verified,
	   (case  when COALESCE(pp.cancelled)=1 then 'cancelled'
	   		  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
	         when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed' end) as kot_status
 
 from pos_purch pp right join pos_purch_details ppp on ppp.id_pos_purch=pp.id 
 where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=1 AND pp.doc_type=22 
 $searchDocumentType 
  ORDER BY pp.`last_modified` desc
 
 )as managekotlist WHERE id!=0 ".$statuscase." 
";

//echo $SQL;


$SqlKotList = mysqli_query($connNew, $SQL); 
$numRows=	mysqli_num_rows($SqlKotList);
 $i=1;
 $listPrintArray=array();
	$listprintHeaderArray=array();
 $pendingKotArray=array();
while($row = mysqli_fetch_object($SqlKotList)){ 
					  
					$table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$row->id_attribute_table."'"); 
					$shift_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'shift' AND id= '".$row->id_attribute_shift."'"); 
					$steward_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'steward' AND id= '".$row->id_attribute_steward."'"); 
					
					$row->mdoc_no;
					
	$pendingKotArray[$row->id_attribute_table]['table_name']=$table_name;
	$pendingKotArray[$row->id_attribute_table]['steward_name']=$steward_name;	
			
	

	$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['id_pos_purch_details']=$row->id_pos_purch_details;
	$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['item_description']=$row->item_description;
	$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['qty']=$row->qty;
	$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['KotNo']=$row->mdoc_no;
	$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['id_attribute_table']=$row->id_attribute_table;
	$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['cookStatus']=$row->cook_status;
	$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['serveStatus']=$row->serve_status;
	$listPrintArray[$row->id_attribute_table][$row->id_pos_purch_details]['verified']=$row->verified;
	
	
					
}//debugData($pendingKotArray);
//debugData($listPrintArray);?>

<?php
foreach($pendingKotArray as $Table=>$TableDetails){
	     
//debugData($TableDetails);
?>
<div class="col-md-3">
  <div class="tab-container">
    <div class="tabbox">
      <div class="tabheading panel-heading">
        <div class=" d-flex justify-content-space-between">
          <div class="tbsteward">
            <h6>Steward:<?php echo $TableDetails['steward_name']; ?></h6>
          </div>
          <div class="tbname">
            <h5>Table :<?php echo $TableDetails['table_name']; ?></h5>
          </div>
          <div class="btn pkbtn " title="Bill Now">
          
          
           
           <form name="FormPosKot_<?php echo $Table;?>" id="FormPosKot_<?php echo $Table;?>" action="kotbilling.php?submenu=177" method="post">
            <input type="hidden" value="1" name="FormSubmitPosKot" />
            <input type="hidden" value="<?php echo $_REQUEST['submenu'];?>" name="submenu1" id="submenu1">
            <input type="hidden" name="tableid" id="tableid" value="<?php echo $Table;?>"/>
            <div id="tableandoutlet<?php echo $Table;?>"></div>
             <div id="tableOutlet2<?php echo $Table;?>"></div>
           
           
            <i class="fa-solid  fa-file-invoice" onClick="GoToBill(<?php echo $Table;?>);"></i> 
           </form>
           </div>
        </div>
      </div>
      <div class="tabcontent">
        <table class="table table-responsive table table-striped table-bordered dataTable no-footer songs-table" >
          <thead>
            <tr>
              <th> Items Name</th>
              <th> Ready</th>
              <th> Verified</th>
              <th style="width:10%;"> Qty </th>
              <th style="width:10%;font-size:10px;padding:5px;">KOT No</th>
            </tr>
          </thead>
          <tbody>
       <?php   foreach($listPrintArray as $Dataset=>$TableData){
		   if($Table== $Dataset){
			 foreach($TableData as $value){
			 
		if($value['cookStatus']=='1'){
				$cssColorClass	=	'style="background: #4CAF50;"';
			}else{
				$cssColorClass	=	'style="background:#b2b0b0"';
				}
				
				
			if($value['verified']=='1'){
				$verifiedStatus	=	'checked';
			}else{
				$verifiedStatus	=	'';
				}
		?>
          <tr>
          <td> <?php echo $value['item_description'] ;?> </td>
          <td style="width:10%; margin : auto;">  <div class="online-status online-status-off " <?php echo $cssColorClass; ?>></div> </td>
          <td style="width:10%;"><label class="switch">
  <input type="checkbox" <?php  echo $verifiedStatus;?> onclick="updateVerifiedStatus(<?php echo $value['id_pos_purch_details']; ?>);" >
  <span class="slider round"></span>
</label></td>
              <td style="width:10%;"> <?php echo $value['qty'] ;?> </td>
              <td style="width:10%;"><?php echo $value['KotNo'] ;?></td>
          </tr>
		  <?php }} } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php }  ?>
