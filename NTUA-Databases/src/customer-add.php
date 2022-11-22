<?php 
    
    // connect with the Database
    include('customer-db-connect.php');

    //intialize them as empty strings
    $first_name = $last_name = $card_exp_date = $city = $street_name = $street_number =
    $zip = $apt_floor = $number_of_kids = $date_of_birth = $relationship_status = '';

    //initialize with a random cardid
    $cardid = rand(1000000000000, 1000000000000000000);

    //initialize them with zero
    $current_points = $points_redeemed = 0;

    
    //a variable with the strings that are wrong
    $errors = array('cardid' => '', 'first_name' => '', 'last_name' => '', 'card_exp_date' => '', 'current_points' => '', 'points_redeemed' => '', 'city' => '', 'street_name' => '',
                    'street_number' => '', 'zip' => '', 'apt_floor' => '', 'number_of_kids' => '', 'date_of_birth' => '', 'relationship_status' => ''); 

	if(isset($_POST['submit'])){
        
        // check cardid
        if(empty($_POST['cardid'])){
            $errors['cardid'] = 'A CardID is required <br />';
        } else{
            $cardid = $_POST['cardid'];
			if(!preg_match('/^[0-9\s]+$/', $cardid)){
				$errors['cardid'] = 'CardID must be numbers only';
			}
        }

        // check first_name
        if(empty($_POST['first_name'])){
            $errors['first_name'] = 'A First Name is required <br />';
        } else{
            $first_name = $_POST['first_name'];
			if(!preg_match('/^[a-zA-Z\s]+$/', $first_name)){
				$errors['first_name'] = 'First Name must be letters and spaces only';
			}
        }

        // check last_name
        if(empty($_POST['last_name'])){
            $errors['last_name'] = 'A Last Name is required <br />';
        } else{
            $last_name = $_POST['last_name'];
			if(!preg_match('/^[a-zA-Z\s]+$/', $last_name)){
				$errors['last_name'] =  'Last Name must be letters and spaces only';
			}
        }

        // check card_exp_date
        if(empty($_POST['card_exp_date'])){
            $errors['card_exp_date'] = 'A Card Expiration Date is required <br />';
        } else{
            $card_exp_date = $_POST['card_exp_date'];
			if(!preg_match('/^([0-9\s]+)(-\s*[0-9\s]*)*$/', $card_exp_date)){
				$errors['card_exp_date'] =  'Card Expiration Date must be numbers and dashes only (e.g.: 2020-03-23)';
			}
        }

        // check current_points
        if(empty($_POST['current_points'])){
            $errors['current_points'] = 'Current Points are required <br />';
        } else{
            $current_points = $_POST['current_points'];
			if(!preg_match('/^[0-9\s]+$/', $current_points)){
                $errors['current_points'] =  'Current Points must be numbers only';
			}
        }

        // check points_redeemed
        if(empty($_POST['points_redeemed'])){
            $errors['points_redeemed'] = 'Points that have been redeemed are required <br />';
        } else{
            $points_redeemed = $_POST['points_redeemed'];
			if(!preg_match('/^[0-9\s]+$/', $points_redeemed)){
                $errors['points_redeemed'] =  'Points that have been redeemed must be numbers only';
			}
        }

        // check city
        if(empty($_POST['city'])){
            $errors['city'] =  'A City required <br />';
        } else{
            $city = $_POST['city'];
			if(!preg_match('/^[a-zA-Z\s]+$/', $city)){
                $errors['city'] =  'City must be letters and spaces only';
			}
        }

        // check street_name
        if(empty($_POST['street_name'])){
            $errors['street_name'] = 'A Street Name required <br />';
        } else{
            $street_name = $_POST['street_name'];
			if(!preg_match('/^[a-zA-Z\s]+$/', $street_name)){
                $errors['street_name'] =  'Street Name must be letters and spaces only';
			}
        }

        // check street_number
        if(empty($_POST['street_number'])){
            $errors['street_number'] = 'A Street Number required <br />';
        } else{
            $street_number = $_POST['street_number'];
			if(!preg_match('/^[0-9\s]+$/', $street_number)){
                $errors['street_number'] =  'Street Number must be numbers only';
			}
        }

        // check zip
        if(empty($_POST['zip'])){
            $errors['zip'] =  'A Zip required <br />';
        } else{
            $zip = $_POST['zip'];
			if(!preg_match('/^[0-9\s]+$/', $zip)){
                $errors['zip'] =  'Zip must be numbers only';
			}
        }

        // check apt_floor
        if(empty($_POST['apt_floor'])){
            $errors['apt_floor'] = 'An Apartment Floor required <br />';
        } else{
            $apt_floor = $_POST['apt_floor'];
			if(!preg_match('/^[0-9\s]+$/', $apt_floor)){
                $errors['apt_floor'] = 'Apartment Floor must be numbers only';
			}
        }

        // check number_of_kids
        if(empty($_POST['number_of_kids'])){
            $errors['number_of_kids'] = 'A Number of Kids required <br />';
        } else{
            $number_of_kids = $_POST['number_of_kids'];
			if(!preg_match('/^[0-9\s]+$/', $number_of_kids)){
                $errors['number_of_kids'] =  'Number of Kids must be numbers only';
			}
        }

        // check date_of_birth
        if(empty($_POST['date_of_birth'])){
            $errors['date_of_birth'] = 'A Date of Birth required <br />';
        } else{
            $date_of_birth = $_POST['date_of_birth'];
			if(!preg_match('/^([0-9\s]+)(-\s*[0-9\s]*)*$/', $date_of_birth)){
				$errors['date_of_birth'] =  'Date of Birth must be numbers and dashes only (e.g.: 1997-10-18)';
			}
        }

        // check relationship_status
        if(empty($_POST['relationship_status'])){
            $errors['relationship_status'] = 'A Relationship Status required <br />';
        } else{
            $relationship_status = $_POST['relationship_status'];
			if(!preg_match('/^[a-zA-Z\s]+$/', $relationship_status)){
                $errors['relationship_status'] =  'Relationship Status must be letters and spaces only';
			}
        }

        // redirection
        //if no errors = False, errors = True 
        if(array_filter($errors)){ 
            //echo 'errors in form';
		} else {

            // escape sql chars
            $cardid = mysqli_real_escape_string($conn, $_POST['cardid']);
			$first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
			$last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
            $card_exp_date = mysqli_real_escape_string($conn, $_POST['card_exp_date']);
            $current_points = mysqli_real_escape_string($conn, $_POST['current_points']);
            $points_redeemed = mysqli_real_escape_string($conn, $_POST['points_redeemed']);
            $city = mysqli_real_escape_string($conn, $_POST['city']);
            $street_name = mysqli_real_escape_string($conn, $_POST['street_name']);
            $street_number = mysqli_real_escape_string($conn, $_POST['street_number']);
            $zip = mysqli_real_escape_string($conn, $_POST['zip']);
            $apt_floor = mysqli_real_escape_string($conn, $_POST['apt_floor']);
            $number_of_kids = mysqli_real_escape_string($conn, $_POST['number_of_kids']);
            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
            $relationship_status = mysqli_real_escape_string($conn, $_POST['relationship_status']);
            
            // create sql
			$sql = "INSERT INTO customer(cardid, first_name, last_name, card_exp_date, current_points, points_redeemed,
            city, street_name, street_number, zip, apt_floor, number_of_kids, date_of_birth, relationship_status) 
            VALUES('$cardid', '$first_name', '$last_name', '$card_exp_date', '$current_points', '$points_redeemed', '$city',
            '$street_name', '$street_number', '$zip', '$apt_floor', '$number_of_kids', '$date_of_birth', '$relationship_status')";

            // save to database and check
			if(mysqli_query($conn, $sql)){
				// success
				header('Location: customers.php');
			} else {
                // error
				echo 'query error: '. mysqli_error($conn);
			}
            
            
		}

	} // end POST check
    
