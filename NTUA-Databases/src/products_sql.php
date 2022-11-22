<?php
$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');

if (!$conn) {
    echo 'Bad connection:' . mysqli_connect_error();
}

$categ = 'SELECT * FROM category';

$cat1 =  mysqli_query($conn, $categ);

$categ1 = mysqli_fetch_all($cat1, MYSQLI_ASSOC);
/*It is used in price_changer.php*/
$sql1 = 'SELECT * FROM store ORDER BY city ASC';
$result1 = mysqli_query($conn, $sql1);
$stores = mysqli_fetch_all($result1, MYSQLI_ASSOC);

?>

