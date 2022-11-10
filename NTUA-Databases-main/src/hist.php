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
$quer = 'SELECT * FROM pricehistory WHERE productid ='. $myvar;
$hist1 =mysqli_query($conn, $quer);
$history = mysqli_fetch_all($hist1, MYSQLI_ASSOC);
?>
</font>
</div>
<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
            <a href="hist.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Price History</a>
            <a href="current_prices.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Current Prices</a>
            <a href="price_changer.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Price Changer</a>

</div>

<table  class="table" style = "width:1000px;" >
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Price</th>
                        <th scope="col">Store</th>
                        
                    </tr>
                </thead>
                <tbody>
             
                    <?php if (is_array($history) || is_object($history)){
                        foreach ($history as $hist) { ?>
                        <tr>
                            <th scope="row"><?php echo $hist['date']?></th>
                            <td><?php echo $hist['newprice']?> € </td>
                            <?php $market =$hist['storeid'];?>
                           <td> <?php 
                            $erwt='SELECT * FROM store WHERE storeid='.$market;
                            $store = mysqli_query($conn, $erwt);
                            $mymarket = mysqli_fetch_array($store, MYSQLI_ASSOC);
                            echo $mymarket['street_name'].' '.$mymarket['street_number'].', '.$mymarket['city']?></td>

                        </tr>
                    <?php } }?>
                    
                </tbody>
                    
            </table>