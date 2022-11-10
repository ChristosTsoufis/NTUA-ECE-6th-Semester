<?php include 'templates/header.php'; ?>
<?php include 'products_sql.php';?>
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
    top: 390px;
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
<form action = '/cat_mod.php' method="GET" >

<hr class ="line2">
    <div style = "text-align :center">
        <font size = "150px">
            <?php echo 'Create Category'; ?>
        </font>
    </div>
</hr>
    <div class="row" style ="width:500px; margin:0;[B]padding:20px 0;[/B];padding-bottom: 50px;">
        <div class="col " style ="width:500px; margin:0;[B]padding:20px 0;[/B];">
            <label for="inpo">Category Name</label>
            <input type="text" class="form-control" name = "inpo" value = "<?php if (isset($_GET['inpo'])) echo $_GET['inpo'];?>" Placeholder = " ">
        </div>
        
    </div>
    <div class="row" style = "width:150px; margin:0 ;[B]padding:200px 0;[/B];padding-bottom: 50px; padding-left:15px;">
        <button type="submit" name="submit" value="submit" class="btn btn-block" style="float: left; color:#e3e6e8; background-color:#354856; text-align:center;">Add Category</button>
    </div>
<hr class ="line3">
    <div style = "text-align :center">
        <font size = "150px">
            <?php echo 'Delete Category'; ?>
        </font>
    </div>
</hr>
    <div class="form-group col-md-3">
        <label for="inputStore">
            Choose Category
        </label>
            <select id="inputStore" class="form-control" name="catid">
                    <?php foreach ($categ1 as $cat) { ?>
                        <option value="<?php echo $cat['catid'] ?>"> <?php echo $cat['name'];?>
                        </option>
                    <?php } ?>
            </select>
    </div>
    <div class="row" style = "width:200px; margin:0 ;[B]padding:200px 0;[/B];padding-bottom: 50px; padding-left:15px;">
        <button type="submit" name="delete" value="delete" class="btn btn-block" style="float: left; color:#e3e6e8; background-color:#354856; text-align:center;">Delete Category</button>
    </div>

</form>
<?php if(isset($_GET['delete'])):?>
<?php 
    $cat = $_GET['catid'];
    $del = 'DELETE FROM category WHERE catid ='.$cat;
    mysqli_query($conn, $del);
    ?>
    <div class="msg"><?php echo "Category succesfully deleted"?></div>
<?php endif?>
<?php
    $conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');

    if(isset($_GET['submit'])): 
?>
        
        <?php      
                    $newcat = $_GET['inpo'];
                    $categs = 'SELECT * FROM category';
                    $cats1 =  mysqli_query($conn, $categs);
                    $categ1s = mysqli_fetch_all($cats1, MYSQLI_ASSOC); 
                    $maxid = 0;
                    foreach($categ1s as $categ)
                    {
                        if( $categ['catid'] > $maxid)
                        {
                            $maxid = $categ['catid'];
                        }
                    }
                    $neamaxid = $maxid + 1;            
        ?>
            <?php if(is_numeric($newcat)):?>
                <style>
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
                </style>
                <div class="msg1"><?php echo "Only characters please"?></div>
            <?php endif?> <!--if user inserts number-->
            <?php
                $flag = false;
                foreach($categ1s as $categ)
                {               
                                
                if(strcasecmp($categ['name'],$newcat) === 0 )
                {
                    $flag = true;
                }          
                }
            ?>
            <?php if(!(is_numeric($newcat)) && $flag === true):?>
            
            
                <style>
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
                </style>
                <div class="msg1"><?php echo "Category already exists";?></div>
            <?php endif?>
            
            <?php  if(!(is_numeric($newcat)) && $flag === false):?>
            <?php
                $catenew = "INSERT INTO category (catid, name) VALUES (". $neamaxid . ',"'. $newcat.'")'; 
                mysqli_query($conn, $catenew);
            ?>
            
                <style>
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
                <div class="msg"><?php echo "Added ". $newcat;?></div>
            <?php endif?>


    <?php  endif?> <!--if(isset($_GET['submit'])): -->
