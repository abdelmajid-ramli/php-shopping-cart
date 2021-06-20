<?php

//action.php

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "add")
	{
		if(isset($_SESSION["shopping_cart"]))
		{
			$is_available = 0;
			foreach($_SESSION["shopping_cart"] as $keys => $values)
			{
				if($_SESSION["shopping_cart"][$keys]['product_id'] == $_POST["product_id"])
				{
					$is_available++;
					$_SESSION["shopping_cart"][$keys]['product_quantity'] = $_SESSION["shopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"];
				}
			}
			if($is_available == 0)
			{
				$item_array = array(
					'product_id'               =>     $_POST["product_id"],  
					'product_name'             =>     $_POST["product_name"],  
					'product_price'            =>     $_POST["product_price"],  
					'product_quantity'         =>     $_POST["product_quantity"]
				);
				$_SESSION["shopping_cart"][] = $item_array;
			}
		}
		else
		{
			$item_array = array(
				'product_id'               =>     $_POST["product_id"],  
				'product_name'             =>     $_POST["product_name"],  
				'product_price'            =>     $_POST["product_price"],  
				'product_quantity'         =>     $_POST["product_quantity"]
			);
			$_SESSION["shopping_cart"][] = $item_array;
		}
	}

	if($_POST["action"] == 'remove')
	{
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
			if($values["product_id"] == $_POST["product_id"])
			{
				unset($_SESSION["shopping_cart"][$keys]);
			}
		}
	}
	if($_POST["action"] == 'empty')
	{
		unset($_SESSION["shopping_cart"]);
	}
	if($_POST["action"] == 'check_out')
	{
			$user_id=$_SESSION["user_id"];
			$products_number = sizeof($_SESSION["shopping_cart"]);

			for($i=0;$i<$products_number ;$i++){
				$product_id = $_SESSION["shopping_cart"][$i]["product_id"];
				$product_qte = $_SESSION["shopping_cart"][$i]["product_quantity"];
				$product_price = $_SESSION["shopping_cart"][$i]["product_price"];

				$total=$product_qte*$product_price;
				$connect2 = mysqli_connect("localhost", "root", "", "testcommerce");  
				$query = "INSERT INTO orders VALUES ($user_id,$product_id,$total,$product_qte)";
				mysqli_query($connect2, $query);
			}

			//unset($_SESSION["shopping_cart"]);
			header("Location: http://localhost/Ramli-commerce/login.php");
	}
}

?>