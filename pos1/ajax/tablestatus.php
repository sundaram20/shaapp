  <div class="box-header with-border paxbox" style="display:hnone;">
                <!--<h5 class="box-title">Main Group </h5>-->
              
                <?php


if($_REQUEST['doc_type']==''){
		$doc_type_bill=21;
		$doc_type_kot=22;
	}else{ //KOT NC
		$doc_type_bill=23;
		$doc_type_kot=24;
	}
	
	 $sql = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE   `doc_type`='22' ";
	 $resToPrint = mysqli_query($connNew,$sql);
 	 $numRows =  mysqli_num_rows($sql);
	   $rowdoc =  mysqli_fetch_object($resToPrint);
		 $idDocConfigDetail= $rowdoc->id;
	
	$doc_detail = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' ";
	$resToPrint1= mysqli_query($connNew,$doc_detail);
	 $rowdoc1 =  mysqli_fetch_object($resToPrint1);
		 $idsubsection = $rowdoc1->id_subsection;

?>
                <label for="name"  title="Table"><i class="fas fa-chair"></i> : <span id="ViewSelectedTable1" style="color:#FF0000"></span></label>
                <input type="hidden" name="doc_type_bill" id="doc_type_bill" value="<?php echo $doc_type_bill; ?>"/>
                <input type="hidden" name="doc_type_kot" id="doc_type_kot" value="<?php echo $doc_type_kot; ?>"/>
                <input type="hidden" name="id_subsection" id="id_subsection" value="<?php echo $idsubsection; ?>"/>
                <input type="hidden" name="id_attribute_table" id="id_attribute_table" value=""/>
                <label for="name" title="No of Pax">  <i class="fa fa-users"></i> : <span id="ViewSelectedPax1" style="color:#FF0000" ></span></label>
                <input type="hidden" name="pax" id="pax" value=""/>
                <label for="name"  title="Steward Name"><i class="fa-solid fa-person"></i>: <span id="ViewSteward1" style="color:#FF0000"></span></label>
                <input type="hidden" name="id_attribute_steward" id="id_attribute_steward" value=""/>
                <input type="hidden" name="attribute_steward_name" id="attribute_steward_name" value=""/>
           
              </div>
              <!--box-heaer ends-->