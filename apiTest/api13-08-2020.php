<?php
/*
* File : api.php
* Author : Gaurav Sharma 
* Amended By : Hitesh Aloney
*/
require_once("rest.inc.php");
class API extends REST
{
public $data      = "";
const DB_SERVER   = "localhost";
const DB_USER     = "whhotels";
const DB_PASSWORD = "W@lC0mH@r1tage";
const DB          = "welcom_be";

private $db = NULL;
public $sql;
public $result;
private $keyValue = 'key123';
public function __construct()
{
parent::__construct();// Init parent contructor
$this->dbConnect();// Initiate Database connection
}

//Database connection
private function dbConnect()
{
$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
if($this->db)
mysql_select_db(self::DB,$this->db);
}
private function fetchRow(){
	if($this->db)
	{
	  return @mysql_fetch_assoc($this->result);		
	}	
}
private function numRow(){
	if($this->db)
	{
	  return @mysql_num_rows($this->result);		
	}	
}
private function query($sql){
	if($this->db)
	{
	 return  @mysql_query($sql);			
	}	
}

//Public method for access api.
//This method dynmically call the method based on the query string
public function processApi()
{
$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
if((int)method_exists($this,$func) > 0){
$this->$func();
}
else{
$this->response('',404);
}
// If the method not exist with in this class, response would be "Page not found".
}
private function productInfo()
{
// Cross validation if the request method is POST else it will return "Not Acceptable" status
if($this->get_request_method() != "POST")
{
$this->response('',406);
}
$rawPost = file_get_contents('php://input');
$jsondeocde = json_decode($rawPost, true);
$key = $jsondeocde['auth']['key'];
$propertyId = $jsondeocde['propertyId'];

@mysql_query("Insert into api_request set url_name='productInfo', parameters='$rawPost'");
// Input validations
if(!empty($propertyId) && $key== $this->keyValue){
		// Input validations

		//$id_hotel_sql = @mysql_query("SELECT id FROM `mst_hotels`  where hotel_code='".addslashes($propertyId)."' ", $this->db);

		//$id_hotel = @mysql_fetch_object($id_hotel_sql)->id;

		$sql = @mysql_query("SELECT hr.id_room as room_id,rt.name as room_name FROM `mst_assign_hotel_rooms` hr LEFT JOIN `mst_room_types` rt ON hr.id_room = rt.id  where hr.id_hotel='".addslashes($propertyId)."' and hr.status=1", $this->db);
		
		if(@mysql_num_rows($sql) > 0)
		{
			$result = array();
		while($rlt = @mysql_fetch_array($sql,MYSQL_ASSOC))
		{		  
			$result[] = $rlt;
		}			
	    //If success everythig is good send header as "OK" and user details
		  $msg = array('message' => "success",'status' => "success", "data" => $result);
		  $this->response($this->json($msg), 200);
		 }else{	
		 $result[] = 'No Content';	 
		 $msg = array('message' => "error",'status' => "no content", "data" => $result);
		 $this->response($this->json($msg), 200); // If no records "No Content" status
		 }
   }else {
    $result[] = 'Please check key and property value.';	 
	$msg = array('message' => "error",'status' => "not acceptable", "data" => $result);
	$this->response($this->json($msg), 200);
  }
}
////////////////////////////////////////////////////////////////////////////////////////////

private function inventoryUpdateOld()
{
// Cross validation if the request method is POST else it will return "Not Acceptable" status
if($this->get_request_method() != "POST")
{
$this->response('',406);
}

$rawPost = file_get_contents('php://input');
$jsondeocde = json_decode($rawPost, true);
$key = $jsondeocde['auth']['key'];

$propertyId = $jsondeocde['data']['propertyId'];
$roomType = $jsondeocde['data']['roomType'];

$startDate = $jsondeocde['data']['inventory'][0]['startDate'];
$endDate = $jsondeocde['data']['inventory'][0]['endDate'];
$free = $jsondeocde['data']['inventory'][0]['free'];
@mysql_query("Insert into api_request set url_name='inventoryUpdate', parameters='$rawPost'");
//$propertyId = $this->_request['propertyId'];
// Input validations
if(!empty($propertyId) && ($key== $this->keyValue) && !empty($roomType) && !empty($startDate) && !empty($endDate) && !empty($free)){
		// Input validations
		$sql = ("UPDATE inventory_master set avl_room = '".addslashes($free)."' where room_type='".addslashes($roomType)."' and hotel_id ='".addslashes($propertyId)."' and inv_tarrif_date between '".addslashes($startDate)."' and '".addslashes($endDate)."'");				
		if(mysql_query($sql)){		
	    // If success everythig is good send header as "OK" and user details
		  $msg = array('message' => "success",'status' => "success");
		  $this->response($this->json($msg), 200);
		 }else{			
		 $msg = array('message' => "error",'status' => "failure");
		 $this->response($this->json($msg), 200); // If no records "No Content" status
		 }
   }else {   
	$msg = array('message' => "error",'status' => "not acceptable");
	$this->response($this->json($msg), 200);
  }
}

//////////////////////////////////////////////////////////////////////////////////


private function ratePlanInfo()
{
// Cross validation if the request method is GET else it will return "Not Acceptable" status
if($this->get_request_method() != "POST")
{
$this->response('',406);
}

$rawPost = file_get_contents('php://input');
$jsondeocde = json_decode($rawPost, true);
$key = $jsondeocde['auth']['key'];
$propertyId = $jsondeocde['propertyId'];
$roomType = $jsondeocde['roomId'];
@mysql_query("Insert into api_request set url_name='ratePlanInfo', parameters='$rawPost'");
if(!empty($propertyId) && $key== $this->keyValue && !empty($roomType) ){
		// Input validations
		//	$sql = @mysql_query("SELECT min(tm.tarrif_date) as start_date, max(tm.tarrif_date) as end_date, tm.tax as taxPerc,  rp.id as rateplanId, rp.rate_plan_name from tarrif_master tm left join rate_plan rp on rp.id=tm.rate_plan_id where tm.type='2' and tm.status='0' and tm.hotel_id='".addslashes($propertyId)."' and tm.room_type='".addslashes($roomType)."' AND rp.status = '1' AND tm.tarrif_date > '2019-09-30' group by tm.rate_plan_id,tm.room_type,tm.hotel_id,tm.tax", $this->db);
        $sql = @mysql_query("SELECT min(tm.effective_date) as start_date, max(tm.effective_date) as end_date, rp.id_plan as rateplanId

        from be_base_rate_inventories tm 

        left join 

        be_room_plan_links rp on rp.id=tm.id_room_plan_link 

        where   tm.status='1' AND tm.id_hotel='".addslashes($propertyId)."' and rp.id_room='".addslashes($roomType)."' AND rp.status = '1' AND tm.effective_date > '2019-09-30' group by rp.id_plan,rp.id_room,tm.id_hotel", $this->db);		
		
		if(@mysql_num_rows($sql) > 0)
		{
		$result = array();
		while($rlt = @mysql_fetch_array($sql,MYSQL_ASSOC))
		{		  
		
		if($rlt['rateplanId'] != ''){
		$rlt['occupancy'] = array('Single','Double');
		$rlt['commissionPerc'] = 0;
		}
		if($rlt['rateplanId'] != ''){
		$rlt['validity'] = array('start_date'=>$rlt['start_date'],'end_date'=>$rlt['end_date']);
		}
		if($rlt['start_date'] != '' && $rlt['end_date']!=''){
		unset($rlt['start_date']);
		unset($rlt['end_date']);
		}
		
		$result[] = $rlt;
		}			
	    // If success everythig is good send header as "OK" and user details
		  $msg = array('message' => "success",'status' => "success", "data" => $result);
		  $this->response($this->json($msg), 200);
		 }else{	
		 $result[] = 'No Content';	 
		 $msg = array('message' => "error",'status' => "no content", "data" => $result);
		 $this->response($this->json($msg), 200); // If no records "No Content" status
		 }
   }else {
    $result[] = 'Please check key and property value.';	 
	$msg = array('message' => "error",'status' => "error", "data" => $result);
	$this->response($this->json($msg), 200);
  }
}


/////////////////////////////////////////////////////////////////////////////////

private function rateUpdateOld()
{
// Cross validation if the request method is POST else it will return "Not Acceptable" status
if($this->get_request_method() != "POST")
{
$this->response('',406);
}
$rawPost = file_get_contents('php://input');
$jsondeocde = json_decode($rawPost, true);
$key = $jsondeocde['auth']['key'];
$propertyId = $jsondeocde['data']['propertyId'];
$roomType = $jsondeocde['data']['roomType'];
$rateplanId = $jsondeocde['data']['rateplanId'];
$startDate = $jsondeocde['data']['rate'][0]['startDate'];
$endDate = $jsondeocde['data']['rate'][0]['endDate'];
$single = $jsondeocde['data']['rate'][0]['Single'];
$double = $jsondeocde['data']['rate'][0]['Double'];
@mysql_query("Insert into api_request set url_name='rateUpdate', parameters='$rawPost'");
// Input validations
if(!empty($propertyId) && ($key== $this->keyValue) && !empty($roomType) && !empty($rateplanId) && !empty($startDate) && !empty($endDate) && !empty($single) && !empty($double)){
		// Input validations
		$sql = ("UPDATE tarrif_master set single_room_price = '".addslashes($single)."', double_room_price = '".addslashes($double)."' where room_type='".addslashes($roomType)."' and hotel_id ='".addslashes($propertyId)."' and type='2' and rate_plan_id='".addslashes($rateplanId)."' and tarrif_date between '".addslashes($startDate)."' and '".addslashes($endDate)."'");		
		//echo $sql;		
		if(mysql_query($sql)){		
	    // If success everythig is good send header as "OK" and user details
		  $msg = array('message' => "success",'status' => "success");
		  $this->response($this->json($msg), 200);
		 }else{			
		 $msg = array('message' => "error",'status' => "failure");
		 $this->response($this->json($msg), 200); // If no records "No Content" status
		 }
   }else {   
	$msg = array('message' => "error",'status' => "not acceptable");
	$this->response($this->json($msg), 200);
  }
}



/////////////////////////////////////////////////////////////////////////////////



private function inventoryUpdate()
{
// Cross validation if the request method is POST else it will return "Not Acceptable" status
if($this->get_request_method() != "POST")
{
$this->response('',406);
}

$rawPost = file_get_contents('php://input');
$jsondeocde = json_decode($rawPost, true);
$key = $jsondeocde['auth']['key'];

$propertyId = $jsondeocde['data']['propertyId'];
$roomType = $jsondeocde['data']['roomType'];

$startDate = $jsondeocde['data']['inventory'][0]['startDate'];
$endDate = $jsondeocde['data']['inventory'][0]['endDate'];
$free = $jsondeocde['data']['inventory'][0]['free'];
$countInventory = count($jsondeocde['data']['inventory']);
$stopsell = $jsondeocde['data']['inventory'][0]['stopsell'];
@mysql_query("Insert into api_request set url_name='inventoryUpdate', parameters='$rawPost'");
//$propertyId = $this->_request['propertyId'];
// Input validations
if(!empty($propertyId) && ($key== $this->keyValue) && !empty($roomType) && ($countInventory != 0) ){
		// Input validations
		
		for($i=0;$i<$countInventory;$i++){
	    
	    
	    if(!empty($jsondeocde['data']['inventory'][$i]['stopsell'])){	
		$stopsell='0';
		}else{
		$stopsell='1';	
			}

		//$id_hotel_sql = @mysql_query("SELECT id FROM `mst_hotels`  where hotel_code='".addslashes($propertyId)."' ", $this->db);

		//$id_hotel = @mysql_fetch_object($id_hotel_sql)->id;
		
		//$sql = mysql_query("UPDATE be_inventory set online_allocation = '".addslashes($jsondeocde['data']['inventory'][$i]['free'])."' where id_room='".addslashes($roomType)."' and id_hotel ='".addslashes($propertyId)."' and allocation_date between '".addslashes($jsondeocde['data']['inventory'][$i]['startDate'])."' and '".addslashes($jsondeocde['data']['inventory'][$i]['endDate'])."'");
        $sql = mysql_query("UPDATE be_inventory set online_allocation = '".addslashes($jsondeocde['data']['inventory'][$i]['free'])."',status='".$stopsell."' where id_room='".addslashes($roomType)."' and id_hotel ='".addslashes($propertyId)."' and allocation_date between '".addslashes($jsondeocde['data']['inventory'][$i]['startDate'])."' and '".addslashes($jsondeocde['data']['inventory'][$i]['endDate'])."'");
		
		}
					
		if($sql){		
	    // If success everythig is good send header as "OK" and user details
		  $msg = array('message' => "success",'status' => "success");
		  $this->response($this->json($msg), 200);
		 }else{			
		 $msg = array('message' => "error",'status' => "failure");
		 $this->response($this->json($msg), 200); // If no records "No Content" status
		 }
   }else {   
	$msg = array('message' => "error",'status' => "not acceptable");
	$this->response($this->json($msg), 200);
  }
}

/////////////////////////////////////////////////////////////////////////////////

private function rateUpdate()
{
// Cross validation if the request method is POST else it will return "Not Acceptable" status
if($this->get_request_method() != "POST")
{
$this->response('',406);
}
$rawPost = file_get_contents('php://input');
$jsondeocde = json_decode($rawPost, true);
$key = $jsondeocde['auth']['key'];
$propertyId = $jsondeocde['data']['propertyId'];
$roomType = $jsondeocde['data']['roomType'];
$rateplanId = $jsondeocde['data']['rateplanId'];
$startDate = $jsondeocde['data']['rate'][0]['startDate'];
$endDate = $jsondeocde['data']['rate'][0]['endDate'];
$single = $jsondeocde['data']['rate'][0]['Single'];
$double = $jsondeocde['data']['rate'][0]['Double'];
$countRate = count($jsondeocde['data']['rate']);
$stopsell = $jsondeocde['data']['rate'][0]['stopsell'];
//print_r($jsondeocde['data']['rate']);
@mysql_query("Insert into api_request set url_name='rateUpdate', parameters='$rawPost'");
// Input validations
if(!empty($propertyId) && ($key== $this->keyValue) && !empty($roomType) && !empty($rateplanId) && ($countRate != 0)  ){
		// Input validations
		
		for($i=0;$i<$countRate;$i++){
		    
		if(!empty($jsondeocde['data']['rate'][$i]['stopsell'])){				
		$stopsell='0';		
		}else{
		$stopsell='1';
			}    
		
        //$id_room_plan_links=selectColumn('be_room_plan_links','id','WHERE id_plan="'.addslashes($rateplanId).'" and  id_hotel="'.addslashes($propertyId).'"  and id_room="'.addslashes($roomType).'"');
        
        $room_plan_sql = @mysql_query("SELECT id FROM `be_room_plan_links`  WHERE id_plan='".addslashes($rateplanId)."' and  id_hotel='".addslashes($propertyId)."'  and id_room='".addslashes($roomType)."' ", $this->db);

		$id_room_plan_links = @mysql_fetch_object($room_plan_sql)->id;        
		
	//	$sql = mysql_query("UPDATE be_base_rate_inventories set single_pax_price = '".addslashes($jsondeocde['data']['rate'][$i]['Single'])."', double_pax_price = '".addslashes($jsondeocde['data']['rate'][$i]['Double'])."' where  id_hotel ='".addslashes($propertyId)."'  and id_room_plan_link='".addslashes($id_room_plan_links)."' and effective_date between '".addslashes($jsondeocde['data']['rate'][$i]['startDate'])."' and '".addslashes($jsondeocde['data']['rate'][$i]['endDate'])."'");
	$sql = mysql_query("UPDATE be_base_rate_inventories set single_pax_price = '".addslashes($jsondeocde['data']['rate'][$i]['Single'])."', double_pax_price = '".addslashes($jsondeocde['data']['rate'][$i]['Double'])."' ,status='".$stopsell."' where  id_hotel ='".addslashes($propertyId)."'  and id_room_plan_link='".addslashes($id_room_plan_links)."' and effective_date between '".addslashes($jsondeocde['data']['rate'][$i]['startDate'])."' and '".addslashes($jsondeocde['data']['rate'][$i]['endDate'])."'");                            
			
		}
		
				
		//echo $sql;		
		if($sql){		
	    // If success everythig is good send header as "OK" and user details
		  $msg = array('message' => "success",'status' => "success");
		  $this->response($this->json($msg), 200);
		 }else{			
		 $msg = array('message' => "error",'status' => "failure");
		 $this->response($this->json($msg), 200); // If no records "No Content" status
		 }
   }else {   
	$msg = array('message' => "error",'status' => "not acceptable");
	$this->response($this->json($msg), 200);
  }
}


//////////////////////////////////////////
}

////////////////////////////////////////////////////////////////////////////////
// Initiiate Library
$api = new API;
$api->processApi();
?>