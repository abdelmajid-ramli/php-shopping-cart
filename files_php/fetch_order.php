<?php
session_start();

unset($_SESSION["shopping_cart"]);
include('database_connection.php');

$userid=$_SESSION["user_id"];
$query = "";
$query="SELECT * FROM orders where IDUSER = $userid";
//echo $userid;




$statement = $connect->prepare($query);

$statement->execute();
	$result = $statement->fetchAll();
	$output = '<div><h2>order details : </h2>';
	$total=0;
	$i=1;
	foreach($result as $product)
	{
		$total+=$product["TOTAL"];

		$output .= '
			<h3>'.$i.'</h3>
			<h3>product id : '.$product["REF"].'</h3>
			<h3>quantity : '.$product["QTE"].'</h3>
			<h3>total price : '.$product["TOTAL"].'</h3>
		</div>
		';
		$i++;
	}
	$output .= '<h1>total order : '.$total.'</h1>';
	echo $output;  

?>