<?php




function consolidatedItemWiseReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport,$kot_nc,$appConnect,$connNew,$id_shop,$cronSet,$pdfName){	
	//global $connNew;
	$contentstyle='';
	//global $appConnect;
	$_SESSION['shop']=$id_shop;
	//echo '==================='.$id_report_type;
//echo '.= =================='.$report_show;die;
//echo '=======================>'.$id_main_group;
//echo '=======================>'.$id_sub_group;
//echo '=======================>'.$id_items;
//print_r($_REQUEST);
if($id_items!=''){
			 $sqlinvItem="SELECT * FROM inv_items WHERE status='1'  AND FIND_IN_SET(id,'".$id_items."')";
           
            $resinvItem = mysqli_query($connNew,$sqlinvItem);
		   $ItemSelectSearch=array();
           while($rowinvItem = mysqli_fetch_object($resinvItem)){
			   array_push($ItemSelectSearch,$rowinvItem->name);
		   }
		    $showitemName	=	implode(',',$ItemSelectSearch);

}

if($id_main_group!=''){
			 $sqlmain_group="SELECT * FROM ".TBL_ATTRIBUTES." WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' AND  FIND_IN_SET(id,'".$id_main_group."')";
           
            $resmain_group = mysqli_query($connNew,$sqlmain_group);
		   $main_groupSelectSearch=array();
           while($rowmain_group = mysqli_fetch_object($resmain_group)){
			   array_push($main_groupSelectSearch,$rowmain_group->field_value);
		   }
		     $showiMainGroupName	=	implode(',',$main_groupSelectSearch);

}

if($id_sub_group!=''){
			   $sqlsub_group="SELECT * FROM ".TBL_ATTRIBUTES." WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND  FIND_IN_SET(id,'".$id_sub_group."')";
           
            $ressub_group = mysqli_query($connNew,$sqlsub_group);
		   $sub_groupSelectSearch=array();
           while($rowsub_group = mysqli_fetch_object($ressub_group)){
			   array_push($sub_groupSelectSearch,$rowsub_group->field_value);
		   }
		    $showSubGroupName	=	implode(',',$sub_groupSelectSearch);

}

$content ='';
if($date!=''){
	
	$SearchDate = explode(' to ',$date);
	$ReportDate =	'From '.$SearchDate[0].' To '.$SearchDate[1];
	$SqlRepDateConn =	" AND DATE(doc_date) between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";	
	}
if($id_main_group!=''){
	$SqlRepConn .=	" AND FIND_IN_SET(id_mst_attributes_group_main,'".$id_main_group."')";	
	}
	
if($id_sub_group!=''){
	$SqlRepConn .=	" AND FIND_IN_SET(id_mst_attributes_group_sub,'".$id_sub_group."')";	
	}
if($id_items!=''){
	$SqlRepConn .=	" AND FIND_IN_SET(id_mst_items,'".$id_items."')";	
	}		
	$logo	= selectField(TBL_SHOP,'image','WHERE id="'.$_SESSION['shop'].'" ');
	
		//196">Consolidated Item Wise //pp.id_mst_outlet, pp.id_attribute_steward, pp.id_attribute_table,pp.id_attribute_shift
if($id_report_type!=''){

	if($id_report_type=='197'){//Pos Day Wise
		$SqlGroupByConn =',doc_date';
		$SqlOrderByConn ='doc_date';
		$SqlOrderByConnType =' desc ';
	}elseif($id_report_type=='198'){//Pos User Wise
		$SqlGroupByConn ='';
	}elseif($id_report_type=='199'){//POS Outlet
		$SqlGroupByConn =',id_mst_outlet';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}elseif($id_report_type=='200'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_steward';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}elseif($id_report_type=='238'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_shift';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';	
	}elseif($id_report_type=='201'){//	Pos Discount Wise<
		$SqlGroupByConn ='';
	
	}elseif($id_report_type=='196'){//	Pos Item Wise<
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}
	
	}
if($id_order_by!=''){	
  if($id_order_by==1){
	 // $OrderDiplay	=	'Name';
	 // $SqlOrderByConn ='item_description';
	 // $SqlOrderByConnType =' ASC ';
	  
	  if($id_report_type=='197'){//Pos Day Wise
		$OrderDiplay	=	'Date Wise';
		$SqlOrderByConn ='doc_date';
		$SqlOrderByConnType =' DESC ';
	}elseif($id_report_type=='200'){//Pos Steward Wise<
		$OrderDiplay	=	'Steward Name';
		$SqlOrderByConn ='steward_name';
		$SqlOrderByConnType =' ASC ';	
	}elseif($id_report_type=='196'){//	Pos Item Wise<
		$OrderDiplay	=	'Name';
	  $SqlOrderByConn ='item_description';
	  $SqlOrderByConnType =' ASC ';
	}elseif($id_report_type=='238'){//	Pos Shift Wise<
		$OrderDiplay	=	'shift name';
	  $SqlOrderByConn ='shift_name';
	  $SqlOrderByConnType =' ASC ';
	}elseif($id_report_type=='199'){//POS Outlet
		$OrderDiplay	=	'Outlets name';
	  $SqlOrderByConn ='outlets_name';
	  $SqlOrderByConnType =' ASC ';
	}/*elseif($id_report_type=='198'){//Pos User Wise
		$SqlGroupByConn ='';
	}elseif($id_report_type=='200'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_steward';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}elseif($id_report_type=='238'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_shift';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';	
	}elseif($id_report_type=='201'){//	Pos Discount Wise<
		$SqlGroupByConn ='';
	
	}elseif($id_report_type=='196'){//	Pos Item Wise<
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}*/
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  }
	 if($id_order_by==2){
	  $SqlOrderByConn ='qty';
	  $SqlOrderByConnType =' DESC ';
	  $OrderDiplay	=	'Qty';
	  }
	  if($id_order_by==3){
	  $SqlOrderByConn ='grandtotal';
	  $SqlOrderByConnType =' DESC ';
	  $OrderDiplay	=	'Amount';
	  } 	  
}
	
	if($kot_nc =='0'){ //0">Without KOT NC
$SqlRepDateConn .=" and pp.pos_bill_type='2' and pp.doc_type='21'";
}
if($kot_nc =='1'){ //1">With KOT NC
$SqlRepDateConn .=" and pp.pos_bill_type IN (1,2) and pp.doc_type IN (21,24)";
}
if($kot_nc =='2'){ //2">Only KOT NC
$SqlRepDateConn .=" and pp.pos_bill_type='1' and pp.doc_type='24'";
}
  $pos_purch_sql="select 
			doc_date,     
			id_mst_items,
			item_code,
			item_description,
			sum(qty) as qty,
			sum(total)as grandtotal,
			id_mst_attributes_group_main,
			id_mst_attributes_group_sub,
 id_mst_outlet, id_attribute_steward, id_attribute_table,id_attribute_shift,outlets_name,steward_name,shift_name
	from 
(
    
			select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description,  ppp.id as id_purch_detail,inv.item_code,
			ppp.qty, ppp.item_amount, ppp.item_discount_amount,((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)  as  total,
			
			inv.id as id_item,
			inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,
			pp.id_mst_outlet, pp.id_attribute_steward, pp.id_attribute_table,pp.id_attribute_shift,attributesshift.field_value as shift_name,attributessteward.field_value as steward_name,outlets.name as outlets_name
			
			FROM pos_purch  pp
			LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id
			LEFT JOIN mst_attributes AS  attributesshift ON attributesshift.`table_name` = 'shift' and attributesshift.id=pp.id_attribute_shift	
			LEFT JOIN mst_attributes AS  attributessteward ON attributessteward.`table_name` = 'steward' and attributessteward.id=pp.id_attribute_steward
			LEFT JOIN mst_outlets AS  outlets ON  outlets.id=pp.id_mst_outlet		
			INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items
			
			WHERE  pp.cancelled=0 and  pp.id_shop= '".addslashes($_SESSION['shop'])."'
			$SqlRepDateConn	
  
  			order by inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.name
			
    ) as purch_rpt

WHERE id_mst_items!=0 $SqlRepConn
group by id_mst_items $SqlGroupByConn
order by  $SqlOrderByConn  $SqlOrderByConnType ";
	 //"SELECT * FROM ".TBL_INV_ITEMS."   WHERE id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' AND id IN(".$id_iteam_purch.")  order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
		//echo $pos_purch_sql;die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$DatewiseArray['Report']['id_mst_attributes_group_main']=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 if($id_report_type!=''){
	if($id_report_type=='197'){//Pos Day Wise
		$maingroupName	=date('d-m-Y',strtotime($posPurchResult->doc_date));
	}elseif($id_report_type=='198'){//Pos User Wise
		$SqlGroupByConn ='';
	}elseif($id_report_type=='199'){//POS Outlet
		$maingroupName	= strtoupper(selectColumn(TBL_OUTLETS,'name'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND `id` = '".$posPurchResult->id_mst_outlet."'  "));
		
	
	}elseif($id_report_type=='238'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_shift';
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1'  AND  `id` = '".$posPurchResult->id_attribute_shift."'"));
	
	}elseif($id_report_type=='200'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_steward';
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1'  AND  `id` = '".$posPurchResult->id_attribute_steward."'"));
	}elseif($id_report_type=='201'){//	Pos Discount Wise<
		$SqlGroupByConn ='';
	}else{
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' AND  `id` = '".$posPurchResult->id_mst_attributes_group_main."'"));
		}
	
	}
		 
		if($id_report_type=='196'){ $maingroupName='item';
		 $DatewiseArray['Report']['id_mst_attributes_group_main'][]=$maingroupName;
		 $DatewiseArray['Report']['id_mst_attributes_group_sub'][$maingroupName][]=$posPurchResult->id_mst_attributes_group_sub;
		 $DatewiseArray['Report']['id_inv_items'][$maingroupName]['item2'][]=$posPurchResult->id_mst_items;
		 $DatewiseArray['Report']['name'][$maingroupName]['item2'][] =ucfirst($posPurchResult->item_description);
		 $DatewiseArray['Report']['item_code'][$maingroupName]['item2'][] =$posPurchResult->item_code;
		 $DatewiseArray['Report']['grandtotal'][$maingroupName]['item2'][] =$posPurchResult->grandtotal;
		 $DatewiseArray['Report']['qty'][$maingroupName]['item2'][] =$posPurchResult->qty;	
		}else{
		 $DatewiseArray['Report']['id_mst_attributes_group_main'][]=$maingroupName;
		 $DatewiseArray['Report']['id_mst_attributes_group_sub'][$maingroupName][]=$posPurchResult->id_mst_attributes_group_sub;
		 $DatewiseArray['Report']['id_inv_items'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][]=$posPurchResult->id_mst_items;
		 $DatewiseArray['Report']['name'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][] =ucfirst($posPurchResult->item_description);
		 $DatewiseArray['Report']['item_code'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->item_code;
		 $DatewiseArray['Report']['grandtotal'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->grandtotal;
		 $DatewiseArray['Report']['qty'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->qty;
		}
	 }
	
	
	$MainGroup=array_unique($DatewiseArray['Report']['id_mst_attributes_group_main']);
	//debugData($DatewiseArray);
//	echo '<pre>';print_r($DatewiseArray);	echo '</pre>';//die;
	
	//HTML View START==============================================================================>
	?>
    <script>$(function() {
		
	
  
  
    $('.table').on("click", ".line", function(e){
		var checkstatus=this.classList.toggle('maingroupi');
		
		if(checkstatus == true){ 
		
		 $(".mainplusclose").show();
          $(".mainplusopen").hide();
		  
      }if(checkstatus == false){ 
	  
		   $(".mainplusopen").show(); 
        $(".mainplusclose").hide();
       
      }
		
      //$(this).addClass('fa fa-plus').siblings().removeClass('fa fa-plus').addClass('fa fa-minus');
        $(this).siblings('.subgrouphideclass').toggle(100);
	//	$(this).toggleClass('fa fa-pluse').siblings().removeClass('fa fa-pluse');
		//$(this).closest('.maingroupi').find('.hidden-item').removeClass('shown');
		$('tr.line')  
                .css("cursor", "pointer")  
                .attr("title", "Click to expand/collapse")  
                .click(function () {  
				
                    $(this).siblings('.child-' + this.id).toggle();  
                });  
            $('tr[@class^=child-]').hide().children('td');
			
			
		//var thisHiddenItem = $(this).find(".table1shafeer");
    //$(this).siblings('line').removeClass('fa fa-plus');
		//$(this).siblings(thisHiddenItem).toggleClass("fa fa-plus table1shafeer");
		//$(".line").find(".table-plus-btn table1").removeClass("fa fa-pluse").addClass("fa fa-minus");
		if (this.classList.toggle('maingroupi') == true) { 
		 //$(this).addClass('fa fa-plus').siblings().removeClass('fa fa-minus').addClass('fa fa-minus');
		//$(this).addClass('fa fa-minus').siblings().removeClass('fa fa-minus');
		//$(this).toggleClass('fa fa-pluse').siblings().removeClass('fa fa-fa fa-pluse');
		//$("#shafeer").hide();
		//$(this).siblings('.subgrouphideclass').removeClass('fa fa-plus');
		//$('fa fa-plus').not(this).removeClass('fa fa-plus');
   // $(this).toggleClass('fa fa-plus');
          //     $('#ahamed').hide();
		//document.getElementById("maingroupi").className = "fa fa-minus";
		//$(this).addClass('fa fa-pluse').siblings().addClass('fa fa-minus');
//  $("maingroupi").find(".maingroupi").removeClass("fa fa-pluse").addClass("fa fa-minus");
}
		//$(this).addClass('fa fa-minus').siblings().removeClass('fa fa-minus').addClass('fa fa-minus');
		   //document.getElementById("rdb21").className = "btn btn-foursquare col-md-3";
      }).on("click", ".subgrouphideclass", function(){ 
      ///
	  var submov	=	this.classList.toggle('submov');
	  // alert(submov);
	 // var showItemReport=$("#showItemReport").val();
	//$( "#showItemReport" ).val(opts);
	 if(submov == true){
		 $( "#showItemReport" ).val('1');
		// alert(submov);
		   $(".mainplusclose").show();
          $(".mainplusopen").hide();
		  $("#showheadinglable").show(); 
        $("#hideheadinglable").hide();
      } if(submov == false){   //alert('=='+submov);
	  $( "#showItemReport" ).val('0');
         $(".mainplusopen").show(); 
        $(".mainplusclose").hide();
		
		
		 $("#showheadinglable").hide(); 
        $("#hideheadinglable").show();
      }
	
        $(this).siblings('.mov').toggle(100);
		 //$(this).siblings('#sp1').toggle(100);
      //$(".sp1").hide();
      }).on("click", ".delete", function(){
      
        $(this).closest("table").fadeOut(function(){alert('12'); 
        	$(this)/*the table*/.remove();
        });
      
    });
  function toggleButtons(show) {alert(show);
      if (show) {
        $("#sp1").hide();
        $("#close").show();
      } else {
        $("#sp1").show();
        $("#close").hide();
      }
  }
});
	  </script>
    <?php 
	$content = '<style>
body { 
	margin:0px; 
	padding:0px;
	font-size:13px !important;
 
 }
.table-bordered {
    	 border: 1px solid #000;
	 border-collapse: collapse;
}
.table {
	font-size:11px !important; 
    margin-bottom: 20px;	   
    width:100%;
} 
table {
	font-size:11px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
	}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
</style> 


<style>
	  .line:hover {
	background-color:#cf5;
	cursor: pointer;
}
.subgrouphideclass:hover {
	background-color:#cf5;
	cursor: pointer;
}
.table { 
    margin: 0 auto;
    width:100%;
    border-collapse: collapse;
    table-layout:fixed;
}
.table td,
.table th{
    padding:5px 10px;
    border:1px solid #444;
}';

if($report_show =='1' ){
		$contentstyle='';
$contentstyle= '
	.table tr.mov
{
    display:none;}';
	
}else{
	$contentstyle='';
	
	if( $report_show!=3){
		$contentstyle= '
	.table tr.mov,
.table tr.subgrouphideclass{
    display:block;
}
';
	}
	}
	

$content.=$contentstyle;

$content .= '</style>';
$foldername =    "/app";

$pathImg = $_SERVER['DOCUMENT_ROOT'].$foldername;

$BackgroundColorMain	='background-color:#edf2f4;';
$BackgroundColor	='background-color:#fff;';
if($report_show!=1){
	/*$content .= '<table  class="table" style=" text-align:center;margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />
						 </th>
						</tr>
			</table>';*/
}
$sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE status='1' and type='2' AND id='".$id_report_type."'";
           
            $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);

            $rowSubMenu = mysqli_fetch_object($resSubMenu);
				
				//selectColumn(APP_SUB_MENU,'name'," WHERE status='1' and type='2' AND id='".$id_report_type."'");
					$headerColor = selectField(APP_COLOR_CONFIG,'report_header_color','',$appConnect); 
		$headerTextColor = selectField(APP_COLOR_CONFIG,'report_header_text_color','',$appConnect); 
		$titleColor = selectField(APP_COLOR_CONFIG,'report_title_color','',$appConnect); 
		$titleTextColor = selectField(APP_COLOR_CONFIG,'report_title_text_color','',$appConnect); 
		$subtitleColor = selectField(APP_COLOR_CONFIG,'report_subtitle_color','',$appConnect); 
		$subtitleTextColor = selectField(APP_COLOR_CONFIG,'report_subtitle_text_color','',$appConnect); 
				
			$content .= '<table class="table table-striped text-center">';
	$content .= '<tr style="vertical-align:central;text-align:center;"><th colspan="4" style="vertical-align:central;text-align:left;color:'.$headerTextColor.';background-color:'.$headerColor.'; font-size:16px !important"><b> '.ucwords($rowSubMenu->name).'  Report Period '.$date.' Order By '.$OrderDiplay.' </b></th>
	<th  style="vertical-align:central;text-align:center;color:'.$headerTextColor.';background-color:'.$headerColor.'; font-size:13px !important"><b> Report Date: '.date('d-m-Y H:m:i').' </b></th></tr>';
	
	
if($showiMainGroupName!=''){	
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Main Group : </b>'.ucwords(strtolower($showiMainGroupName)).'  </th></tr>';
}if($showSubGroupName!=''){
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Sub Group : </b>'.ucwords(strtolower($showSubGroupName)).'  </th></tr>';
}if($showitemName!=''){		
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Item Name : </b>'.ucwords(strtolower($showitemName)).'  </th></tr>';
}
		$content .= '</table>';
			
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center;">';
		//$content .= '<tr style="font-size:16px !important;" id="hideheadinglable">';
		//$content .= '<th  width="60px;" ><b>S.no</b></th>';
		if($id_report_type==196){
		$content .= '<tr style="color:'.$titleTextColor.';background-color:'.$titleColor.';font-size:16px !important;" id="hideheadinglable">';	
		$content .= '<th width="60px;"  ><b>S.no</b></th>';	
		$content .= '<th width="140px;"><b>Item Code</b></th>';
		$content .= '<th ><b>Item Name</b></th>
		<th style="width:200px;text-align: center; " ><b>Qty</b></th>
		<th style="width:200px;text-align: center;  "><b>Amount</b></th>';
		$content .= '</tr>';
		}else{
		
				if(($report_show==3) ||  ($report_show==2)){
					$displayClass	='';
				}else{
					if($cronSet!='1'){
					$displayClass	=' display:none;';} }
		$content .= '<tr style="font-size:16px !important; '.$displayClass.'" id="showheadinglable">';
		//$content .= '<th  width="60px;" >&nbsp;</th>';
		$content .= '<th  style="width:60px;" ><b>S.no</b></th>';	
		$content .= '<th style="width:140px;"><b>Item Code</b></th>';		
		$content .= '<th   ><b>Item Name</b></th>
		<th style="width:200px;text-align: center; " ><b>Qty</b></th>
		<th style="width:200px;text-align: center;  "><b>Amount</b></th>';
		$content .= '</tr>';
		}
			//$content .= '</table>	    ';
		
		//$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%">';

	
	
	
	
	$GrandTotalQTY=0;
	$GrandTotalAmount=0;
	if($id_report_type==196){
		$colspa1=3;
	}else{
		$colspa1=3;
		}
		
		
		
		
		
	foreach($DatewiseArray['Report']['name'] as $id_main=>$subindexvalue){
		//Main Group=======================>
			
		/*$content .= '<tr class="line" style="'.$BackgroundColorMain.'background-color:#c2d69a;color:#ooo !important;font-size:16px !important;">
			<th  colspan="'.$colspa1.'" ><b>'.$maingroupName.'</b></th>
			</tr>';*/
			
		$MainGroupTotalQTY=0;
		$MainGroupTotalAmount=0;
		$subgroupInc=1;	$contentSubGroup='';$contentItem='';
		foreach($subindexvalue as $id_subindex=>$data){
			
			
			
			$k=0;
			$i=1;
			
			$subgroupTotalQTY=0;
			$SubGroupTotalAmounts=0;
				
				foreach($data as $datavalue){
				
				if($id_report_type!='196'){
					$listTagClass	=	'class="mov"';
				}
				$qty=		number_format($DatewiseArray['Report']['qty'][$id_main][$id_subindex][$k],2);
				$grandtotal=	number_format($DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k],2);
				if(($report_show==1)  || ($report_show==2  && $showItemReport==1 ) ||( $report_show==3 && $showItemReport==1) || ($id_report_type=='196') || $cronSet=='1'){
					
				$contentItem .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000;   background-color:#fff;">';				
				$contentItem .= '<td style="text-align:center;width:200px;">'.$i.'</td>';
				$contentItem .= '<td  style="text-align:left;">'.$DatewiseArray['Report']['item_code'][$id_main][$id_subindex][$k].'</td>';
				if($id_report_type==196){
					$contentItem .= '<td  style="text-align:left;" >'.strtoupper($datavalue).'</td>';
					}else{
						$contentItem .= '<td style="text-align:left;">'.strtoupper($datavalue).'</td>';
						}
				
				$contentItem .= '<td style="text-align:right;width:200px;">'.$qty.'</td>
				<td style=text-align:right;width:200px;">'.$grandtotal.'</td>
				</tr>';
				}
				$subgroupTotalQTY+=$qty;
				$SubGroupTotalAmounts+=$DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k];
				
				$MainGroupTotalQTY+=$qty;
				$MainGroupTotalAmount+=$DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k];
						
				$GrandTotalQTY+=$qty;
				$GrandTotalAmount+=$DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k];
				
				$i++;
				$k++;
			}
			//$content.=$content;
		//Sub GroupItem
			/*$content .= '<tr style="border:1px solid:font-size:11px !important;">
			<td width="60px;" style="text-align:center">'.$subgroupInc.'</td>
			
			<td>'.strtoupper($subgroupName).'</td>
			
			<td style="width:100px;text-align:right;">'.number_format($subgroupTotalQTY,2).'</td>
			<td style=width:100px;text-align:right;">'.number_format($SubGroupTotalAmounts,2).'</td>
			</tr>';*/
			
			
			//Sub Group=======================>
			if($id_report_type!='196'){
				$subgroupName=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND  `id` = '".$id_subindex."'"));
			
			$contentSubGroup .= '<tr class="subgrouphideclass" style="'.$BackgroundColor.'color:#000 !important;font-size:12px !important; font-weight:bold;">';
			
			
		$contentSubGroup .=  '<td  colspan="3"  style="color:#ooo !important;text-align:left;">&nbsp;<b>'.$subgroupName.'</b></td>';
			
			
			$contentSubGroup .=  '<td style=text-align:right;width:200px;">'.number_format($subgroupTotalQTY,2).'</td>';
			$contentSubGroup .=  '<td style=text-align:right;width:200px;">'.number_format($SubGroupTotalAmounts,2).'</td>';
			$contentSubGroup .=  '</tr>';
			}
			//$contentItem2.=$contentItem;
			
			$colspa=3;
			$contentSubGroup .= $contentItem;
			$subgroupInc++;	$contentItem='';
			}
		
		if($id_report_type!='196'){  //First Step
			$content .= '<tr class="line" style="'.$BackgroundColorMain.'color:#ooo !important;font-size:16px !important;">';
			//$content .= ' <td width="120px; border-right:none;" colspan="2"  ></td>';
			$content .= ' <td  colspan="3"  style="text-align:center;" >';
			
			
			$content .= ' <b>'.$id_main.'</b></td>';
			
			
			
			$content .= '<td  style=text-align:right;width:200px;"><b>'.number_format($MainGroupTotalQTY,2).'</b></td>
						<td  style=text-align:right;width:200px;"><b>'.number_format($MainGroupTotalAmount,2).'</b></td>
			</tr>';
		}
		$content .= $contentSubGroup;	
		//$content .= $content2;
		}
		
		
	
			
			
		$content .= '</table>';
		$content;
		//die;
		$date=date('d-m-yy');
if($id_report_type==196){		
$Filename='consolidatedItemWiseReport_'.$date;
}else{
$Filename='consolidatedSubGroupWiseReport_'.$date;	
	}

	if($report_show==3){ 
		
//pdfGeneratorAttach($content,'te2s.pdf');
		if($cronSet=='1'){
			echo $pdfName;
			pdfGeneratorAttach($content,$pdfName);
		}else{
			$dompdf = new DOMPDF();
			$dompdf->set_paper('landscape', 'landscape');
			$dompdf->load_html($content);
			$dompdf->render();
			$font = Font_Metrics::get_font("helvetica", "bold");
			$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
			$dompdf->output();
			$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
		}
	}else if($report_show==2){
			 $test=$content;
			//die;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename".'.xls');
        echo $test;die;
	}
	else{
		 echo $content;
		die;
		}
		}	
		
	function cellColorExcel($cells,$color,$objPHPExcel){
    	//global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    			)	
    	));
	}	
		

	
	?>