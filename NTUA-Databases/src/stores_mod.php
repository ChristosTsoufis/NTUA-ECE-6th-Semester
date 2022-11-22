<?php include 'templates/header.php';?>
<?php 
$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');
$sql = 'SELECT * FROM store ';
$result = mysqli_query($conn, $sql);
$reg = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql1 = 'SELECT DISTINCT city FROM store ';
$result1 = mysqli_query($conn, $sql1);
$reg1 = mysqli_fetch_all($result1, MYSQLI_ASSOC);

?>
<?php 
    $megisto = 0;
    foreach($reg as $regs)
        {
            if($regs['storeid'] > $megisto)
            {
                $megisto = $regs['storeid'];
            }

        }
    ?>
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
<form action = '/stores_mod.php' method="GET" >
<!--CREATION-->
<hr class ="line2">
    <div style = "text-align :center">
        <font size = "150px">
            <?php echo 'Create Store'; ?>
        </font>
    </div>
</hr>
    <div class="row" style ="width:1200px; margin:0;[B]padding:20px 0;[/B];padding-bottom: 50px;">
        <div class="col " style ="width:500px; margin:0;[B]padding:20px 0;[/B];">
            <label for="inpot">Street Name</label>
            <input type="text" class="form-control" name = "inpot" value = "<?php if (isset($_GET['inpot'])) echo $_GET['inpot'];?>" Placeholder = " ">
        </div>
        <div class="col " style ="width:600px; margin:0;[B]padding:20px 0;[/B];">
            <label for="inpo">Street Number</label>
            <input type="number" min ="1" class="form-control" name = "input" value = "<?php if (isset($_GET['input'])) echo $_GET['input'];?>" Placeholder = " ">
        </div>
        <div class="form-group col-md-3">
            <label for="inputStore">
                Choose City
            </label>
            <select id="inputStore" class="form-control" name="city">
                    <?php foreach ($reg1 as $st1) { ?>
                        <option value="<?php echo $st1['city'] ?>"> <?php echo $st1['city'];?>
                        
                        </option>
                    <?php } ?>
            </select>
        </div>
        <div class="col " style ="width:500px; margin:0;[B]padding:20px 0;[/B];">
            <label for="inpo">Zip</label>
            <input type="number" min ="1" class="form-control" name = "input1" value = "<?php if (isset($_GET['input1'])) echo $_GET['input1'];?>" Placeholder = " ">
        </div>   
        <div class="col " style ="width:500px; margin:0;[B]padding:20px 0;[/B];">
            <label for="inpo">Square Meters </label>
            <input type="number" min ="1" step = ".1"class="form-control" name = "input2" value = "<?php if (isset($_GET['input2'])) echo $_GET['input2'];?>" Placeholder = " ">
        </div>
        
       
    </div>
    <div class="row" style ="width:1200px; margin:0;[B]padding:20px 0;[/B];padding-bottom: 8px;">
        
        <?php include 'op.php';?>

            
    </div>   
   
    <div class="row" style = "width:150px; margin:0 ;[B]padding:200px 0;[/B];padding-bottom: 50px; padding-left:15px;">
        <button type="submit" name="submitS" value="submitS" class="btn btn-block" style="float: left; color:#e3e6e8; background-color:#354856; text-align:center;">Add Store</button>
    </div>
<!--DELETION-->
<hr class ="line3">
    <div style = "text-align :center">
        <font size = "150px">
            <?php echo 'Delete Store'; ?>
        </font>
    </div>
</hr>
    <div class="form-group col-md-3">
        <label for="inputStore">
            Choose Store
        </label>
            <select id="inputStore" class="form-control" name="storeid">
                    <?php foreach ($reg as $st) { ?>
                        <option value="<?php echo $st['storeid'] ?>"> <?php echo $st['street_name'].' '.$st['street_number'].', '.$st['city'];?>
                        
                        </option>
                    <?php } ?>
            </select>
    </div>
    <div class="row" style = "width:200px; margin:0 ;[B]padding:200px 0;[/B];padding-bottom: 50px; padding-left:15px;">
        <button type="submit" name="deleteS" value="deleteS" class="btn btn-block" style="float: left; color:#e3e6e8; background-color:#354856; text-align:center;">Delete Store</button>
    </div>

</form>

<!--

-->
<?php if(isset($_GET['deleteS'])):?>
<?php 
    $stor = $_GET['storeid'];
    

    $del = 'DELETE FROM store WHERE storeid ='.$stor;
    mysqli_query($conn, $del);
    ?>
    <div class="msg"><?php echo "Store succesfully deleted"?></div>
