<?php 
include_once("../../config/auto_loader.php");
?>

<?php
$Message=array();
 	//echo '<pre>';print_r($_REQUEST);echo '</pre>';die;
	$doc_date  =$_REQUEST['po_date1'];
   	$purch_id	=	$_REQUEST['get_purch_id'];
	//if($_REQUEST['pay_total_amount_'.$purch_id]>0){
		//echo "DELETE from `".FO_RECEIPT."` where id_fo_bill='".$purch_id."' ";
		//die;
	$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
	$numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
	$rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
	$today = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));	
		
	if($_REQUEST['savetype']==0){ //Unsettled
	
	$doc_date  =  date('d-m-Y', strtotime(selectColumn(FO_BILL,'doc_date'," WHERE `id_mst_shops` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$purch_id."'")));
	$TodayDate	= date('d-m-Y');
	$Yesterday	=date('d-m-Y',strtotime('-1 day',strtotime($TodayDate)));   //date('d-m-Y',strtotime($TodayDate , '-days'));
		$id_reservations	= selectColumn(FO_BILL,'id_reservations'," WHERE `id_mst_shops` = '".addslashes($_SESSION['shop'])."' AND `id_fo_folio_to` = '".$purch_id."'");
//$id_reservations=	selectColumn(FO_BILL,'id_reservations'," WHERE `id_mst_shops` = '".addslashes($_SESSION['shop'])."' AND `id_fo_folio_to` = '".$id_fo_folio_to."'");
		
		$checkout	= selectColumn('fo_reservations','checkout'," WHERE `id` = '".$id_reservations."'");
	
				if($_SESSION['userLevel']=='1'){
					
							executeSql("DELETE from `".FO_RECEIPT."` where id='".$_REQUEST['id_receipt']."' ");
							$Message['msg']= 'Unsettled Successfully';
							$Message['status']= '2';
							$Message['id_invoice_detail']= $purch_id;
							echo json_encode($Message);
							die;
				
					
					}elseif(strtotime($Yesterday)<=strtotime($checkout)){
						executeSql("DELETE from `".FO_RECEIPT."` where id='".$_REQUEST['id_receipt']."' ");
							$Message['msg']= 'Unsettled Successfully';
							$Message['status']= '2';
							$Message['id_invoice_detail']= $purch_id;
							echo json_encode($Message);
							die;
						
						}else{
							$Message['msg']= "You don't have permission to take this action.".$checkout;
							$Message['status']= '0';
							$Message['id_invoice_detail']= $purch_id;
							echo json_encode($Message);
							die;					
						}
	}
	//executeSql("DELETE from `".FO_RECEIPT."` where id_fo_bill='".$purch_id."' ");
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
				$id_fo_bill	 = $_REQUEST['id_fo_bill'][$purch_id]['ROOMTO'][$Array2];
				
				if($id_company =='0' ){
					$Message['msg']= 'Please Select Room';
					$Message['status']= '0';
					echo json_encode($Message);
					die;
						}
				$insertGrid =  "UPDATE `".TBL_PURCH."` SET 
				
				 `id_fo_bill`='".$id_fo_bill."'
				 
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
			
            $insertSql = "INSERT INTO ".FO_RECEIPT." 
                        SET 
                        id_fo_bill='".$purch_id."',
					    `id_fo_folio`='".addslashes($_REQUEST['id_folio'])."',
						id_type='".$id_type."',
                        payment_mode='".$paytype."',
                        amount='".$amount."',
						remark='".$remarks."',
						
						id_company='".$id_company."',
						id_charges_master='".$id_charges_master."',
						id_cardtype='".$cardType."',
						id_onlinetransfertype='".$id_onlinetransfertype."',
						doc_date='".date('Y-m-d',  strtotime($today))."',
						time='".date('H:i:s')."',
						cardnumber='".$cardnumber."',
						ccredit='".$paytype."',
                        tips='".$tips."' "; 

            $insertSql .= ",last_modified='".date('Y-m-d H:i:s')."',
                        date_created='".date('Y-m-d H:i:s')."',
                        id_mst_user_created_by='".$_SESSION['userId']."',
                        id_mst_user_modified_by='".$_SESSION['userId']."' ";
			//echo '<br><br><br>'.$insertSql;die;			
             mysqli_query($connNew,$insertSql);    
		     $CardCount++;      
			}
        } 
		if($_REQUEST['savetype']==0){
			//echo '0###Unsettled Successfully';
		}else{
       // echo '0###Billing Saved Successfully';
	   				$Message['msg']= 'Folio Saved Successfully';
					$Message['status']= '1';
					$Message['id_invoice_detail']= $_REQUEST['id_folio'];$_REQUEST['id_invoice_detail'];
					echo json_encode($Message);
					die;
		}
		
	//}else{
	//	 echo '1###Please Update Amount';
	//	die;
	//	}
	
?>
    