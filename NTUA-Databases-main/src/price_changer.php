<?php session_start();?>
<?php include 'products_sql.php';?>
<?php include 'templates/header.php';?>
<?php function numberOfDecimals($value)
{
    if ((int)$value == $value)
    {
        return 0;
    }
    else if (! is_numeric($value))
    {
        // throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
        return false;
    }

    return strlen($value) - strrpos($value, '.') - 1;
}?>
<div style = "text-align :center"><font size = "350px">
<?php
$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');
if (isset($_GET['submit'])){
    
    $myvar = $_SESSION['prod']; #productid
    $mystore = $_GET['storeid'];
    $_SESSION['val'] = $mystore;

    $val = $_GET['inp']; #input
    
    
    $_SESSION['timh'] = $val;
    
    $sq = 'SELECT * FROM pricehistory WHERE ( storeid= '.$mystore.' AND productid = '. $myvar.')';
    $sq1 =mysqli_query($conn, $sq);
    $dates = mysqli_fetch_all($sq1, MYSQLI_ASSOC);
    $magazia='SELECT * FROM store WHERE storeid='.$mystore;
    $storia = mysqli_query($conn, $magazia);
    $mymagazia = mysqli_fetch_array($storia, MYSQLI_ASSOC);
}
 #$date = date("Y/m/d");
 
 $myvar = $_SESSION['prod'];
 $sqli = 'SELECT * FROM product WHERE productid = '.$myvar; 
 $Name =mysqli_query($conn, $sqli);
 $names = mysqli_fetch_array($Name, MYSQLI_ASSOC);
 echo $names['name'].'('.$names['brand'].')';
?>
 
 
</font>
</div>
<div class="btn-group btn-group-sm" role="group" aria-label="Basic example" >
            <a href="hist.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Price History</a>
            <a href="current_prices.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Current Prices</a>
            <a href="price_changer.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Price Changer</a>

            </div>
   
<form action = '/price_changer.php' method="GET" >
<div class="row">
    <div class="col">
        <label for="inp">Insert New Price</label>
        <input type="text" class="form-control" name = "inp" value = "<?php if (isset($_GET['inp'])) echo $_GET['inp'];?>" Placeholder = "Insert New Price">
    </div>
    <div class="form-group col-md-3">
                <label for="inputStore">Pick Store</label>
                <select id="inputStore" class="form-control" name="storeid">
                    <?php foreach ($stores as $stor) { ?>
                        <option value="<?php echo $stor['storeid'] ?>"> <?php echo $stor['street_name'] . ' ' . $stor['street_number'] . ', ' . $stor['city'] ?>
                        </option>
                    <?php } ?>
                </select>
    </div>
 </div>
 
 
 <div class="col" style = "width:100px;">
 <button type="submit" name="submit" value="submit" class="btn btn-block" style="float: left; color:#e3e6e8; background-color:#354856; text-align:center;">Submit</button>
 </div>
</form>

<!-- finds max date -->

 <?php
 $conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');

 if(isset($_GET['submit']))
 {
    $sq = 'SELECT * FROM pricehistory WHERE ( storeid= '.$mystore.' AND productid = '. $myvar.')';
    $sq1 =mysqli_query($conn, $sq);
    $dates = mysqli_fetch_all($sq1, MYSQLI_ASSOC);     
     foreach ($dates as $dat) { 
         $max = 0;
         if ($dat['date']> $max) 
        {
         $max = $dat['date'];
         $kati = $dat['issales'];
        }
 }
 
 
$hmer = date("Y-m-d");
?>
<?php 
  $conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');
  $of='SELECT * FROM offers';
  $ofi = mysqli_query($conn, $of);
  $ergotelhs = mysqli_fetch_all($ofi, MYSQLI_ASSOC);
  $store  = $_GET['storeid'];
  ?>
<?php 
$flag = false;
foreach($ergotelhs as $beth)
{
    if($beth['storeid']===$store and $beth['productid'] === $myvar)
    ##if store and product combo already exists
    {
        $flag = true;
    }
    
}
?>


<!-- if the input is string-->
    <?php if (!is_numeric($val) ):?>
        <style>.msg1 {
    margin: 30px auto; 
    padding: 10px; 
    border-radius: 5px; 
    color: #8B0000; 
    background: #ff7d66; 
    border: 1px solid #8B0000;
    width: 50%;
    text-align: center;
}</style>
<div class="msg1">Do not use characters. A number with maximum two decimals is required!</div>
<?php endif?>
<!--if user inserts a number with more than two decimals -->
<?php 

if(is_numeric($val) and numberOfDecimals($val) !== 2 ):?>
    <style>.msg1 {
    margin: 30px auto; 
    padding: 10px; 
    border-radius: 5px; 
    color: #8B0000; 
    background: #ff7d66; 
    border: 1px solid #8B0000;
    width: 50%;
    text-align: center;
}</style>
<div class="msg1">Τwo decimals please!</div>
<?php endif?>

<?php if(($max === $hmer) and is_numeric($val) and numberOfDecimals($val) === 2):?>
<!--if date is ok and the input is a number-->
<style>.msg1 {
    margin: 30px auto; 
    padding: 10px; 
    border-radius: 5px; 
    color: #8B0000; 
    background: #ff7d66; 
    border: 1px solid #8B0000;
    width: 50%;
    text-align: center;
}</style>
<div class="msg1"><?php echo 'Price already changed today at '.$mymagazia['street_name'].' '.$mymagazia['street_number'].', '.$mymagazia['city'];?></div>
    
<?php endif ?>
<?php if(($max !== $hmer) and is_numeric($val) and numberOfDecimals($val) == 2)
{
    
        $up = 'UPDATE offers SET current_price = ' .$val .' WHERE (productid = '. $myvar.' AND storeid= '.$mystore.')';
        mysqli_query($conn, $up);
    
    
    #echo $myvar,'####', $mystore, '#####',$val;
    
    $kiouri = "INSERT INTO pricehistory (storeid, productid, date, issales, newprice) VALUES (". $mystore . ','. $myvar. ',"' . $hmer . '",' . $kati . ','. $val.')';
}?>

    <?php 
    $conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');
    
    if(($max !== $hmer)and is_numeric($val) and numberOfDecimals($val) == 2 and mysqli_query($conn, $kiouri)):?>
        <style>.msg {
    margin: 30px auto; 
    padding: 10px; 
    border-radius: 5px; 
    color: #3c763d; 
    background: #dff0d8; 
    border: 1px solid #3c763d;
    width: 50%;
    text-align: center;
}</style>
<div class="msg"><?php echo 'Price changed successfully at '.$mymagazia['street_name'].' '.$mymagazia['street_number'].', '.$mymagazia['city']; ?></div>
    <?php endif?>
  
<?php  

}
 

?>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll("tr[data-href]");
        rows.forEach(row => {
            row.addEventListener("click", () => {
                window.location.href = row.dataset.href;
            })
        });
    });
</script>
