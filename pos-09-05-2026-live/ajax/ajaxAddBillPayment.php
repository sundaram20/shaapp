<?php 
include_once("../../config/auto_loader.php");
?>

<?php
$Message=array();
 	//echo '<pre>';print_r($_REQUEST);echo '</pre>';die;
	$doc_date  =$_REQUEST['po_date1'];
   	$purch_id	=	$_REQUEST['get_purch_id'];
	//if($_REQUEST['pay_total_amount_'.$purch_id]>0){
		//echo "DELETE from `".TBL_PURCH_PAY."` where id_purch='".$purch_id."' ";
		//die;
		
		
	if($_REQUEST['savetype']==0){ //Unsettled
	
	$doc_date  =  date('d-m-Y', strtotime(selectColumn('pos_purch','doc_date'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$purch_id."'")));
	$TodayDate	= date('d-m-Y');
	$Yesterday	=date('d-m-Y',strtotime('-1 day',strtotime($TodayDate)));   //date('d-m-Y',strtotime($TodayDate , '-days'));
	
	
				if($_SESSION['userLevel']=='1'){
					
							executeSql("DELETE from `".TBL_PURCH_PAY."` where id_purch='".$purch_id."' ");
							
							//	audit_trail-================================					
	$ch5 ="POS BILL Unsettled " .   date('d-m-Y H:i:s');
	$ch6 ="  <br/><b>REMARKS</b> - " .   $_REQUEST['UnsettleRemarks'];				
	$auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($purch_id)."',
	`tables_name` = '".TBL_PURCH_PAY."',
	`form_code` = 'POS',
	`changes` =  '".addslashes($ch5).addslashes($ch6)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";
	 mysqli_query($connNew,$auditeditSql);
	 //	audit_trail-================================
					
	//Update Payment =====================================
		$total_amount_recevied	=  selectColumn(TBL_PURCH_PAY,'sum(amount)'," WHERE id_purch='".$purch_id."'");
		$id_mst_room_no_allocationr=selectColumn(TBL_ATTRIBUTES,'id'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND id_mst_room_no= '".$_REQUEST['room_no']."'");	
		//`id_attribute_table`='".$id_mst_room_no_allocationr."',
		
		 $UpdateTotalAmount =  "UPDATE `".TBL_PURCH."` SET 
				
				 `payment_amount_received`='".$total_amount_recevied."'
				
				 
				  where`id` = '".$purch_id."'
				  ";   //die;
				  mysqli_query($connNew,$UpdateTotalAmount);  
		//Update Payment =====================================	
		
						
					$insertGridFolio =  "UPDATE `".TBL_PURCH."` SET 
				
				 `id_fo_bill`='0',
				 `id_fo_folio`='0',
				`id_fo_folio_to`='0'
				 
				  where`id` = '".$purch_id."'
				  ";	
				 mysqli_query($connNew,$insertGridFolio);  
					
					
					
					$Message['msg']= 'Unsettled Successfully';
							$Message['status']= '2';
							echo json_encode($Message);
							die;
				
					
					}elseif(strtotime($Yesterday)<=strtotime($doc_date)){
						executeSql("DELETE from `".TBL_PURCH_PAY."` where id_purch='".$purch_id."' ");
							
					//	audit_trail-================================					
	$ch5 ="POS BILL Unsettled " .   date('d-m-Y H:i:s');
	$ch6 ="  <br/><b>REMARKS</b> - " .   $_REQUEST['UnsettleRemarks'];				
	$auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($purch_id)."',
	`tables_name` = '".TBL_PURCH_PAY."',
	`form_code` = 'POS',
	`changes` =  '".addslashes($ch5).addslashes($ch6)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";
	 mysqli_query($connNew,$auditeditSql);
	 //	audit_trail-================================
					
		//Update Payment =====================================
		$total_amount_recevied	=  selectColumn(TBL_PURCH_PAY,'sum(amount)'," WHERE id_purch='".$purch_id."'");
					$id_mst_room_no_allocationw=selectColumn(TBL_ATTRIBUTES,'id'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND id_mst_room_no= '".$_REQUEST['room_no']."'");	
					if($paytype=='ROOMTO'){
			$connRoomTo	="`id_attribute_table`='".$id_mst_room_no_allocationw."',";
			}
		 $UpdateTotalAmount =  "UPDATE `".TBL_PURCH."` SET 
				$connRoomTo
				 `payment_amount_received`='".$total_amount_recevied."'
				
				 
				  where`id` = '".$purch_id."'
				  ";  // die;
				  mysqli_query($connNew,$UpdateTotalAmount);  
		//Update Payment =====================================	
		
			$insertGridFolio =  "UPDATE `".TBL_PURCH."` SET 
				
				 `id_fo_bill`='0',
				 `id_fo_folio`='0',
				`id_fo_folio_to`='0'
				 
				  where`id` = '".$purch_id."'
				  ";	
				 mysqli_query($connNew,$insertGridFolio);  		
					
					
					
					
					$Message['msg']= 'Unsettled Successfully';
							$Message['status']= '2';
							echo json_encode($Message);
							die;
						
						}else{
							$Message['msg']= "You don't have permission to take this action.";
							$Message['status']= '0';
							echo json_encode($Message);
							die;					
						}
	}
	executeSql("DELETE from `".TBL_PURCH_PAY."` where id_purch='".$purch_id."' ");
	$CardCount=0;	
	$error='';
	
				 $error	=	is_array($_REQUEST['payamount'][$purch_id]);
	
				if($error!=1 && $error=='' ){
					$Message['msg']= 'Please Select any One Payment Type';
					$Message['status']= '0';
					echo json_encode($Message);
					die;
				}
				
				 	
	foreach($_REQUEST['payamount'][$purch_id] as $paytype =>$value){
		
		
				
		foreach($value as $Array2 => $k){
			
			if($paytype=='CASH'){$id_type=1;}
			if($paytype=='CARD'){$id_type=2;}
			if($paytype=='ONLINETRANSFER'){$id_type=3;}
			if($paytype=='COMPANY'){$id_type=4;}
			if($paytype=='CHEQUE'){$id_type=5;}
			if($paytype=='UPI'){$id_type=6;}
			if($paytype=='ROOMTO'){$id_type=7;}
			if($paytype=='BIllONHOLD'){$id_type=8;}
			
			$remarks			= $_REQUEST['remarks'][$purch_id][$paytype][$Array2];
			$tips	   	   	   = $_REQUEST['tips'][$purch_id][$paytype][$Array2];
			$amount		 	 = $_REQUEST['payamount'][$purch_id][$paytype][$Array2];
			$id_company		 = $_REQUEST['id_company'][$purch_id][$paytype][$Array2];
			if($_REQUEST['savetype']==0){ $amount=0; } //UnSettled
			if($paytype=='CARD' || $paytype=='ONLINETRANSFER'){
				
				$cardnumber	 = $_REQUEST['cardnumber'][$purch_id]['CARDNUMBER'][$Array2];
				
				/*echo 'COUNT='.$CardCount.$cardtypevalue=$_REQUEST['cardtype'][$purch_id]['CARDTYPE'][$CardCount];
				
				$cardType	 = $cardtypevalue;
						if($cardType=='3'){
							$paytype='ONLINETRANSFER';
							$id_onlinetransfertype	 = $cardtypevalue;
							$cardType	 = $cardtypevalue;
							$id_charges_master	 = $_REQUEST['id_bank'][$purch_id]['BANK'][$Array2];
							}else{
								$paytype='CARD';
								$cardType	 = $cardtypevalue;
								
								
								}*/
						
						//echo '============'.$error	=	is_array($_REQUEST['cardtype']);
					//echo '=='.$_REQUEST['cardtype'][$purch_id]['CARDTYPE'][$Array2];
					if($_REQUEST['cardtype'][$purch_id]['CARDTYPE'][$Array2]==0 || $_REQUEST['cardtype'][$purch_id]['CARDTYPE'][$Array2]=='' ){
					$Message['msg']= 'Please Select Card Type';
					$Message['status']= '0';
					echo json_encode($Message);
					die;
					}	
					
							
				if(is_array($_REQUEST['cardtype'][$purch_id]['CARDTYPE'][$Array2])){   
						//print_r($_REQUEST['cardtype'][$purch_id]['CARDTYPE'][$Array2]);
						//die;
					foreach($_REQUEST['id_bank'] as $bankkey=> $bankid){
					//echo '('.$bankkey.')';
					
					//echo $bankkey.'==='.$Array2.'===='.$_REQUEST['id_bank'][$bankkey]['BANK'][0];
					if($_REQUEST['id_bank'][$bankkey]['BANK'][0] ==0 ){
					$Message['msg']= 'Please Select Bank';
					$Message['status']= '0';
					echo json_encode($Message);
					die;
						}
					}			
				
					foreach($_REQUEST['cardtype'][$purch_id]['CARDTYPE'][$Array2] as $key =>$cardtypevalue){
						
					
				
				
					
						
						$cardType	 = $cardtypevalue;
						if($cardType=='3'){
							$paytype='ONLINETRANSFER';
							$id_onlinetransfertype	 = $cardtypevalue;
							$cardType	 = $cardtypevalue;
							$id_charges_master	 = $_REQUEST['id_bank'][$purch_id]['BANK'][$Array2];
							$amount		 	    = $_REQUEST['payamount'][$purch_id]['CARD'][$Array2];
							$remarks			   = $_REQUEST['remarks'][$purch_id]['CARD'][$Array2];
							$tips	   	   	      = $_REQUEST['tips'][$purch_id]['CARD'][$Array2];
							
							
						}else{
							    $paytype='CARD';
								$cardType	 = $cardtypevalue;
								$id_onlinetransfertype = '';
								$amount		 	    = $_REQUEST['payamount'][$purch_id]['CARD'][$Array2];
								$remarks			   = $_REQUEST['remarks'][$purch_id]['CARD'][$Array2];
								$tips	   	   	      = $_REQUEST['tips'][$purch_id]['CARD'][$Array2];
								 $id_charges_master	 = $_REQUEST['id_bank'][$purch_id]['BANK'][$Array2];
								
									
							}
							
						/*if($id_charges_master ==0 || $id_charges_master=='' ){
					$Message['msg']= 'Please Select Bankss';
					$Message['status']= '0';
					echo json_encode($Message);
					die;
						}*/	
						
					}
				}
			}else{
				$cardnumber 	 = '';
				$cardType	   = '';  
			}
			if($paytype=='COMPANY'){
				$id_company	 = $_REQUEST['id_company'][$purch_id]['COMPANY'][$Array2];
				
				if($id_company =='0' ){
					$Message['msg']= 'Please Select Company';
					$Message['status']= '0';
					echo json_encode($Message);
					die;
						}
			}else{
				$id_company 	 = '';
			}
			if($paytype=='ROOMTO'){
				//$id_fo_folio	 = $_REQUEST['id_fo_bill'][$purch_id]['ROOMTO'][$Array2];
				$id_fo_bill	 = $_REQUEST['id_fo_bill'][$purch_id]['ROOMTO'][$Array2];
				if($id_company =='0' ){
					$Message['msg']= 'Please Select Room';
					$Message['status']= '0';
					echo json_encode($Message);
					die;
						}
				//$id_fo_bill	=  selectColumn('fo_folio','id_fo_bill'," WHERE `id` = '".$id_fo_folio."'");
				$id_fo_folio	=  selectColumn('fo_folio','id'," WHERE `id_fo_bill` = '".$id_fo_bill."'");
				
				$insertGrid =  "UPDATE `".TBL_PURCH."` SET 
				
				 `id_fo_bill`='".$id_fo_bill."',
				 `id_fo_folio`='".addslashes($id_fo_folio)."',
				`id_fo_folio_to`='".addslashes($id_fo_folio)."'
				 
				  where`id` = '".$purch_id."'
				  ";
				//echo $insertGrid;die;
			//$insertOrder	=mysqli_query($connNew,$insertGrid);
		
		
		mysqli_query($connNew,$insertGrid);		
			}else{
				$id_fo_bill 	 = '';
			}
			/*if($paytype=='ONLINETRANSFER'){
				$id_onlinetransfertype	 = $_REQUEST['onlinetransfertype'][$purch_id]['ONLINETYPE'][$Array2];
			}else{
				$id_onlinetransfertype 	 = '';
			}*/
			
            $insertSql = "INSERT INTO ".TBL_PURCH_PAY." 
                        SET 
                        id_purch='".$purch_id."',
						id_type='".$id_type."',
                        payment_mode='".$paytype."',
                        amount='".$amount."',
						remark='".$remarks."',
						id_fo_bill='".$id_fo_bill."',
						id_company='".$id_company."',
						id_charges_master='".$id_charges_master."',
						id_cardtype='".$cardType."',
						id_onlinetransfertype='".$id_onlinetransfertype."',
						doc_date='".date('Y-m-d',  strtotime($doc_date))."',
						time='".date('H:i:s')."',
						cardnumber='".$cardnumber."',
						ccredit='".$paytype."',
                        tips='".$tips."' "; 

            $insertSql .= ",last_modified='".date('Y-m-d H:i:s')."',
                        date_created='".date('Y-m-d H:i:s')."',
                        id_mst_user_created_by='".$_SESSION['userId']."',
                        id_mst_user_modified_by='".$_SESSION['userId']."' ";
			//echo '<br><br><br>'.$insertSql;			
             mysqli_query($connNew,$insertSql);    
		     $CardCount++;   
			 //	audit_trail-================================
			 $ch4 .='<br>'.$paytype.'='.$amount;
			 //	audit_trail-================================ 
			}
        } 
		if($_REQUEST['savetype']==0){
			//echo '0###Unsettled Successfully';
		}else{
    //	audit_trail-================================		
	$ch5 ="POS BILL Saved Successfully " .   date('d-m-Y H:i:s');
	$auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($purch_id)."',
	`tables_name` = '".TBL_PURCH_PAY."',
	`form_code` = 'POS',
	`changes` =  '".addslashes($ch5).addslashes($ch4)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";
	mysqli_query($connNew,$auditeditSql); 
	//	audit_trail-================================
			//Update Payment =====================================
		$total_amount_recevied	=  selectColumn(TBL_PURCH_PAY,'sum(amount)'," WHERE id_purch='".$purch_id."'");
		
			$id_mst_room_no_allocationw= selectColumn(TBL_ROOMNO,'id'," WHERE `room_no` = '".$_REQUEST['room_no']."'");
		$id_mst_room_no_allocationw=selectColumn(TBL_ATTRIBUTES,'id'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND id_mst_room_no= '".$id_mst_room_no_allocationw."'");	
			//echo " WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND id_mst_room_no= '".$_REQUEST['room_no']."'";
			
			if($paytype=='ROOMTO'){
			$connRoomTo	="`id_attribute_table`='".$id_mst_room_no_allocationw."',";
			}
		  $UpdateTotalAmount =  "UPDATE `".TBL_PURCH."` SET 
				$connRoomTo
				 `payment_amount_received`='".$total_amount_recevied."'
				
				 
				  where`id` = '".$purch_id."'
				  "; //die;  
				  mysqli_query($connNew,$UpdateTotalAmount);  
		//Update Payment =====================================	
		
			
			// echo '0###Billing Saved Successfully';
	   				$Message['msg']= 'Billing Saved Successfully';
					$Message['status']= '1';
					echo json_encode($Message);
					die;
		}
		
	//}else{
	//	 echo '1###Please Update Amount';
	//	die;
	//	}
	
?>