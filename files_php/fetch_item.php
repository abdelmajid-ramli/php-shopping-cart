<?php


include('database_connection.php');

$record_per_page = 8;  
$page = '';

if(isset($_POST["page"]))  
{  
	 $page = $_POST["page"];  
}  
else  
{  
	 $page = 1;  
} 

$cat_set=false;
$category='';
if(isset($_POST["cat"]))  
{  
	 $category = $_POST["cat"];
	 $cat_set=true;

} 




$start_from = ($page - 1)*$record_per_page; 

$query = "";
if($cat_set){
	$query="SELECT * FROM ( SELECT products.ref, products.name, products.description, products.price, products.image, ROW_NUMBER() OVER(PARTITION BY products.ref ORDER BY products.price DESC) rn FROM products INNER JOIN product_cat ON products.ref = product_cat.REF INNER JOIN category on product_cat.ID = category.ID WHERE category.NAME LIKE '%$category%' ORDER BY price DESC LIMIT $start_from, $record_per_page) a WHERE rn = 1 ";
	$page_query = "SELECT * FROM ( SELECT products.ref, products.name, products.description, products.price, products.image, ROW_NUMBER() OVER(PARTITION BY products.ref ORDER BY products.price DESC) rn FROM products INNER JOIN product_cat ON products.ref = product_cat.REF INNER JOIN category on product_cat.ID = category.ID WHERE category.NAME LIKE '%$category%' LIMIT $start_from, $record_per_page) a WHERE rn = 1";
}else{
	$query = "SELECT * FROM products ORDER BY price DESC LIMIT $start_from, $record_per_page";
	$page_query = "SELECT * FROM products ORDER BY price DESC"; 
}



$statement = $connect->prepare($query);

if($statement->execute())
{
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $product)
	{
		$output .= '
		<div class="col-md-3" style="margin-top:12px;">  
            <div style="border:3px solid #888; background-color:#f8f8f8; border-radius:8px; padding:16px; height:300px; position:relative;" align="center">
            	<img src="'.$product["image"].'" class="img-responsive" style="width: 100px" /><br />
            	<h5 class="text-dark">'.$product["name"].'</h5>
            	<h4 class="text-danger" style=" font-weight: bold;">$ '.$product["price"] .'</h4>
            	<input type="text" name="quantity" id="quantity' . $product["ref"] .'" class="form-control" value="1"" style="position:absolute; right: 0px; bottom:40px;" />
            	<input type="hidden" name="hidden_name" id="name'.$product["ref"].'" value="'.$product["name"].'" />
            	<input type="hidden" name="hidden_price" id="price'.$product["ref"].'" value="'.$product["price"].'" />
            	<input type="button" name="add_to_cart" id="'.$product["ref"].'" style="margin-top:5px; position:absolute; right: 0; bottom:0;" class="btn btn-outline-success form-control add_to_cart" value="Add to Cart" />
            </div>
		</div>
		';
	}


	$connect2 = mysqli_connect("localhost", "root", "", "testcommerce");  
	$page_result = mysqli_query($connect2, $page_query);  
	$total_records = mysqli_num_rows($page_result);  
	$total_pages = ceil($total_records/$record_per_page);  
	if(!($total_pages<=1)){
		$output .= '<p>.</p><p>.</p><div align="center">';
		for($i=1; $i<=$total_pages; $i++)  
		{  
		$output .= "<span class='pagination_link' style='cursor:pointer; padding:6px; border:1px solid #ccc;' id='".$i."'>".$i."</span>"; 
		}  
		$output .= '</div><br /><br />';
	}
	 
	echo $output;  
}


?>