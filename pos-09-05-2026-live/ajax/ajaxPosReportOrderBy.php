<?php include_once("../../config/auto_loader.php");
?>
<?php 

if($_REQUEST['id_order_by']!=''){ 
echo $sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE 1=1 and status='1' and type='2' AND id='".$_REQUEST['id_order_by']."'";
           
            $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);

            $rowSubMenu = mysqli_fetch_object($resSubMenu);
			
				$lisarray=explode(',', $rowSubMenu->report_display_order);
				
				
			
	$contact  =	'<select  class="form-control select2" name="id_order_by" data-parsley-required id="id_order_by" style="width:100%" >
					 <option  value="">---Select Order By---</option>';
					 $i=1;
		foreach($lisarray  as $Data){	
			
			if($i=='1'){
				$selected = 'selected="selected"';
			}else {
				$selected = '';
			}
			$contact .= '<option value="'.$i++.'" '.$selected.'> '.$Data.'</option>';
				
			
		}				 
		$contact .=	'</select>';
	
echo $contact;




  }




?>



	