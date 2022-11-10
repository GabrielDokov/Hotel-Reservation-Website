<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/hotel/";

require_once($path . 'connect.php');

$id = $_GET['id'];
$DelSql = "DELETE FROM `room_types` WHERE id=$id";
$res = mysqli_query($connection, $DelSql);
if($res){
	header('location: view.php');
}else{
	echo "Failed to delete";
}
?>