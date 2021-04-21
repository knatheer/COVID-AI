<?php
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
		 $myfile = fopen("new/".$filename, "w") or die("Unable to open file!");
		 fclose($myfile);
		 #check if there is result file
		 while (!file_exists ("result/".$filename.".txt" ))
		 {
		 }
		 echo ("<img src='processed/" . $filename . "'><br>");
		 echo file_get_contents("result/".$filename.".txt" );

		 
	 #$exec2= shell_exec('sudo -u ubuntu /home/ubuntu/tools/dark/darknet/darknet detector test /home/ubuntu/tools/dark/darknet/cfg/covid.data /home/ubuntu/Downloads/yolo-obj.cfg /home/ubuntu/Downloads/yolo-obj_5000.weights -thresh 0.25 images/'.$filename.' -ext_output -dont_show -out test.txt | grep "covid:"');
	 #echo "<pre>$exec2</pre>";
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
