<?php session_start();?>

<!-- <script>
    document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll("tr[data-href]");
        rows.forEach(row => {
            row.addEventListener("click", () => {
                window.location.href = row.dataset.href;
            })
        });
    });
</script> -->

<?php

    // connect with the Database
    include('customer-db-connect.php');

    // write query for all customers
    // this symbol : * means that we want all the data 
	$sql = 'SELECT * FROM customer ORDER BY cardid';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

    // fetch the resulting rows as an array
    // so now $customers is an array
	$customers = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// free the $result from memory (good practise)
	mysqli_free_result($result);

	// close connection
	mysqli_close($conn);

	// print_r($customers);

?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <?php include('customer-header.php'); ?>

    <h4 class="center grey-text">Customers</h4>
    
    <!-- this is a materialize class -->
    
    <div class="container">
		<div class="row">

			<?php foreach($customers as $customer): ?>

				<div class="col-sm-6 col-md-3">
					<div class="card z-depth-0">
                        <img src="img/customer-profile.png"class="customer">
						    <div class="card-content center">
							    <h6><?php echo htmlspecialchars($customer['cardid']); ?></h6>
                                <div><?php echo htmlspecialchars($customer['first_name']); ?></div>
                                <div><?php echo htmlspecialchars($customer['last_name']); ?></div>
						    </div>
						    <div class="card-action right-align">
							    <a class="brand-text" href="customer_profile.php?cardid=<?php echo $customer['cardid']. '&seehours=1'?>">More info</a>
						    </div>
					    </div>
				    </div>

            <?php endforeach; ?>

		</div>
	</div>

    <?php include('customer-footer.php'); ?>

</html>