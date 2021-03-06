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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
			$('#my_image').attr('src', 'received/' +  result[0]);
      $('#result_image').attr('src', result[1].toLowerCase() + '.jpg');
      $summary = 'Confidence=' + result[2] + '%     Prediction Time = ' + result[3] + 's<br>';
			$('#summary').html($summary);
			$("#feedback").show();
		}
		else{
			setTimeout(doPoll, 500);
		}
    });	
}

function setFeedback(fb){
    $.post("update_feedback.php",
    {
      img_name: "<?php echo $img_name ?>",
	  feedback: fb
    },
    function(data,status){
		$("#feedback").hide();
		$("#message").show();
    });		
}

function noFeedback(){
		$("#feedback").hide();
		$("#message").show();
}

</script>
<script>
$(document).ready(function(){
	doPoll();
});
</script>

</head>
<body>

<img id="my_image"  src="wait.gif" width="500" height="600" />
<br>
<img id="result_image"  src="empty.jpg" width="160" height="70" />
<div id="summary"></div>
<div style = "display: none" id="feedback" >
Do you agree with the above result?<br>
<button class="button button1" onclick="setFeedback('yes')">Yes</button>
<button class="button button3" onclick="setFeedback('no')">No</button>
<button class="button button3" onclick="noFeedback()">Not Sure</button>
</div>
<div style = "display: none" id="message" >
<button class="button button1">Thanks You!</button><br>
<button onclick="location.href = 'index.php';" class="button button5">Submit Another Image</button>
</div>
</body>
</html>