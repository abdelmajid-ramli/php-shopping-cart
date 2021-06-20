<?php

ini_set('memory_limit', '1024M');

$pdo = new pdo('mysql:dbname=testcommerce', 'root');
$content = file_get_contents('./products.json');
$initial_products = json_decode($content);

$compteur = 0;
try {
foreach ($initial_products as $product ) {
	$compteur = $compteur + 1;
    if($compteur > 100)
	{
		break;
	}

	$ref = $product->sku;
	$name = $product->name;
	$description = $product->description;
	$image = $product->image;
	$price = $product->price;

	$sql = "INSERT INTO products VALUES(:ref, :name, :description, :price, :image)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([
		'ref' => $ref,
		'name' => $name,
        'description' => $description,
		'price' => $price,
        'image' => $image,
	]);
}

echo 'DONE';
}
catch(PDOException $e)
{
   echo $e->getMessage();
}

?>