<?php endif?>
<?php if(isset($_GET['submitS'])):
    $sname = $_GET['inpot'];
    $snumb = $_GET['input'];
    $cit = $_GET['city'];
    
    $zip = $_GET['input1'];
    $sm = $_GET['input2'];
    $open1 = $_GET['open1'];
    $close1 = $_GET['close1'];
    $open2 = $_GET['open2'];
    $close2 = $_GET['close2'];
    $open3 = $_GET['open3'];
    $close3 = $_GET['close3'];
    $open4 = $_GET['open4'];
    $close4 = $_GET['close4'];
    $open5 = $_GET['open5'];
    $close5 = $_GET['close5'];
    $open6 = $_GET['open6'];
    $close6 = $_GET['close6'];
    $mon = "Monday";
    $tue = "Tuesday";
    $wed = "Wednesday";
    $thu = "Thursday";
    $fri = "Friday";
    $sat = "Saturday";
    
    
    
?>
<?php
     $flag = false;
        foreach($reg as $re)
            {               
                                
                if((strcasecmp($re['street_name'],$sname) === 0) and (strcasecmp($re['city'], $cit) === 0) and (strcasecmp($re['street_number'] , $snumb) === 0) and (strcasecmp($re['zip'], $zip) ===0 ))
                {
                    $flag = true;
                }          
            }
            
                
#    and (strcasecmp($re['zip'], $zip) ===0 )
            ?>

    <?php if($open1=="" or $open2 == "" or $open3 =="" or $open4 == "" or $open5 == "" or $open6 == "" or $close1 == "" or $close2 == "" or $close3 == "" or $close4 == "" or $close5 =="" or $close6 =="" ):?>
    <div class="msg1">Opening or closing hours can't be empty</div>
    <?php endif?>
    <?php if($flag === true):?>
        <div class="msg1"><?php echo "Store at ".$sname.','. $snumb.','.$cit. ' already exists';?></div>
    <?php endif?>
    <?php if(is_numeric($sname)):?>
        <div class="msg1">Street Name field requires only characters</div>
    <?php endif?>
    <?php if($snumb === ''):?>
        <div class="msg1">Street Number field can't be empty</div>
    <?php endif?>
    <?php if($zip === ''):?>
        <div class="msg1">Zip field can't be empty</div>
    <?php endif?>
    <?php if($sm === ''):?>
        <div class="msg1">Square meters field can't be empty</div>
    <?php endif?>
    <?php if(!(is_numeric($sname)) and $snumb !== '' and $zip !== '' and $sm !== '' and $flag === false and !($open1=="" or $open2 == "" or $open3 =="" or $open4 == "" or $open5 == "" or $open6 == "" or $close1 == "" or $close2 == "" or $close3 == "" or $close4 == "" or $close5 =="" or $close6 =="" )):
        $neoid = $megisto + 1;
        $insstore = "INSERT INTO store (storeid, street_name,street_number,city,zip,sq_meters) VALUES (". $neoid. ',"'.$sname.'",'. $snumb.',"'.$cit.'",'.$zip. ','.$sm.')'; 
        mysqli_query($conn, $insstore);
        $op1 = "INSERT INTO opening_hours (storeid, day,start_time,end_time) VALUES (". $neoid. ',"'.$mon.'","'.$open1. ':00","'.$close1.':00")'; 
        mysqli_query($conn, $op1);
        $op2 = "INSERT INTO opening_hours (storeid, day,start_time,end_time) VALUES (". $neoid. ',"'.$tue.'","'.$open2. ':00","'.$close2.':00")'; 
        mysqli_query($conn, $op2);
        $op3 = "INSERT INTO opening_hours (storeid, day,start_time,end_time) VALUES (". $neoid. ',"'.$wed.'","'.$open3. ':00","'.$close3.':00")'; 
        mysqli_query($conn, $op3);
        $op4 = "INSERT INTO opening_hours (storeid, day,start_time,end_time) VALUES (". $neoid. ',"'.$thu.'","'.$open4. ':00","'.$close4.':00")'; 
        mysqli_query($conn, $op4);
        $op5 = "INSERT INTO opening_hours (storeid, day,start_time,end_time) VALUES (". $neoid. ',"'.$fri.'","'.$open5. ':00","'.$close5.':00")'; 
        mysqli_query($conn, $op5);
        $op6 = "INSERT INTO opening_hours (storeid, day,start_time,end_time) VALUES (". $neoid. ',"'.$sat.'","'.$open6. ':00","'.$close6.':00")'; 
        mysqli_query($conn, $op6);


    ?>
        <div class="msg"><?php echo "Added store at ".$sname.','. $snumb.','.$cit;?></div>
    <?php endif?>
   

<?php endif?>