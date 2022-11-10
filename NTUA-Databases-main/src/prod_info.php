<?php session_start();?>
<?php include 'templates/header.php'; ?>
<?php include 'products_sql.php';?>
<div style = "text-align :center"><font size = "350px">
<?php
 
if(isset($_GET['productid']))
{
    $myvar = $_GET['productid']  ;
    $_SESSION['prod'] = $myvar;
    $sqli = 'SELECT * FROM product WHERE productid = '.$myvar; 
    $Name =mysqli_query($conn, $sqli);
    $names = mysqli_fetch_array($Name, MYSQLI_ASSOC);

    $quer = 'SELECT * FROM pricehistory WHERE productid ='. $myvar;
    $hist1 =mysqli_query($conn, $quer);
    $history = mysqli_fetch_all($hist1, MYSQLI_ASSOC);

    $newq = 'SELECT * FROM offers WHERE productid =' .$myvar;
    $curpr =mysqli_query($conn, $newq);
    $price = mysqli_fetch_all($curpr, MYSQLI_ASSOC);

    echo $names['name'].'('.$names['brand'].')';

}

?> 
</font>
</div> 
            
            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
            <a href="hist.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Price History</a>
            <a href="current_prices.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Current Prices</a>
            <a href="price_changer.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Price Changer</a>

            </div>
           
                
               
            </div>
            
        

