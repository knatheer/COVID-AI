<!DOCTYPE html>

<?php

$img_name = "";
if (isset($_GET['img_name']))
{
	$img_name = $_GET['img_name'];
}
?>
	
<html>
<head>
<style>
.button {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
.button1 {background-color: #4CAF50;} /* Green */
.button2 {background-color: #008CBA;} /* Blue */
.button3 {background-color: #f44336;} /* Red */ 
.button4 {background-color: #e7e7e7; color: black;} /* Gray */ 
.button5 {background-color: #555555;} /* Black */
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

function doPoll(){
    $.post("check_status.php",
    {
      img_name: "<?php echo $img_name ?>"
    },
    function(data,status){
		if (data.length > 0){
			result = data.split('~');
			
			$('#my_image').attr('src', 'processed/' +  result[0]);
			$('#summary').html(result[1]);
			$("#feedback").show();
		}
		else{
			setTimeout(doPoll, 500);
		}
    });	
}
</script>
<script>
$(document).ready(function(){
	doPoll();
});
</script>

</head>
<body>

<img id="my_image"  src="wait.gif"/>
<br>
<div id="summary"></div>
<div style = "display: none" id="feedback" >
Do you agree with the above result?<br>
<button class="button button1">Yes</button>
<button class="button button3">No</button>
</div>

</body>
</html>