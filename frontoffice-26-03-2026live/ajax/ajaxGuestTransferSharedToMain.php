<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
		
		if($_REQUEST['id_fo_reservations']!=='' &&   $_REQUEST['id_mst_room_no_allocation']!=='' && $_REQUEST['order_by_room']!='' && $_REQUEST['id_shared_guest']!=''){
			//$id_shared_guest=array();
			$id_shared_guest = selectColumn(FO_RESERVATIONS_DETAILS, 'id_shared_guest', 'WHERE `id_fo_reservations` = "' . $_REQUEST['id_fo_reservations'] . '"  and order_by_room = "' . $_REQUEST['order_by_room'] . '" ');
$id_mst_guest = selectColumn(FO_RESERVATIONS_DETAILS, 'id_mst_guest', 'WHERE `id_fo_reservations` = "' . $_REQUEST['id_fo_reservations'] . '"  and order_by_room = "' . $_REQUEST['order_by_room'] . '" ');

$id_shared_guest2 = array();
if ($id_shared_guest != '') {
    $id_shared_guest2 = explode(',', $id_shared_guest);

    $key = array_search($_REQUEST['id_shared_guest'], $id_shared_guest2);
    if ($key !== false) {
        unset($id_shared_guest2[$key]);
    }

    // Add $id_mst_guest to array (if not already present)
    if (!in_array($id_mst_guest, $id_shared_guest2)) {
        $id_shared_guest2[] = $id_mst_guest;
    }

    // Re-index the array
    $id_shared_guest2 = array_values($id_shared_guest2);
}	

			  $id_mst_guest_folio	=	selectColumn("fo_folio",'id_mst_guest'," WHERE `id` = '".$_REQUEST['id_folio']."'");
			  
			  
			  if($id_mst_guest_folio==$id_mst_guest){
				  
			  $folioSql = " UPDATE  fo_folio SET				 
						
											
						`id_mst_guest`='".$_REQUEST['id_shared_guest']."'	
						
						WHERE  
						`id` = '".$_REQUEST['id_folio']."'  
						
						";
				mysqli_query($connNew,$folioSql);
				  
				  }
			
			  $id_shared_guest =implode(',',$id_shared_guest2);
			
			  $roomdetails = " UPDATE  `".FO_RESERVATIONS_DETAILS."` SET				 
						
						`id_shared_guest`='".$id_shared_guest."',					
						`id_mst_guest`='".$_REQUEST['id_shared_guest']."'	
						
						WHERE  
						`id_fo_reservations` = '".$_REQUEST['id_fo_reservations']."'  and order_by_room = '".$_REQUEST['order_by_room']."' 
						
						";
				mysqli_query($connNew,$roomdetails);
		}
		
		
?>  <table id="foliotable" class="table table-bordered table-striped datatable" cellspacing="0" width="100%" style="    background-color: #F5F5F5;">
    <thead><?php 
	  
	  if($id_shared_guest!=''){
		 $id_shared_guest	= explode(',',$id_shared_guest);
		  foreach($id_shared_guest as $sharecount=>$shareGuestid){
			 
			$SQL_ShareGuest = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and id='".$shareGuestid."'";
            
            $query_ShareGuest=mysqli_query($connNew, $SQL_ShareGuest);
            
			   $ShareGuestCheckout = "select *  from fo_shared_guest where shared_guest_status='1' and `id_mst_shops` = '".addslashes($_SESSION['shop'])."' and id_fo_folio_to='".$_REQUEST['id_folio']."' and id_shared_guest='".$shareGuestid."'";
            
            $queryShareGuestCheckout =mysqli_query($connNew, $ShareGuestCheckout);
			$checkoutNumRows =mysqli_num_rows($queryShareGuestCheckout);
            ?>
            <tr><td><?php
            
              $result_ShareGuest=mysqli_fetch_assoc($query_ShareGuest);
			   $Title_ShareGuest=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$result_ShareGuest['id_mst_attributes_title']."'"); 	
			  ?>
           
               <?php 
			   echo $result_ShareGuest['guest_reg_no'] . ' - Name : '.ucfirst($Title_ShareGuest).'. '.ucfirst($result_ShareGuest['first_name']).' '.ucfirst($result_ShareGuest['last_name']).' |  Mobile : '.$result_ShareGuest['primary_mobile'];
			   ?></td>
				 <td class="text-center">
					 <?php if($checkoutNumRows==0){?>
               <!-- <button class="btn btn-sm btn-danger" onclick="removeGuestDetail(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id']; ?>)">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <button class="btn btn-sm btn-secondary" onclick="guestTransferSharedToMain(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">
                    <i class="fas fa-sign-out-alt"></i>Room Owner 
                </button>
                <button class="btn btn-sm btn-primary" onclick="promptCheckinTimeAndCheckout(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">
                    <i class="fas fa-sign-out-alt"></i> Checkout
                </button>-->
					  <div class="flex space-x-2">
    <button class="text-red-600 hover:text-red-800" title="Remove Guest" onclick="removeGuestDetail(<?php echo addslashes($_REQUEST['id_fo_reservations']).','.$_REQUEST['id_mst_room_no_allocation'].','.$_REQUEST['order_by_room'].','.$_REQUEST['room_no'].','.$_REQUEST['id_multiple_guest'].','.$_REQUEST['id_folio']; ?>)">🗑</button>
    <button class="text-gray-700 hover:text-gray-900" title="Make Room Owner" onclick="guestTransferSharedToMain(<?php echo addslashes($_REQUEST['id_fo_reservations']).','.$_REQUEST['id_mst_room_no_allocation'].','.$_REQUEST['order_by_room'].','.$_REQUEST['room_no'].','.$_REQUEST['id_multiple_guest'].','.$_REQUEST['id_folio']; ?>)">🏠 Room Owner</button>
    <button class="text-blue-600 hover:text-blue-800" title="Pax Checkout"  onclick="promptCheckinTimeAndCheckout(<?php echo addslashes($_REQUEST['id_fo_reservations']).','.$_REQUEST['id_mst_room_no_allocation'].','.$_REQUEST['order_by_room'].','.$_REQUEST['room_no'].','.$_REQUEST['id_multiple_guest'].','.$_REQUEST['id_folio']; ?>)">📤 Pax Checkout</button>
  </div> 
					 <?php } ?>
            </td>
            </tr><?php 
		 }
			 } 
		  
		  
	  
	  ?></thead></table>