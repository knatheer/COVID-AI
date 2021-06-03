<?php
$con=mysqli_connect("127.0.0.1","natheer","pass","covid");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

// Perform a query, check for error
if (!mysqli_query($con,"INSERT INTO requests (img_name) VALUES ('ttt')")) {
  echo("Error description: " . mysqli_error($con));
}

mysqli_close($con);
?>