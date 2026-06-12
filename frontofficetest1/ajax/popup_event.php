
<?php
include_once("../../config/auto_loader.php");

	
	
 $id=$_POST['id'];  

$sql="SELECT * FROM ".FO_RESERVATIONS." where id= $id ";

$res = mysqli_query($connNew,$sql);
	
	while($row = mysqli_fetch_object($res)){
		
		
$Firstname	   =	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$row->id_mst_guest."'");
$Lastname		=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$row->id_mst_guest."'");

$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$row->id_mst_guest."'");				
$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				

$guestName=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));	

$company_name	=	selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".$row->id_mst_company."'");
	
		
		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		 $NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
	
	if($NightAuditDated==date('Y-m-d', strtotime($row->checkin))){
		
		$pendingCheckin	=	'1';
		}else{
			$pendingCheckin	=	'0';
			}
	
		$data['id_value'] = $row->id;
		$data['booking_no'] = $row->booking_no;
		$data['guest_name'] = $guestName;
		$data['source'] = $company_name;
		$data['enc_id'] = encryptor(encrypt, $row->id);
		$data['pendingCheckin'] = $pendingCheckin;
		
	}	
echo json_encode($data);




/* 

$date=date('Y-m-d');
$sql="SELECT fo_reservations.guest_name,fo_reservations.parentId,fo_reservations.check_in,fo_reservations.check_out FROM fo_reservations LEFT JOIN fs_inventory ON fo_reservations.id_mst_hotels = fs_inventory.id_mst_hotels"; 

//'title' => ('AVl'.$rownew->crs_available) . "\n" . ('CON'.$rownew->confirmed). "\n" . ('CON'.$rownew->tentative)  . "\n" . ('CON'.$rownew->waitlisted)  ,




<!-- start expander toggle script -->

<script type="text/javascript" src="<?php echo $SITE_URL; ?>/hexpander/movingjs.js"></script>

<table align=center height="100px" border=0 width="500px" cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<a style="font-family:verdana;font-size:12px;">
			<img src="<?php echo $SITE_URL; ?>/hexpander/insert.jpg" id="insert1" align="absmiddle" onClick="toggleSlide('div1',this.id);">Hioxindia</a>
			
			<div id="div1" style="display:none; overflow: hidden; height: 75px;margin:10px;"> 
			    <div style="font-family:verdana;font-size:12px;">
				   HIOX INDIA is currently involved in web services, software/application development, web content development,  
				   web hosting, domain registration, internet solutions and web design.
				</div>
			</div>
			
		</td>
	</tr>
</table>

<!-- end expander toggle script -->






		
			
*/		

?>		