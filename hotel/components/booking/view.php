<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/hotel/";

require_once($path . 'connect.php');


session_start();

if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
	echo "Unauthorized Access";
	return;
}

if ($_SESSION['role'] == 'Admin') {
	$ReadSql = "SELECT b.id, b.date_in, b.date_out, b.number_of_guests, b.total_price, u.name as name, u.email as email, r.room_number as room_number, p.name as package  FROM bookings b JOIN users u ON b.user_id=u.id JOIN rooms r ON b.room_id = r.id JOIN packages p ON b.package_id = p.id";
	
}else {
	$id =$_SESSION['id'];
	$ReadSql = "SELECT b.id, b.date_in, b.date_out, b.number_of_guests, b.total_price, u.name as name, u.email as email, r.room_number as room_number, p.name as package  FROM bookings b JOIN users u ON b.user_id=u.id JOIN rooms r ON b.room_id = r.id JOIN packages p ON b.package_id = p.id WHERE u.id = '$id'";
	
}
$res = mysqli_query($connection, $ReadSql);

?>
<?php require($path . 'templates/header.php') ?>

	<div class="container-fluid my-4">
		<div class="row my-2">
			<h2>Hotel stroitel - Bookings</h2>	
		</div>
		<table class="table"> 
		<thead> 
			<tr> 
				<th>Booking No.</th> 
				<th>Room No.</th> 
				<th>Customer Name</th>
				<th>Customer email</th> 
				<th>No. of guests</th> 
				<th>Check-In Date</th> 
				<th>Check-Out Date</th>
				<th>Total Price</th> 
				<th>Package</th>
				<th>Action</th>
			</tr> 
		</thead> 
		<tbody> 
		<?php 
		while($r = mysqli_fetch_assoc($res)){
		?>
			<tr> 
				<th scope="row"><?php echo $r["id"]; ?></th> 
				<td><?php echo $r["room_number"]; ?></td> 
				<td><?php echo $r["name"]; ?></td>
				<td><?php echo $r["email"]; ?></td> 
				<td><?php echo $r["number_of_guests"]; ?></td>
				<td><?php echo $r["date_in"]; ?></td>
				<td><?php echo $r["date_out"]; ?></td> 
				<td><?php echo $r["total_price"]; ?></td>
				<td><?php echo $r["package"]; ?></td> 
				<td>
					<a href="update.php?id=<?php echo $r["id"]; ?>"><button type="button" class="btn btn-info">Edit</button></a>

					<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal">Delete</button>

					  <div class="modal fade" id="myModal" role="dialog">
					    <div class="modal-dialog">
					    
					      
					      <div class="modal-content">
					        <div class="modal-header">
                            <h5 class="modal-title">Delete Booking</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
					        </button>
					        </div>
					        <div class="modal-body">
					          <p>Are you sure?</p>
					        </div>
					        <div class="modal-footer">
					          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					          <a href="delete.php?id=<?php echo $r["id"]; ?>"><button type="button" class="btn btn-danger"> Yes, Delete</button></a>
					        </div>
					      </div>
					      
					    </div>
					  </div>

				</td>
			</tr> 
		<?php } ?>
		</tbody> 
		</table>
	</div>  


<div id="confirm" class="modal hide fade">
  <div class="modal-body">
    Are you sure?
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
  </div>
</div>

<?php require($path . 'templates/footer.php') ?>