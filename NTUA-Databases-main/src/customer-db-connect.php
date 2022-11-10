<?php

    // connect to database
    $conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');

    // check connection
    if (!$conn) {
        echo 'Bad connection:' . mysqli_connect_error();
    }

?>