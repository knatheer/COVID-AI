<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "covid";
if (isset($_POST['img_name']))
{
	$img_name = $_POST['img_name'];
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	#echo ("connection done");
	// prepare and bind
	$stmt = $conn->prepare("SELECT img_name FROM requests where status = 'processed' and img_name = ?");
	$stmt->bind_param("s", $img_name);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($row = $result->fetch_assoc())
	{
		$result = $row['img_name'];
		echo($result);
	}
	#$result->close();
	$stmt->close();
	$conn->close();
}
?>