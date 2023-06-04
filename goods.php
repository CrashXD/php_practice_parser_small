<?php
require_once "connection.php";

$sql = 'SELECT * FROM products WHERE price < 1000';
$query = $database->query($sql);

$products = $query->fetchAll();

foreach ($products as $product) { ?>
    <h3><?= $product['title'] ?></h3> 
    <p><?= $product['price'] ?></p> 
    <img src="<?= $product['image'] ?>" style="width: 200px; height: 200px; object-fit: contain;" alt="">
<?php }