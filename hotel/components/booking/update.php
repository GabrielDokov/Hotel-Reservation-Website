<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/hotel/";

require_once($path . 'connect.php');

//ъпдейтване на резервация
session_start();

if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
	echo "Unauthorized Access";
	return;
}

$id = $_GET['id'];

$SelSql = "SELECT * FROM `bookings` WHERE id=$id";
$res = mysqli_query($connection, $SelSql);
$r = mysqli_fetch_assoc($res);

if(isset($_POST) & !empty($_POST)){
	$room_id= ($_POST['room_id']);
	//$customer_email = ($_POST['customer_email']);
	$number_of_guests = ($_POST['number_of_guests']);
	//$children = ($_POST['children']);
	$date_in = ($_POST['date_in']);
	$date_out = ($_POST['date_out']);
	$package_id = ($_POST['package_id']);

	$UpdateSql = "UPDATE `bookings` SET room_id='$room_id', package_id = '$package_id' , number_of_guests='$number_of_guests',  date_in='$date_in', date_out='$date_out' WHERE id='$id' ";
	$res = mysqli_query($connection, $UpdateSql);
	if($res){
		header('location: view.php');
	}else{
		$fmsg = "Failed to Update data.";
	}
}
?>
<?php require($path . 'templates/header.php') ?>

	<div class="mt-4">
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<form method="post" class="mx-auto w-25">
            <div class="form-group">
                <label>Room Number</label>
				<input type="text" class="form-control" name="room_id" value="<?php echo $r['room_id']; ?>"/>
            </div> 
			<div class="form-group">
                <label>Packages</label>
				<input type="text" class="form-control" name="package_id" value="<?php echo $r['package_id']; ?>"/>
            </div> 
            <div class="form-group">
                <label>No. of Guests</label>
				<input type="number" class="form-control" name="number_of_guests" value="<?php echo $r['number_of_guests']; ?>"/>
            </div> 
            <div class="form-group">
				<label>Check In</label>
				<input type="date" name="date_in" class="form-control" value="<?php echo $r['date_in']; ?>"/>
			</div>
			<div class="form-group">
				<label>Check Out</label>
				<input type="date" name="date_out" class="form-control" value="<?php echo $r['date_out']; ?>"/>
			</div>
			<input type="submit" class="btn btn-primary" value="Update" />
		</form>
	</div>
	
<?php require($path . 'templates/footer.php') ?>