<?php 
include_once("../config/auto_loader.php");



if(isset($_REQUEST['path']))
	$path=$_REQUEST['path'];
else{
	$path='';
}

if(isset($_REQUEST['id_request']))
	$id_request=$_REQUEST['id_request'];



if(isset($_REQUEST['table_name']) && $_REQUEST['table_name']!=''){
	$sql = "SELECT image FROM ".$_REQUEST['table_name']." WHERE id_shop='".$_SESSION['shop']."' AND id=".$id_request." ";
	$res = mysqli_query($connNew,$sql);
	$row = mysqli_fetch_object($res);
	$image=$row->image;
}
else{
	$image='';
}

$imageData='<div class="col-sm-4">
    <div class="form-group">                
        <label for="image">Display Offer Image &nbsp;&nbsp;</label>
        
        <div class="btn btn-default btn-file">
            <i class="fa fa-upload"></i> Upload
            <input type="hidden" value="'.$image.'" id="imageName" name="imageName">
            <input onchange="uploadImg(\'image\',\'offerImg\',\'offers\',\'imageName\');" type="file" class="form-control" placeholder="Display Image" id="image" name="photo" value="'.$image.'" >
                      
        </div>
        
    </div>  
    <?php echo $err_image;?>
</div>

<div class="col-sm-8">                                        
    <ul class="mailbox-attachments clearfix"> 
        <li id="imageCallback">
                          
                <span class="mailbox-attachment-icon has-img">
                    <img id="offerImg" src="'.$path.$image.'" alt="Image">     
                </span>           
                <div class="mailbox-attachment-info">
                    <a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> '.$image.'</a>
                    <span class="mailbox-attachment-size">
                        
                    <a href="'.$path.$image.'" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                    </span>
                </div>
                
        </li>                
    </ul>           
</div>';

echo $imageData;

?>