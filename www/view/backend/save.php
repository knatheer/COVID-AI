<?php
include 'database.php';
if(count($_POST)>0){
	if($_POST['type']==1){
		$req_id=$_POST['req_id'];
		$img_name=$_POST['img_name'];
		$ip_address=$_POST['ip_address'];
		$img_size=$_POST['img_size'];
		$height=$_POST['height'];
		$width=$_POST['width'];
		$result=$_POST['result'];
		$process_time=$_POST['process_time'];
		$confidence=$_POST['confidence'];
		$feedback=$_POST['feedback'];
		$status=$_POST['status'];
		$date_time=$_POST['date_time'];
		$sql = "INSERT INTO `requests`( `img_name`, `ip_address`, `img_size`,`height`,`width`,`process_time`,`result`,`confidence`,`feedback`,`status`,`date_time`) 
		VALUES ('$img_name','$ip_address',$img_size,$height, $width,$process_time,'$result',$confidence,'$feedback', '$status', '$date_time')";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
		$req_id=$_POST['req_id'];
		$img_name=$_POST['img_name'];
		$ip_address=$_POST['ip_address'];
		$img_size=$_POST['img_size'];
		$height=$_POST['height'];
		$width=$_POST['width'];
		$result=$_POST['result'];
		$process_time=$_POST['process_time'];
		$confidence=$_POST['confidence'];
		$feedback=$_POST['feedback'];
		$status=$_POST['status'];
		$date_time=$_POST['date_time'];
		$sql = "UPDATE `requests` set `img_name`='$img_name', `ip_address`='$ip_address', `img_size`=$img_size, `height`=$height,`width`=$width,`process_time`=$process_time,`result`='$result', `confidence`=$confidence,`feedback`='$feedback',`status`='$status',`date_time`= '$date_time' WHERE req_id=$req_id";
		
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==3){
		$req_id=$_POST['id'];
		$sql = "DELETE FROM `requests` WHERE req_id=$req_id ";
		if (mysqli_query($conn, $sql)) {
			echo $req_id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==4){
		$id=$_POST['id'];
		$sql = "DELETE FROM crud WHERE req_id in ($req_id)";
		if (mysqli_query($conn, $sql)) {
			echo $req_id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}

?>