?>

<!DOCTYPE html> 
<html> 
	
    <?php include('templates/header.php'); ?>
    
    <?php include('customer-header.php'); ?>

	<section class="container grey-text">
        <h4 class="center">Add new Customer</h4>
        
        <!-- below instead of using this file in action, we can use a SUPERGLOBAL -->
        <!-- with this technic, we can update the file (its name) and it will still work due to the superglobal _SERVER -->
		<form class="white" action="customer-add.php" method="POST">
        <legend><h2 style="font-family:Segoe UI;">Customer's Personal Details: </h2></legend>
            
            <!-- htmlspecialchars() is used to prevent XSS Attacks -->

            <!-- CardID -->
            <label>CardID: </label>
            <input type="text" name="cardid" 
            pattern="[0-9]{0,20}" 
			title="This should be a number with up to 20 digits."
            value="<?php echo htmlspecialchars($cardid) ?>">
            <div class="red-text"><?php echo $errors['cardid']; ?></div>

            <!-- First Name -->
            <label>First Name:</label>
            <input type="text" name="first_name" 
            pattern="[a-zA-Z]{1,20}" 
            title="Please enter more than 1 and less than 20 letters"
            value="<?php echo htmlspecialchars($first_name) ?>">
            <div class="red-text"><?php echo $errors['first_name']; ?></div>
            
            <!-- Last Name -->
            <label>Last Name:</label>
            <input type="text" name="last_name" 
            pattern="[a-zA-Z]{1,30}" 
			title="Please enter more than 1 and less than 30 letters"
            value="<?php echo htmlspecialchars($last_name) ?>">
            <div class="red-text"><?php echo $errors['last_name']; ?></div>

            <!-- Card Expiration Date -->
            <label>Card Expiration Date:</label>
            <h6>The appropriate format is: YYYY-MM-DD</h6>
            <input type="text" name="card_exp_date" 
            pattern=".{10,}"
			title="Please enter a valid date with YYYY-MM-DD format"
            value="<?php echo htmlspecialchars($card_exp_date) ?>">
            <div class="red-text"><?php echo $errors['card_exp_date']; ?></div>
            
            <!-- Current Points -->
            <label>Current Points: </label>
            <input type="text" name="current_points" 
            pattern="[0-9]{0,5}" 
			title="This should be a number with up to 5 digits."
            value="<?php echo htmlspecialchars($current_points) ?>">
            <div class="red-text"><?php echo $errors['current_points']; ?></div>

            <!-- Points Redeemed -->
            <label>Points Redeemed: </label>
            <input type="text" name="points_redeemed" 
            pattern="[0-9]{0,11}" 
			title="This should be a number with up to 11 digits."
            value="<?php echo htmlspecialchars($points_redeemed) ?>">
            <div class="red-text"><?php echo $errors['points_redeemed']; ?></div>

            <!-- City-->
            <label>City:</label>
            <input type="text" name="city" 
            pattern="[a-zA-Z]{1,30}"
			title="Enter more than 3 and less than 30 letters"
            value="<?php echo htmlspecialchars($city) ?>">
            <div class="red-text"><?php echo $errors['city']; ?></div>

            <!-- Street Name-->
            <label>Street Name:</label>
            <input type="text" name="street_name" 
            pattern="[a-zA-Z]{1,30}"
			title="Enter more than 3 and less than 30 letters"
            value="<?php echo htmlspecialchars($street_name) ?>">
            <div class="red-text"><?php echo $errors['street_name']; ?></div>

            <!-- Street Number -->
            <label>Street Number: </label>
            <input type="text" name="street_number" 
            pattern="[0-9]{0,3}"
			title="This should be a number with up to 3 digits."
            value="<?php echo ($street_number) ?>">
            <div class="red-text"><?php echo $errors['street_number']; ?></div>

            <!-- Zip -->
            <label>Zip: </label>
            <input type="text" name="zip" 
            pattern="[0-9]{0,5}" step="0.1" 
			title="This should be a number with up to 5 digits."
            value="<?php echo htmlspecialchars($zip) ?>">
            <div class="red-text"><?php echo $errors['zip']; ?></div>

            <!-- Apartment Floor -->
            <label>Apartment Floor: </label>
            <input type="text" name="apt_floor" 
            pattern="[0-9]{0,2}" 
			title="This should be a number with up to 5 digits."
            value="<?php echo htmlspecialchars($apt_floor) ?>">
            <div class="red-text"><?php echo $errors['apt_floor']; ?></div>

            <!-- Number of Kids -->
            <label>Number of Kids: </label>
            <input type="text" name="number_of_kids" 
            pattern="[0-9]{0,2}" 
			title="This should be a number with up to 5 digits."
            value="<?php echo htmlspecialchars($number_of_kids) ?>">
            <div class="red-text"><?php echo $errors['number_of_kids']; ?></div>
            
            <!-- Date of Birth -->
            <label>Date of Birth: </label>
            <h6>The appropriate format is: YYYY-MM-DD</h6>
            <input type="text" name="date_of_birth" 
            pattern=".{10,}"
			title="Please enter a valid date with YYYY-MM-DD format"
            value="<?php echo htmlspecialchars($date_of_birth) ?>">
            <div class="red-text"><?php echo $errors['date_of_birth']; ?></div>

            <!-- Relationship Status -->
            <label>Relationship Status:</label>
            <input type="text" name="relationship_status" 
            pattern="[a-zA-Z]{1,30}" 
			title="Please enter more than 1 and less than 30 letters"
            value="<?php echo htmlspecialchars($relationship_status) ?>">
            <div class="red-text"><?php echo $errors['relationship_status']; ?></div>

			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
    </section>
    
    <?php include('customer-footer.php'); ?>

</html>