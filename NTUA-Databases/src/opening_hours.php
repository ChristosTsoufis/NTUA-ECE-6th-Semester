<?php session_start();?>
<?php include 'templates/header.php';?>
<div style = "text-align :center"><font size = "350px">
<?php
$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');
if(isset($_GET['storeid'])){
    $myvar = $_GET['storeid'] ;
    $_SESSION['storeid'] =$myvar;
    
    $sl = 'SELECT * FROM opening_hours WHERE storeid='.$myvar. " ORDER BY FIELD (day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday' ,'Friday', 'Saturday', 'Sunday' )";
    
    $r1t = mysqli_query($conn, $sl);
    $r3g = mysqli_fetch_all($r1t, MYSQLI_ASSOC);

    $sl1 = 'SELECT * FROM store  WHERE storeid ='. $myvar;
    $r1t1 = mysqli_query($conn, $sl1);
    $r3g1 = mysqli_fetch_array($r1t1, MYSQLI_ASSOC);
    
}
echo $r3g1['street_name'].' '.$r3g1['street_number'].', '.$r3g1['city'];
?>
 
</font>
</div>

<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
<a href="up_ts.php" class="btn btn-secondary btn-sm active" role="button"style="background-color:#354856;">Change Timeslot</a>
</div>

<div class="col - 9">
            <table  class="table table-hover" style = "width:1000px; margin-left:auto; margin-right:auto;">
                <thead>
                <tr>
                          
                        <th scope="col">Day</th>      
                        <th scope="col">Opening Time</th>   
                        <th scope="col">Closing Time</th>        
                    </tr>
                </thead>
                <tbody>
                <?php foreach($r3g as $day){?>
                    <tr>
     
                        <th scope="col"><?php echo $day['day'];?></th>   
                        <th scope="col"><?php echo $day['start_time'];?></th> 
                        <th scope="col"><?php echo $day['end_time'];?></th>        
                    </tr>
                   
                <?php } ?>
                </tbody>
                    
            </table>
        </div>
        