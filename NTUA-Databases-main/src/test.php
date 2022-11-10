<?php session_start();?>
<?php include 'templates/header.php'; ?>

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

<?php 
if (isset($_GET['submit']))
{
$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');    
$myvar = $_SESSION['prod'];
$mystore = $_SESSION['val'];
$val = $_SESSION['timh'];
echo $myvar;
echo $mystore;
echo $val;
$up = 'UPDATE offers SET current_price = ' .$val .' WHERE (productid = '. $myvar.' AND storeid= '.$mystore.')';
    #echo $myvar,'####', $mystore, '#####',$val;
    mysqli_query($conn, $up);
}
?>

<div class="btn-group btn-group-lg" role="group" aria-label="Basic example">
            <a href="hist.php" class="btn btn-secondary btn-lg active" role="button"style="background-color:#354856;">Price History</a>
            <a href="current_prices.php" class="btn btn-secondary btn-lg active" role="button"style="background-color:#354856;">Current Prices</a>
            <a href="price_changer.php" class="btn btn-secondary btn-lg active" role="button"style="background-color:#354856;">Price Changer</a>

            </div>