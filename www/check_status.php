<?php
include 'connection.php';

if (isset($_POST['img_name']))
{

	$img_name = $_POST['img_name'];

	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
 
	if ($conn->connect_error) {

		die("Connection failed: " . $conn->connect_error);
	}

	// prepare and bind
	$stmt = $conn->prepare("SELECT img_name, result, confidence, process_time FROM requests where status = 'processed' and img_name = ?");
	$stmt->bind_param("s", $img_name);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($row = $result->fetch_assoc())
	{
		$result_txt = $row['img_name'] . "~" . $row['result'] . "~" . $row['confidence'] . "~" . $row['process_time'];

		echo($result_txt);
	}
	#$result->close();
	$stmt->close();
	$conn->close();
}
?>