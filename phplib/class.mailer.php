
<?php


class sendMail extends PHPMailer
{
    private $_host = 'mail.roomstatushub.com';
    private $_user = 'info@roomstatushub.com';
    private $_password = 'info123#';

    public function __construct($exceptions=true)
    {
        $this->Host = $this->_host;
        $this->Username = $this->_user;
        $this->Password = $this->_password;
        $this->Port = 25;
        $this->SMTPAuth = true;
       // $this->SMTPSecure = 'tls';
        $this->isSMTP();
        parent::__construct($exceptions);
   }

   public function sendMail($from, $to="", $subject, $body, $cc="",$multi=array())
   {  
   	  $this->WordWrap = 50;
	    $this->isHTML(true);
      $this->setFrom($from);

      if($to !="")
        $this->addAddress($to);

      if($cc !="")
        $this->addCC($cc);

      if($multi !=""){
        for($i=0 ; $i < count($multi); $i++){
          $this->addAddress($multi[$i]);
        }
      }

      $this->Subject = $subject;
      $this->Body = $body;

      return $this->send();
  }
  
  public function autoMail($from, $to, $subject, $body, $cc="", $file_to_attach="",$file_name="")
   {  

      /*echo $from."<br>";
      echo $to."<br>";
      echo $subject."<br>";
      echo $body."<br>";
      echo $cc."<br>";
      echo $file_to_attach."<br>";
      echo $file_name."<br>";*/
      
      $this->WordWrap = 50;
      $this->isHTML(true);
      $this->setFrom($from);
      $this->addAddress($to);

      if($cc != ""){
        $this->addCC($cc);
      }
      if($file_to_attach !=""){  
        //echo '..\adminpanel\autoMailerExport\\'.$file_to_attach.'.xls';
        $this->AddAttachment('..\adminpanel\autoMailerExport\\'.$file_to_attach.'.xls');
      }  
      
      $this->Subject = $subject;
      $this->Body = $body;
      return $this->send();
  }
  
  
   /*public function sendReminderMail($id)
   {  
  	$sqlOrderDetail = executeSQl("SELECT `".TBL_ORDERS."`.*, `".TBL_ORDER_DETAIL."`.hotel_id FROM `".TBL_ORDERS."` LEFT JOIN `".TBL_ORDER_DETAIL."` ON  `".TBL_ORDERS."`.id_order=`".TBL_ORDER_DETAIL."`.id_order where `".TBL_ORDERS."`.booking_status='2' and `".TBL_ORDERS."`.id_order= '".addslashes($id)."'"); 
		 $rowOrderDetail = fetch_object($sqlOrderDetail); 
    $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($rowOrderDetail->id_customer)."'"); 
		 $rowGuestDetail = fetch_object($sqlGuestDetail); 
 	$resHotelDetail = selectSql(TBL_HOTELS,"where id='".addslashes($rowOrderDetail->hotel_id)."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = fetch_object($resHotelDetail); 
	$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($rowOrderDetail->id_company_person)."'",''); 
		  $resultContact = fetch_object($resContact); 
	$resCompany = selectSql(TBL_COMPANY,"where id_company='".addslashes($rowOrderDetail->id_company)."'",''); 
		  $resultCompany = fetch_object($resCompany); 
		  
		  
	$from = 'noreply@roomstatushub.com';	
	$body = '<p><strong>'.$resultContact->first_name.' '.$resultContact->last_name.'</strong><br />
    <strong>'.$resultCompany->name.'</strong><br />
    <br />
  Dear '.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.', &nbsp;<br />
  Greetings from WelcomHeritage!!!&nbsp;<br />
  <br />
  This is in reference to the booking of the caption guest, RSD SERIES from  22/02/2018 to 23/02/2018 at WELCOMHERITAGE KOOLWAL KOTHI, NAWALGARH wherein the  voucher is due by 22/01/2018 .&nbsp;<br />
  You are requested to send us the voucher by the specified date to reconfirm  the booking and avoid automatic system cancellation of the same. &nbsp;<br />
  In case of any further assistance please feel free to get in touch with us. <br />
  With Kind Regards.<br />
  ADMIN-ADMIN ENTRY<br />
  <strong>C.C. : WELCOMHERITAGE KOOLWAL KOTHI, NAWALGARH</strong><br />
  HOUSE NO. 40, GOVT. HOSPITAL ROAD,, NAWALGARH - 333 042, RAJASTHAN-PHONE  NO:01594 225817</p>';
  
    
		  
   	  $this->WordWrap = 50;
	  $this->isHTML(true);
      $this->setFrom($from);
      $this->addAddress('gaurav0736@gmail.com');
      $this->Subject = $subject;
      $this->Body = $body;

      return $this->send();
  }
  
  
  
  
  
  */
  
  
}
?>