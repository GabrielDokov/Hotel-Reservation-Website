<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/hotel/";

require_once($path . 'connect.php');


session_start();

if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	echo "Unauthorized Access";
	return;
}

if(isset($_POST) & !empty($_POST)){
	$type = ($_POST['type']);
	$price = ($_POST['price']);
	$description = ($_POST['description']);
	// добаване на снимка
    $image = $_FILES['image']['name']; 
    $dir="../img/rooms/";
    $temp_name=$_FILES['image']['tmp_name'];
    if($image!="")
    {
        if(file_exists($dir.$image))
        {
            $image= time().'_'.$image;
        }
        $fdir= $dir.$image;
        move_uploaded_file($temp_name, $fdir);
    }

    
	$query = "INSERT INTO `rooms` (type, price, description, image) VALUES ('$type', '$price', '$description', '$image')";
	$res = mysqli_query($connection, $query);
	if($res){
		header('location: view.php');
	}else{
		$fmsg = "Failed to Insert data.";
		print_r($res->error_list);
	}
}
?>