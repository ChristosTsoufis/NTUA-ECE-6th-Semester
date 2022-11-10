<?php
$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');

    if (!$conn){
        echo 'Bad connection:' . mysqli_connect_error();
    }

    $sql = 'SELECT * FROM Product';

    $result = mysqli_query($conn, $sql);

    $prods = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>