<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/hotel/";

require_once($path . 'connect.php');


session_start();

if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['role'] == 'Admin')) {
	echo "Unauthorized Access";
	return;
}

$id = $_GET['id'];

$SelSql = "SELECT * FROM `room_types` WHERE id=$id";
$res = mysqli_query($connection, $SelSql);
$r = mysqli_fetch_assoc($res);


if(isset($_POST) & !empty($_POST)){
	$name = ($_POST['name']);
	$capacity = ($_POST['capacity']);
	$price = ($_POST['price']);
	 
   
    


	$query = "UPDATE `room_types` SET name='$name', price='$price', capacity='$capacity' WHERE id='$id'";
	
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
		<h2 class="my-4">Update Room</h2>
		<form method="post" enctype="multipart/form-data">

			<div class="form-group">

                <label>Type</label>
				<input type="text" class="form-control" readonly = 'readonly' name="name" value="<?php echo $r['name'];?>" required/>
            </div> 
            <div class="form-group">
                <label>New Price</label>
				<input type="text" class="form-control" name="price" value="<?php echo $r['price'];?>" required/>
            </div> 
            <div class="form-group">
                <label>Capacity</label>
				<input type="text" class="form-control" name="capacity" value="<?php echo $r['capacity'];?>"/>
            </div> 
			<input type="submit" class="btn btn-primary" value="Update" />
		</form>
	</div>
	
<?php require_once($path . 'templates/footer.php') ?>