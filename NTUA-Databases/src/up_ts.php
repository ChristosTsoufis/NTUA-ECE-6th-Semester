<?php session_start();?>
<?php include 'templates/header.php';?>
<p><font size = "350px"><center>Change Timeslot</center></font></p>
<style>
    .line {
    position:absolute;
    width:100%;
    top: 190px;
    }
    .line2 {
    position:absolute;
    width:100%;
    top: 117px;
    }
    .line3 {
    position:absolute;
    width:100%;
    top: 750px;
    }
    
    .msg1 
     {
     margin: 30px auto; 
     padding: 10px; 
     border-radius: 5px; 
     color: #8B0000; 
     background: #ff7d66; 
     border: 1px solid #8B0000;
     width: 50%;
     text-align: center;
     top: 100px;
    }   
    .msg 
    {
    margin: 30px auto; 
    padding: 10px; 
    border-radius: 5px; 
    color: #3c763d; 
    background: #dff0d8; 
    border: 1px solid #3c763d;
    width: 50%;
    text-align: center;
    } 
                
</style>
<?php  
 $conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');
$myvar = $_SESSION['storeid']; 
$_SESSION['storeid'] = $myvar;
?>
<form action = '/up_ts.php' method = "GET">
    <div class="row" style ="width:1200px; margin:0;[B]padding:20px 0;[/B];padding-bottom: 8px;">
        <?php include 'op1.php';?> 
    </div> 
    <div class="col" style = "width:100px;">
    <button type="submit" name="submit" value="submit" class="btn btn-block" style="float: left; color:#e3e6e8; background-color:#354856; text-align:center;">Submit</button>
    </div>
</form>
    
    <?php #var_dump (isset($_GET['submit42']));
     if (isset($_GET['submit'])):
    
   
    $open11 = $_GET['open11'];
    $close11 = $_GET['close11'];
    $open21 = $_GET['open21'];
    $close21 = $_GET['close21'];
    $open31 = $_GET['open31'];
    $close31 = $_GET['close31'];
    $open41 = $_GET['open41'];
    $close41 = $_GET['close41'];
    $open51 = $_GET['open51'];
    $close51 = $_GET['close51'];
    $open61 = $_GET['open61'];
    $close61 = $_GET['close61'];
    $mon = "Monday";
    $tue = "Tuesday";
    $wed = "Wednesday";
    $thu = "Thursday";
    $fri = "Friday";
    $sat = "Saturday";
    ?>

    <?php if (($open11 !== "" and $close11 == "") or ($open11 == "" and $close11 !== "") or ($open21 !== "" and $close21 == "") or ($open21 == "" and $close21 !== "") or ($open31 !== "" and $close31 == "") or ($open31 == "" and $close31 !== "") or ($open41 !== "" and $close41 == "") or ($open41 == "" and $close41 !== "") or ($open51 !== "" and $close51 == "") or ($open51 == "" and $close51 !== "") or ($open61 !== "" and $close61 == "") or ($open61 == "" and $close61 !== "") ):?>
        <div class="msg1">If opening hour is set closing must be too and vice versa!</div>
    
    <?php else: 
     
    $op11 = 'UPDATE opening_hours SET start_time="' .$open11. ':00", end_time="'.$close11. '" WHERE (storeid = '. $myvar.' AND day= "'.$mon.'")';
    mysqli_query($conn, $op11); 
    $op21 = 'UPDATE opening_hours SET start_time="' .$open21. ':00", end_time="'.$close21. '" WHERE (storeid = '. $myvar.' AND day= "'.$tue.'")';
    mysqli_query($conn, $op21);   
    $op31 = 'UPDATE opening_hours SET start_time="' .$open31. ':00", end_time="'.$close31. '" WHERE (storeid = '. $myvar.' AND day= "'.$wed.'")';
    mysqli_query($conn, $op31);
    $op41 = 'UPDATE opening_hours SET start_time="' .$open41. ':00", end_time="'.$close41. '" WHERE (storeid = '. $myvar.' AND day= "'.$thu.'")';
    mysqli_query($conn, $op41);
    $op51 = 'UPDATE opening_hours SET start_time="' .$open51. ':00", end_time="'.$close51. '" WHERE (storeid = '. $myvar.' AND day= "'.$fri.'")';
    mysqli_query($conn, $op51);
    $op61 = 'UPDATE opening_hours SET start_time="' .$open61. ':00", end_time="'.$close61. '" WHERE (storeid = '. $myvar.' AND day= "'.$sat.'")';
    mysqli_query($conn, $op61);
    ?>
     <div class="msg">Changed Timeslot succesfully</div>
    <?php endif?>
    <?php endif ?>
    
    
 