<?php
echo "heloo sumeet";

$host = 'db';
$user = 'root';
$db = 'quicklxnew';
$password = 'root';

$conn = new mysqli($host, $user, $password, $db);
if($conn->connect_error){
	echo("there was an error in the connection".$conn->connect_error);
}
else{
	echo("connected gracefully!<br><pre>");
	print_r($_SERVER['HTTP_HOST']);
}


?>
