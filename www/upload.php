<?php
function getIPAddress() {  
	//whether ip is from share internet
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   
	  {
		$ip_address = $_SERVER['HTTP_CLIENT_IP'];
	  }
	//whether ip is from proxy
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
	  {
		$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	  }
	//whether ip is from remote address
	else
	  {
		$ip_address = $_SERVER['REMOTE_ADDR'];
	  }
	return $ip_address;
}  

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "covid";

   if(isset($_FILES['image'])){
      $errors= array();
      $filename = md5(date('Y-m-d H:i:s:u')).".jpg";
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

      $extensions= array("jpeg","jpg","png");
      echo($extensions);
      echo($_FILES['image']['name']);
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }


      if(empty($errors)==true){

         move_uploaded_file($file_tmp,"received/".$filename);
         echo "<br>Success<br>";
		 #Save record in database
		 // Create connection
		 $conn = new mysqli($servername, $username, $password, $dbname);
		 // Check connection
		 if ($conn->connect_error) {
			 die("Connection failed: " . $conn->connect_error);
		}
		echo ("connection done");
		// prepare and bind
		$stmt = $conn->prepare("INSERT INTO requests (img_name, ip_address, img_size, height, width, status) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssiiis", $img_name, $size, $ip_address, $height, $width, $status);
		
		list($img_width, $img_height) = getimagesize("received/".$filename);
		//Set the parameters
		// set parameters and execute
		$img_name = $filename;
		$size = $file_size;
		$ip_address = getIPAddress();
		$height = $img_height;
		$width = $img_width;
		$status = "new";
		
		$stmt->execute();
		

		
		$select_sql = "SELECT img_name, result from requests where status = 'processed' and img_name='" . $img_name . "'";
		print($select_sql);
		
		$result = $conn->query($select_sql);

		 #check if there is result file
		 while ($result->num_rows == 0)
		 {
			 $result = $conn->query($select_sql);
		 }
		 $row = $result->fetch_assoc();
		 $stmt->close();
		 $conn->close();
		 echo ("<img src='processed/" . $filename . "'><br>");
		 echo $row['result'];
	}
	else{
         print_r($errors);
      }
   }
?>
<html>
   <body>

      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" />
         <input type="submit"/>
      </form>

   </body>
</html>
