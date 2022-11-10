<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/hotel/";

require_once($path . 'connect.php');


session_start();

if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['role'] == 'Admin')) {
	echo "Unauthorized Access";
	return;
}

if(isset($_POST) & !empty($_POST)){
	$name = ($_POST['name']);
	$capacity= ($_POST['capacity']);
	$price= ($_POST['price']);


    
	$query = "INSERT INTO `room_types` (name, capacity, price) VALUES ('$name', '$capacity', '$price')";
	$res = mysqli_query($connection, $query);
	if($res){
		header('location: view.php');
	}else{
		$fmsg = "Failed to Insert data.";
		print_r($res->error_list);
	}
}
?>

<?php require_once($path . 'templates/header.php') ?>

	<div class="container">
	<?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
		<h2 class="my-4">Add New Room</h2>
		<form method="post" enctype="multipart/form-data">
			<div class="form-group">
                <label>Type</label>
				<input type="text" id="id" class="form-control" name="name" value="" required/>
            </div> 
            <div class="form-group">
                <label>Price</label>
				<input type="number" class="form-control" name="price" value="" required/>
            </div> 
            <div class="form-group">
                <label>Capacity</label>
				<input type="text" class="form-control" name="capacity" value=""/>
            </div> 
            
			<input type="submit" class="btn btn-primary" value="Add Room" />
		</form>
	</div>
	
<?php require_once($path . 'templates/footer.php') ?>