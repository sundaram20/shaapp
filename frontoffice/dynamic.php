<html>
<head>
 <title>Dynamic Input Fields</title>
        <!--Jquery Link-->
      
 <!-- Bootstrap Styling-->
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
 <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
 
 
 <!-- custom stylesheet-->

 
 <!-- custom javascript-->

 
</head>
 
<body>
  
    <div class="container">
        <div class="row centered-form">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="panel panel-info">
 
                    <div class="panel-heading text-center">
                   
                        <h1 class="panel-title">Add Content</h1>
                    </div>
 
                    <div class="panel-body">
                        <form role="form" method="post" action="">
                            
                            <div class="list_wrapper">  
                                <div class="row">
 
                                    <div class="col-xs-2 col-sm-2 col-md-2">
 
                                        <div class="form-group">
                                            Item Name
                                            <input name="list[0][]" type="text" placeholder="Type Item Name" class="form-control"/>
                                            
                                        </div>
                                    </div>
 
                                    <div class="col-xs-2 col-sm-2 col-md-2">
                                        <div class="form-group">
                                            Quantity
                                            <input autocomplete="off" name="list[0][]" type="text" placeholder="Type Item Quantity" class="form-control"/>
                                        </div>
                                    </div> 
								    <div class="col-xs-2 col-sm-2 col-md-2">
                                        <div class="form-group">
                                            Quantity2
                                            <input autocomplete="off" name="list[0][]" type="text" placeholder="Type Item Quantity" class="form-control"/>
                                        </div>
                                    </div> 
									<div class="col-xs-2 col-sm-2 col-md-2">
                                        <div class="form-group">
                                            Quantity3
                                            <input autocomplete="off" name="list[0][]" type="text" placeholder="Type Item Quantity" class="form-control"/>
                                        </div>
                                    </div> 
									<div class="col-xs-2 col-sm-2 col-md-2">
                                        <div class="form-group">
                                            Quantity4
                                            <input autocomplete="off" name="list[0][]" type="text" placeholder="Type Item Quantity" class="form-control"/>
                                        </div>
                                    </div> 
									<div class="col-xs-2 col-sm-2 col-md-2">
                                        <div class="form-group">
                                            Quantity5
                                            <input autocomplete="off" name="list[0][]" type="text" placeholder="Type Item Quantity" class="form-control"/>
                                        </div>
                                    </div>
									
 
                                    <div class="col-xs-1 col-sm-1 col-md-1">
                                        <br>
                                       <button class="btn btn-primary list_add_button" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="submit" value="Submit" class="btn btn-info btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
 
	<script>
	$(document).ready(function()

    {
	var x = 0; //Initial field counter
	var list_maxField = 10; //Input fields increment limitation
	
        //Once add button is clicked
	$('.list_add_button').click(function()
	    {
	    //Check maximum number of input fields
	    if(x < list_maxField){ 
	        x++; //Increment field counter
	        var list_fieldHTML = '<div class="row"><div class="col-xs-2 col-sm-2 col-md-2"><div class="form-group"><input name="list['+x+'][]" type="text" placeholder="Type Item Name" class="form-control"/></div></div><div class="col-xs-2 col-sm-2 col-md-2"><div class="form-group"><input name="list['+x+'][]" type="text" placeholder="Type Item Quantity" class="form-control"/></div></div><div class="col-xs-2 col-sm-2 col-md-2"><div class="form-group"><input name="list['+x+'][]" type="text" placeholder="Type Item Quantity" class="form-control"/></div></div><div class="col-xs-2 col-sm-2 col-md-2"><div class="form-group"><input name="list['+x+'][]" type="text" placeholder="Type Item Quantity" class="form-control"/></div></div><div class="col-xs-2 col-sm-2 col-md-2"><div class="form-group"><input name="list['+x+'][]" type="text" placeholder="Type Item Quantity" class="form-control"/></div></div><div class="col-xs-1 col-sm-7 col-md-1"><a href="javascript:void(0);" class="list_remove_button btn btn-danger">-</a></div></div>'; //New input field html 
	        $('.list_wrapper').append(list_fieldHTML); //Add field html
	    }
        });
    
        //Once remove button is clicked
        $('.list_wrapper').on('click', '.list_remove_button', function()
        {
           $(this).closest('div.row').remove(); //Remove field html
           x--; //Decrement field counter
        });
});
	
	</script>
</body>
</html>