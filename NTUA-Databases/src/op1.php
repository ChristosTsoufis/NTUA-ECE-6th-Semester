<?php session_start();?>
<?php $myvar = $_SESSION['storeid'];

$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');
$sql = 'SELECT * FROM opening_hours WHERE storeid='.$myvar;
$result = mysqli_query($conn, $sql);
$reg = mysqli_fetch_all($result, MYSQLI_ASSOC);?>
<?php foreach($reg as $d){
  if($d['day'] === "Monday"){$mono = $d['start_time']; $monc = $d['end_time'];}
  if($d['day'] === "Tuesday"){$tueo = $d['start_time']; $tuec = $d['end_time'];}
  if($d['day'] === "Wednesday"){$wedo = $d['start_time']; $wedc = $d['end_time'];}
  if($d['day'] === "Thursday"){$thuo = $d['start_time']; $thuc = $d['end_time'];}
  if($d['day'] === "Friday"){$frio = $d['start_time']; $fric = $d['end_time'];}
  if($d['day'] === "Saturday"){$sato = $d['start_time']; $satc = $d['end_time'];}
  
}?>
<table class="table table-sm" style = "width:1000px; margin-left:auto; margin-right:auto;">
  <thead>
    <tr>
      <th scope="col">Day</th>
      <th scope="col">Opening Hour</th>
      <th scope="col">Closing Hour</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      
      <td>Monday</td>
      <td><input type="time" class="form-control" name = "open11" value = "<?php echo $mono; if (isset($_GET['open11'])) echo $_GET['open11'];?>" ></td>
      <td><input type="time" class="form-control" name = "close11" value = "<?php echo $monc; if (isset($_GET['close11'])) echo $_GET['close11'];?>" Placeholder = " "></td>
    </tr>
    <tr>
      <td>Tuesday</td>
      <td><input type="time" class="form-control" name = "open21" value = "<?php echo $tueo; if (isset($_GET['open21'])) echo $_GET['open21'];?>" Placeholder = " "></td>
      <td><input type="time" class="form-control" name = "close21" value = "<?php echo $tuec; if (isset($_GET['close21'])) echo $_GET['close21'];?>" Placeholder = " "></td>
    </tr>
    <tr>

      <td >Wednesday</td>
      <td><input type="time" class="form-control" name = "open31" value = "<?php echo $wedo; if (isset($_GET['open31'])) echo $_GET['open31'];?>" Placeholder = " "></td>
      <td><input type="time" class="form-control" name = "close31" value = "<?php echo $wedc; if (isset($_GET['close31'])) echo $_GET['close31'];?>" Placeholder = " "></td>

    </tr>
    <tr>
      
      <td>Thursday</td>
      <td><input type="time" class="form-control" name = "open41" value = "<?php echo $thuo; if (isset($_GET['open41'])) echo $_GET['open41'];?>" Placeholder = " "></td>
      <td><input type="time" class="form-control" name = "close41" value = "<?php echo $thuc; if (isset($_GET['close41'])) echo $_GET['close41'];?>" Placeholder = " "></td>
    </tr>
    <tr>
      <td>Friday</td>
      <td><input type="time" class="form-control" name = "open51" value = "<?php echo $frio; if (isset($_GET['open51'])) echo $_GET['open51'];?>" Placeholder = " "></td>
      <td><input type="time" class="form-control" name = "close51" value = "<?php echo $fric; if (isset($_GET['close51'])) echo $_GET['close51'];?>" Placeholder = " "></td>
    </tr>
    <tr>

      <td >Saturday</td>
      <td><input type="time" class="form-control" name = "open61" value = "<?php echo $sato; if (isset($_GET['open61'])) echo $_GET['open61'];?>" Placeholder = " "></td>
      <td><input type="time" class="form-control" name = "close61" value = "<?php echo $satc; if (isset($_GET['close61'])) echo $_GET['close61'];?>" Placeholder = " "></td>

    </tr>
    
    

  </tbody>
</table>




