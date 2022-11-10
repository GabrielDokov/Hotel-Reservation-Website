
<?php
require_once ('connect.php');

session_start();

if(isset($_POST) & !empty($_POST)){
	$date_in = ($_POST['date_in']);
	$date_out = ($_POST['date_out']);
	$guests = ($_POST['guests']);
	$user_id = ($_POST['user_id']);
	$package_id = ($_POST['package_id']);
	$room_price = ($_POST['room_price']);
	$room_id = ($_POST['room_id']);

	$sql_packages = "SELECT * FROM packages WHERE id=?";
	if ($stmt = mysqli_prepare($connection, $sql_packages)) {
		mysqli_stmt_bind_param($stmt, "i", $package_id); 
		if (mysqli_stmt_execute($stmt)) {
			$result = mysqli_stmt_get_result($stmt);
		} else {
			echo "Oops! Something went wrong. Please try again later.";
		}
		mysqli_stmt_close($stmt);	
	}
											
	$package = mysqli_fetch_array($result,MYSQLI_ASSOC);

	$total_price = $guests*$room_price + $guests * $package["price"];

	$query = "INSERT INTO `bookings` (date_in, date_out, number_of_guests, room_id, user_id, package_id, total_price) VALUES ('$date_in', '$date_out', '$guests', '$room_id', '$user_id', '$package_id', '$total_price')";
	$res = mysqli_query($connection, $query);
	if($res){
		header('location: index.php');
	}else{
		$fmsg = "Failed to Insert data.";
		print_r($res->error_list);
	}
}

?>
<?php require('templates/header.php') ?>
	<div class="d-flex">
		<img src="img/hotel-banner.jpg" class="hotel-img">
		<span class="tagline"></span>
	</div>
	<div class="d-flex mt-4 mx-4">
        <h2>Welcome To Hotel Stroitel,
        	<b><?php // 
			if ($user_logged) {
				$user_id = ($_SESSION['id']);
				$select_sql = "SELECT name FROM `users` WHERE id='$user_id'";
				$result = mysqli_query($connection, $select_sql);
				if ($result->num_rows > 0) {
					$row = mysqli_fetch_assoc($result);
					echo $row["name"];
					if (!$row["name"]) {
						 echo "Guest";
					}
				}
			} else {
			    echo "Guest";
			}
        	?></b> 	
        </h2>
    </div>

    <div class="d-flex my-2">
	<?php 
      	if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
    <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
    </div>

	<div class="row main-section">
      <?php 
		$sql = "SELECT * FROM `room_types` ORDER BY price";
		$res = mysqli_query($connection, $sql);




		$num_of_rows = mysqli_num_rows($res);
		if ($num_of_rows > 0) {
	    	
		    while($num_of_rows > 0) {
		    	$num_of_rows--;
		    	$r = mysqli_fetch_assoc($res);
		        include('templates/room.php');
		    }
		} else {
		    echo "<p>No Room Available</p>";
		}
	?>
	</div>

<?php require('templates/footer.php') ?>