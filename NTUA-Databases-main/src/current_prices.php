<?php session_start();?>
<?php include 'templates/header.php';?>
<?php include 'products_sql.php';?>
<div style = "text-align :center"><font size = "350px">
<?php
$myvar = $_SESSION['prod'];
$newq = 'SELECT * FROM offers WHERE productid =' .$myvar;
$curpr =mysqli_query($conn, $newq);
$price = mysqli_fetch_all($curpr, MYSQLI_ASSOC);
$sqli = 'SELECT * FROM product WHERE productid = '.$myvar; 
$Name =mysqli_query($conn, $sqli);
$names = mysqli_fetch_array($Name, MYSQLI_ASSOC);
echo $names['name'].'('.$names['brand'].')';
?>
</font>
</div>

 <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
            <a href="hist.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Price History</a>
            <a href="current_prices.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Current Prices</a>
            <a href="price_changer.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Price Changer</a>

            </div>
<table  class="table" style = "width:800px; margin-left:auto; margin-right:auto;">
                <thead>
                    <tr>
                        <th scope="col">Price</th>
                        <th scope="col">Store</th>
                        
                    </tr>
                </thead>
                <tbody>
             
                    <?php if (is_array($price) || is_object($price)){
                        foreach ($price as $pr) { ?>
                        <tr>
                            <th scope="row">
                            <?php echo $pr['current_price']?> € </th>
                            <?php $marketa =$pr['storeid'];?>
                           <td> <?php 
                            $erwtg='SELECT * FROM store WHERE storeid='.$marketa;
                            $stores = mysqli_query($conn, $erwtg);
                            $mymarkets = mysqli_fetch_array($stores, MYSQLI_ASSOC);
                            echo $mymarkets['street_name'].' '.$mymarkets['street_number'].', '.$mymarkets['city']?></td>

                        </tr>
                    <?php } } ?>
                </tbody>
                </table>