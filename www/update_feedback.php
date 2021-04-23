<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "covid";


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	 die("Connection failed: " . $conn->connect_error);
}
echo ("connection done");
// prepare and bind
$stmt = $conn->prepare("UPDATE requests SET feedback = ? where img_name = ?");

$img_name = '';
if (isset($_POST['img_name'])){
	$img_name = $_POST['img_name'];
}
$feedback = '';
if (isset($_POST['feedback'])){
	$feedback = $_POST['feedback'];
}
$stmt->bind_param("ss", $feedback, $img_name);
$stmt->execute();
$conn->close();
?>