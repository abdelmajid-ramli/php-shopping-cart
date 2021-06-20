<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Ramli Shopping Cart</title>
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<script src="js/bootstrap.min.js"></script>
		<style>
		.popover
		{
		    width: 100%;
		    max-width: 800px;
		}
		.add_to_cart{
			color:tomato;
			font-size: 15px;
			font-weight: bold;
			/*background : tomato;*/
		}
		.add_to_cart:hover{
			color:white;
			background : tomato;
		}
		.product_price_class{
			font-weight: bold;
			color:blue;
		}
		</style>
	</head>
	<body>
			<nav class="navbar navbar-default" role="navigation" style="background:#444;">
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
			<div id="display_cat">

			</div>

			<div id="popover_content_wrapper" style="display: none">
				<span id="cart_details"></span>
				<!--  cheout btn and clear btn -->       <!--...-->
				<div align="right">
					<a href="check.php" class="btn btn-primary" id="check_out_cart">
					Check out
					</a>
					<a href="#" class="btn btn-default" id="clear_cart">
					 Clear
					</a>
				</div>
				<!-- end -->
			</div>

		<div > <!-- class="container" -->



			<div id="display_item">


			</div>
			
		</div>
	</body>
</html>

<script>  
$(document).ready(function(){

	load_product();

	load_cat();

	load_cart_data();
    
	function load_product(page,cat)
	{
		$.ajax({
			url:"files_php/fetch_item.php",
			method:"POST",
			data:{page:page,cat:cat},
			success:function(data)
			{
				$('#display_item').html(data);
			}
		});
	}

	function load_cat()
	{
		$.ajax({
			url:"files_php/fetch_cat.php",
			method:"POST",
			data:{},
			success:function(data)
			{
				$('#display_cat').html(data);
			}
		});
	}

	$(document).on('click', '.pagination_link', function(){  
           var page = $(this).attr("id");  
           load_product(page);  
      }); 

	$(document).on('click', '.categories', function(){
		   var cat= $(this).attr("id");   
           load_product(1,cat);  
    });

	function load_cart_data()
	{
		$.ajax({
			url:"files_php/fetch_cart.php",
			method:"POST",
			dataType:"json",
			success:function(data)
			{
				$('#cart_details').html(data.cart_details);
				$('.total_price').text(data.total_price);
				$('.badge').text(data.total_item);
			}
		});
	}

	$('#cart-popover').popover({
		html : true,
        container: 'body',
        content:function(){
        	return $('#popover_content_wrapper').html();
        }
	});

	$(document).on('click', '.add_to_cart', function(){
		var product_id = $(this).attr("id");
		var product_name = $('#name'+product_id+'').val();
		var product_price = $('#price'+product_id+'').val();
		var product_quantity = $('#quantity'+product_id).val();
		var action = "add";
		if(product_quantity > 0)
		{
			$.ajax({
				url:"files_php/action.php",
				method:"POST",
				data:{product_id:product_id, product_name:product_name, product_price:product_price, product_quantity:product_quantity, action:action},
				success:function(data)
				{
					load_cart_data();
					//alert("Item has been Added into Cart");
				}
			});
		}
		else
		{
			alert("please Enter Number of Quantity");
		}
	});

	$(document).on('click', '.delete', function(){
		var product_id = $(this).attr("id");
		var action = 'remove';
		if(confirm("Are you sure you want to remove this product?"))
		{
			$.ajax({
				url:"files_php/action.php",
				method:"POST",
				data:{product_id:product_id, action:action},
				success:function()
				{
					load_cart_data();
					$('#cart-popover').popover('hide');
					//alert("Item has been removed from Cart");
				}
			})
		}
		else
		{
			return false;
		}
	});

	$(document).on('click', '#clear_cart', function(){
		var action = 'empty';
		$.ajax({
			url:"files_php/action.php",
			method:"POST",
			data:{action:action},
			success:function()
			{
				load_cart_data();
				$('#cart-popover').popover('hide');
				//alert("Your Cart has been clear");
			}
		});
	});

	$(document).on('click', '#check_out_cart', function(){
		var action = 'check_out';
		$.ajax({
			url:"files_php/action.php",
			method:"POST",
			data:{action:action},
			success:function()
			{
				load_cart_data();
				$('#cart-popover').popover('hide');
				//alert("Your Cart has been cleared");
			}
		});
	});
    
});

</script>