<?php 
session_start();
//session_destroy();

	include("connection.php");
	include("functions.php");

	if(!(isset($_SESSION["user_id"]))){
        header("Location: login.php");
    }

?>


<!DOCTYPE html>
<html>
<head>
	<title>check</title>
    <script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<script src="js/bootstrap.min.js"></script>
    <style type="text/css">

.popover
		{
		    width: 100%;
		    max-width: 800px;
		}
	
	#text{

		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 100%;
	}

	#button{

		padding: 10px;
		width: 100px;
		color: white;
		background-color: lightblue;
		border: none;
	}

	#box{

		background-color: grey;
		margin: auto;
		width: 300px;
		padding: 20px;
	}

	</style>
</head>
<body>


    <nav class="navbar navbar-default" role="navigation" style="background:#444; ">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Menu</span>
						<span class="glyphicon glyphicon-menu-hamburger"></span>
						</button>
						<a class="navbar-brand" href="http://localhost/Ramli-commerce/" style="color:white;">Ramli Store</a>
					</div>
					
					<div id="navbar-cart" class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li style="position:absolute; right: 0; bottom:0;;">
								<a id="cart-popover" class="btn" data-placement="bottom" title="Shopping Cart">
									<span class="badge"></span>
									<span class="total_price" style="color:white;">$ 0.00</span>
								</a>
							</li>
						</ul>
					</div>
					
				</div>
		</nav>

        <div id="display_order" style="margin-left: 40px;">


        </div>


</body>
<script>  
$(document).ready(function(){

	load_order();
    
	function load_order()
	{
		$.ajax({
			url:"files_php/fetch_order.php",
			method:"POST",
			data:{},
			success:function(data)
			{
				$('#display_order').html(data);
			}
		});
	}

    
});

</script>
</html>