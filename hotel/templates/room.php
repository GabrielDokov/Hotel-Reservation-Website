<?php
									
	require_once "connect.php";

	$user_logged = false;


	$sql_packages = "SELECT * FROM packages ORDER BY price";
	if ($stmt = mysqli_prepare($connection, $sql_packages)) {

		if (mysqli_stmt_execute($stmt)) {
			$result = mysqli_stmt_get_result($stmt);
		} else {
			echo "Oops! Something went wrong. Please try again later.";
		}
		mysqli_stmt_close($stmt);
	}

?>

<?php
									
	require_once "connect.php";

	$room_type_id = ($r['id']);
	

    $csvFile = file('sample_text.csv');
    $data = [];
    foreach ($csvFile as $line) {
        array_push($data, $line);

    }

	

	//print_r($data);


	$start_date = (array_values($data)[0]);
	$end_date = (array_values($data)[1]);


	$sql_packages = "SELECT * FROM rooms r
        WHERE r.room_type_id = ?
		AND 
        r.id NOT IN
        (
            SELECT b.room_id 
            FROM bookings b
            WHERE  (b.date_in <= ? AND b.date_out >= ?)
            OR (b.date_in < ? AND b.date_out >= ?) 
            OR (? <= b.date_in AND ? >= b.date_in)
        )";
		
	if ($stmt = mysqli_prepare($connection, $sql_packages)) 
	{


		//mysqli_stmt_bind_param($stmt, "issssss", $room_type_id, $GLOBALS['date_in'], $GLOBALS['date_in'], $GLOBALS['date_out'], $GLOBALS['date_out'], $GLOBALS['date_in'], $GLOBALS['date_out']); 

		mysqli_stmt_bind_param($stmt, "issssss", $room_type_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date); 
	
		
	
		if (mysqli_stmt_execute($stmt)) {
			$result_rooms = mysqli_stmt_get_result($stmt);
		} else {
			echo "Oops! Something went wrong. Please try again later.";
		}
		mysqli_stmt_close($stmt);
	}

?>

<?php

	$path = $_SERVER['DOCUMENT_ROOT'];
	$path .= "/hotel/";

	require_once($path . 'connect.php');

	if(isset($_POST) & !empty($_POST)){
		$date_in = ($_POST['date_in']);
		$date_out = ($_POST['date_out']);
		$guests = ($_POST['guests']);
		$user_id = ($_POST['user_id']);

		$dates = array($date_in, $date_out);

		$file = fopen("sample_text.csv","w");
foreach ($dates as $line)
{
 fputcsv($file,explode(',',$line));
}
fclose($file);




		$query = "INSERT INTO `bookings` (date_in, date_out, guests, user_id) VALUES ('$date_in', '$date_out', '$guests', '$user_id')";
		$res = mysqli_query($connection, $query);
		if($res){
			header('location: index.php');
		}else{
			$fmsg = "Failed to Insert data.";
			print_r($res->error_list);
		}
	}
?>

<div class="col-3 my-2">
	<div class="card m-auto room" style="width: 20rem;">
		<img class="card-img-top" src="<?php echo $server; ?>img/rooms/<?php echo $r['id']; ?>.jpg" alt="Card Image Caption">

		<div class="card-body">
			<h4 class="card-title"><?php echo $r['name']; ?></h4>
			<p class="card-text"><?php echo "Capacity: ", $r['capacity']; ?></p>
			<p>$<?php echo $r['price']; ?></p>



			<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
				$user_logged = true;

				if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Customer' ) { ?>
			
			<button type="button" name = "bookBtn" class="btn book-button" data-toggle="modal" data-target="#confirmOrder<?php echo $r["id"]; ?>">
				<span class="text-white"><i class="fa fa-key text-white"></i> Book</span>
			</button>
			
			<?php } 
			}
			?>
			

			<!-- Modal -->
			<div class="modal" id="confirmOrder<?php echo $r["id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<form method="post">

							<!--<input type="number" name="room_number" value="<?php echo $r["id"];?>" hidden>-->

							<input type="number" name="user_id" value="<?php echo $_SESSION["id"]; ?>" hidden>
							 

							<div class="modal-header">
								<h3 class="modal-title" id="confirmTitle">Book Room</h3>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label>No. of adults</label>
									<input type="number" name="guests" class="form-control" value="<?php echo $r["capacity"]; ?>" min = "1" max="<?php echo $r["capacity"]; ?>" />
								</div>

								<input type="number" name="room_price" value="<?php echo $r["price"]; ?>" hidden>
								
								<div class="form-group">
									<label>Check In</label>
									<input id= "dateInInput" type="date" name="date_in" class="form-control" placeholder="Date of Check In" />
								</div>
								<div class="form-group">
									<label>Check Out</label>
									<input id= "dateOutInput" type="date" name="date_out" class="form-control" placeholder="Date of Check Out" />
								</div>
								<div class="form-group">
									
								<label>Package</label>
								<select name="package_id" class="form-control">

								<?php
										while ($package = mysqli_fetch_array($result,MYSQLI_ASSOC)):;
								?>
										<option name="package_id" value="<?php echo $package["id"];?>">
											<?php echo $package["name"], ' - ', $package["price"], ' $', ' -> ', $package["description"] ;?>
										</option>
									<?php endwhile; ?>
									
								</select>
									
								</div>

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

								<!--<button type="submit" class="btn btn-primary">Check</button>-->

								<button type="button" name = "checkBtn" class="btn book-button" data-toggle="modal" data-target="#checkOrder<?php echo $r["id"]; ?>">
									<span class="text-white"><i class="fa fa-key text-white"></i>Check</span>
								</button>

								<div class="modal fade" id="checkOrder<?php echo $r["id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmOrder" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">

											<div class="modal-header">
												<h3 class="modal-title" id="confirmOrder">Confirm Booking</h3>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>

											<div class="modal-body">
											<label>Availiable rooms</label>
											
									
												<select name="room_id" class="form-control">

													<?php
															while ($rooms = mysqli_fetch_array($result_rooms,MYSQLI_ASSOC)):;

													?>
															<option name="room_id" value="<?php echo $rooms["id"];?>">
																<?php echo $rooms["room_number"] ;?>
															</option>

													<?php endwhile; ?>
												</select>

												<div class="form-group">
									<label></label>
									<input type="input" name="	" class="form-control" readonly = 'readonly' />
								</div>
								<div class="form-group">
									<label></label>
									<input type="input" name="" class="form-control" readonly = 'readonly'  />
								</div>
								<div class="form-group">
									<label></label>
									<input type="input" name="" class="form-control" readonly = 'readonly'  />
								</div>
								<div class="form-group">
									<label></label>
									<input type="input" name="" class="form-control" readonly = 'readonly' />
								</div>
								<div class="form-group">
									<label></label>
									<input type="input" name="" class="form-control" readonly = 'readonly' />
								</div>
								
												

												

							
											</div>

											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

												<button type="submit" class="btn btn-primary">Book</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>