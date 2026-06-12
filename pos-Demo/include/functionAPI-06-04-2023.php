<?php
function settlementSummaryReportAPI($Date,$id_outlet,$id_shift,$connNewQ){
	
	//global $connNew;
	global $objPHPExcel;
	
	$id_shop=2;
	
	if($Date != ''){
		$DateExplode = explode(' to ',$Date);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$SqlConn .= " AND `date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$SqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConn .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}


	$resShop  =  mysqli_query($connNewQ,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".$id_shop."'");
	$rowShop = mysqli_fetch_object($resShop);
	$logo	=	$rowShop->image;
	



	
$SQL="select 
       id as 'Payment ID'
	  ,id_attribute_shift
	  ,id_attribute_table
	  ,pax
      ,id_mst_outlet
      ,outlet_Name
      ,mdoc_no
      ,sub_total_items
      ,(discount_amount_additional+total_discount_items) AS 'Discount'
      ,others_charges_net_amount As 'OtherCharges'      
      ,sgst_total_items as sgst
      ,cgst_total_items as cgst
      ,igst_total_items as igst
      ,cess_total_items as cess
      ,vat_total_items as vat
      ,surcharge_total_items as surcharge      
      ,round_off_amount
      ,net_amount_items
      ,grant_total_amount
      ,name as 'UserName'
	  ,max(time) as Time
	  ,sum(CASH) as Cash
	  ,sum(CARD) as Card
	  ,sum(CHEQUE) as Cheque
	  ,sum(UPI) as 'Upi'
	  ,sum(ONLINETRANSFER) as 'OnlineTransfer'
	  ,sum(COMPANY) as Company
	  ,field_value as 'FeildValue'
      ,max(date_created) as 'CreatedDate'
	  ,max(bill_date_created) as 'bill_date_created'
	  ,id_charges_master
	  ,remark
	  ,doc_no
	  ,id_company
	  
from 
(
select
       p.id
	   ,p.id_attribute_shift
      ,comp.name as 'outlet_Name'
      ,usr.name
	  ,time as Time
	  ,p.mdoc_no
      ,p.id_mst_outlet
      ,p.sub_total_items
      ,p.sgst_total_items
      ,p.cgst_total_items
      ,p.igst_total_items
      ,p.cess_total_items
      ,p.vat_total_items
      ,p.surcharge_total_items
      ,p.round_off_amount
      ,p.net_amount_items
      ,p.grant_total_amount
      ,p.others_charges_net_amount
      ,p.discount_amount_additional
      ,p.total_discount_items
	  ,case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH
	  ,case when payment_mode='CARD' and id_cardtype=1 then IFNULL(amount,0) else null end as CARD
	  ,case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE
	  ,case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI
	  ,case when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) then IFNULL(amount,0) else null end as ONLINETRANSFER
	  ,case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY
	  ,att.field_value
      ,pp.date_created as date_created
	  ,p.pax
	  ,p.id_attribute_table
	  ,p.date_created as bill_date_created
	  ,p.doc_no
	  ,pp.id_charges_master
	  ,pp.remark
	  ,pp.id_company
	  
    
	from pos_purch_pay pp
	INNER JOIN
	pos_purch p
	on
	p.id = pp.id_purch
		
	
	INNER JOIN
	mst_attributes att
	on
	att.id = p.id_attribute_shift   
	
	INNER JOIN
	mst_outlets as comp
	on
	comp.id = p.id_mst_outlet   
		
		
	INNER JOIN
	mst_users usr
	on
	usr.id=pp.id_mst_user_created_by
   
    
) as settlement_summary
WHERE id!=0 $SqlConn
GROUP BY id,name,field_value  ORDER BY id_mst_outlet,id_attribute_shift,doc_no asc";
//echo $SQL;
//die;

$query = mysqli_query($connNewQ,$SQL);
$TotalNumberOfRows = mysqli_num_rows($query);

$InCount=1;
$con++;
$count2=1;
$TotalBillCount=0;
$transactions=array();
if($TotalNumberOfRows>0){
while($Records	   =	mysqli_fetch_object($query)){
	$shiftSummary	=	$Records->id_attribute_shift;
	
	
	

	
	$SqlAttrbuteTable =  mysqli_query($connNewQ,"SELECT * FROM `".TBL_ATTRIBUTES."` where id_shop='".$id_shop."'  and status = '1' and `table_name` = 'table' AND id= '".$Records->id_attribute_table."'");
	
    $resultAttrbuteTable = mysqli_fetch_object($SqlAttrbuteTable);
	
	
		
				$transaction['sno'] =$InCount; 
				$transaction['outlet_Name']=trim($Records->outlet_Name);
				$transaction['mdoc_no']=$Records->mdoc_no;
				$transaction['date_created']= date('d-m-Y',strtotime($Records->bill_date_created));  //->setCellValue('D'.$con, date('d-m-Y',strtotime($Records->CreatedDate)))
				$transaction['pax']= $resultAttrbuteTable->field_value.'('.$Records->pax.')';
				
				$transaction['sub_total']= $Records->sub_total_items;
				$transaction['Discount']= $Records->Discount;
				$transaction['net_amount']= $Records->sub_total_items-$Records->Discount;
				$transaction['sgst']= $Records->sgst;
				$transaction['cgst']= $Records->cgst;
				$transaction['igst']= $Records->igst;
				$transaction['cess']= $Records->cess;
				$transaction['vat']= $Records->vat;
				$transaction['surcharge']= $Records->surcharge;
				$transaction['OtherCharges']= $Records->OtherCharges;
				$transaction['round_off_amount']= $Records->round_off_amount;
				$transaction['grant_total_amount']= $Records->grant_total_amount;
				$transaction['UserName']=$Records->UserName;
				$transaction['Time']= $Records->Time;
				$transaction['Cash']= $Records->Cash;
				$transaction['Card']= $Records->Card;
				$transaction['Company']= $Records->Company;
				$transaction['Cheque']= $Records->Cheque;
				$transaction['OnlineTransfer']= $Records->OnlineTransfer;
				$transaction['remarks']= $remarks;
				$transactions[]=$transaction;
			
		
		//SUMMARY DETAILS====================================================
		$count2++;
		$con++;
		$InCount++;
		$TotalBillCount++;
	}
}else{			
		 $msg = array('message' => "error",'status' => "Please check Request");
		 $mailMsg="failure";
		 //$pushConn->response($this->json($msg), 200); // If no records "No Content" status
			echo json_encode($msg);
		 }
// Rename worksheet
	
//Print
//echo json_encode($transactions);
//echo str_replace(PHP_EOL,"\n",json_encode($transactions));
echo str_replace('\\/', '/',json_encode($transactions));

	//echo $json =  json_encode($transactions);
//echo $json = preg_replace('!\\r?\\n!', "", json_encode($transactions));


	
	}
	
	




		
	?>