<?php
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
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
      #echo($extensions);
      #echo($_FILES['image']['name']);
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }


      if(empty($errors)==true){
		  #Save the file in the server under the new name
         move_uploaded_file($file_tmp,"received/".$filename);
         #echo "<br>Success<br>";
		 #Create a new record in the database 
		 // Create connection
		 $conn = new mysqli($servername, $username, $password, $dbname);
		 // Check connection
		 if ($conn->connect_error) {
			 die("Connection failed: " . $conn->connect_error);
		}
		#echo ("connection done");
		// prepare and bind
		$stmt = $conn->prepare("INSERT INTO requests (img_name, ip_address, img_size, height, width, status) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssiiis", $img_name, $size, $ip_address, $height, $width, $status);
		
		list($img_width, $img_height) = getimagesize("received/".$filename);
		//Set the parameters
		// set parameters and execute
		$img_name = $filename;
		$size = $file_size;
		$ip_address = echo ip_info("Visitor", "Country") . '-' .  echo ip_info("Visitor", "City");;
		$height = $img_height;
		$width = $img_width;
		$status = "new";
		
		$stmt->execute();
		
		#Now it is python turn to execute.
		#Move to another page and keep fetching the result.
		
		header("Location: result.php?img_name=" . $img_name);
		exit();
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
