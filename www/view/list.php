<?php
include 'backend/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>User Data</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="ajax/ajax.js"></script>
</head>
<body>
    <div class="container">
	<p id="success"></p>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2>Manage <b>Users</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New User</span></a>
						<a href="JavaScript:void(0);" class="btn btn-danger" id="delete_multiple"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>						
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>
							<span class="custom-checkbox">
								<input type="checkbox" id="selectAll">
								<label for="selectAll"></label>
							</span>
						</th>
						<th>Request ID</th>
                        <th>Image Name</th>
                        <th>Request Location</th>
						<th>Image Size (bytes)</th>
                        <th>Height</th>
                        <th>Width</th>
						<th>Process Time</th>
						<th>Result</th>
						<th>Confidence</th>
						<th>Feedback</th>
						<th>Status</th>
						<th>Date_Time</th>
                    </tr>
                </thead>
				<tbody>
				
				<?php
				$result = mysqli_query($conn,"SELECT * FROM requests");
					$i=1;
					while($row = mysqli_fetch_array($result)) {
				?>
				<tr id="<?php echo $row["req_id"]; ?>">
				<td>
							<span class="custom-checkbox">
								<input type="checkbox" class="user_checkbox" data-req_id"="<?php echo $row["req_id"]; ?>">
								<label for="checkbox2"></label>
							</span>
						</td>
					<td><?php echo $i; ?></td>
					<td><?php echo $row["img_name"]; ?></td>
					<td><?php echo $row["ip_address"]; ?></td>
					<td><?php echo $row["img_size"]; ?></td>
					<td><?php echo $row["height"]; ?></td>
					<td><?php echo $row["width"]; ?></td>
					<td><?php echo $row["process_time"]; ?></td>
					<td><?php echo $row["result"]; ?></td>
					<td><?php echo $row["confidence"]; ?></td>					
					<td><?php echo $row["feedback"]; ?></td>					
					<td><?php echo $row["status"]; ?></td>					
					<td><?php echo $row["date_time"]; ?></td>					
					<td>
						<a href="#editEmployeeModal" class="edit" data-toggle="modal">
							<i class="material-icons update" data-toggle="tooltip" 
							data-req_id="<?php echo $row["req_id"]; ?>"
							data-img_name="<?php echo $row["img_name"]; ?>"
							data-ip_address="<?php echo $row["ip_address"]; ?>"
							data-img_size="<?php echo $row["img_size"]; ?>"
							data-height="<?php echo $row["height"]; ?>"
							data-width="<?php echo $row["width"]; ?>"
							data-process_time="<?php echo $row["process_time"]; ?>"
							data-result="<?php echo $row["result"]; ?>"
							data-confidence="<?php echo $row["confidence"]; ?>"
							data-feedback="<?php echo $row["feedback"]; ?>"
							data-status="<?php echo $row["status"]; ?>"
							data-date_time="<?php echo $row["date_time"]; ?>"							
							title="Edit">&#xE254;</i>
						</a>
						<a href="#deleteEmployeeModal" class="delete" data-req_id"="<?php echo $row["req_id"]; ?>" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" 
						 title="Delete">&#xE872;</i></a>
                    </td>
				</tr>
				<?php
				$i++;
				}
				?>
				</tbody>
			</table>
			
        </div>
    </div>
	<!-- Add Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_form">
					<div class="modal-header">						
						<h4 class="modal-title">Add Request</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Image Name</label>
							<input type="text" id="img_name" name="img_name" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Request Location</label>
							<input type="text" id="ip_address" name="ip_address" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Image Size (bytes)</label>
							<input type="number" id="img_size" name="img_size" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Height</label>
							<input type="number" id="height" name="height" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Width</label>
							<input type="number" id="width" name="width" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Process Time</label>
							<input type="number" id="process_time" name="process_time" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Result</label>
							<input type="text" id="result" name="result" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Confidence</label>
							<input type="number" id="confidence" name="confidence" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Feedback</label>
							<input type="text" id="feedback" name="feedback" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Status</label>
							<input type="text" id="status" name="status" class="form-control" required>
						</div>						
						<div class="form-group">
							<label>Date_Time</label>
							<input type="text" id="date_time" name="date_time" class="form-control" required>
						</div>
					</div>					
					<div class="modal-footer">
					    <input type="hidden" value="1" name="type">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="button" class="btn btn-success" id="btn-add">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="update_form">
					<div class="modal-header">						
						<h4 class="modal-title">Edit User</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="req_id" name="req_id" class="form-control" required>					
						<div class="form-group">
							<label>Image Name</label>
							<input type="text" id="img_name" name="img_name" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Request Location</label>
							<input type="text" id="ip_address" name="ip_address" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Image Size (bytes)</label>
							<input type="number" id="img_size" name="img_size" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Height</label>
							<input type="number" id="height" name="height" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Width</label>
							<input type="number" id="width" name="width" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Process Time</label>
							<input type="number" id="process_time" name="process_time" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Result</label>
							<input type="text" id="result" name="result" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Confidence</label>
							<input type="number" id="confidence" name="confidence" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Feedback</label>
							<input type="text" id="feedback" name="feedback" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Status</label>
							<input type="text" id="status" name="status" class="form-control" required>
						</div>						
						<div class="form-group">
							<label>Date_Time</label>
							<input type="text" id="date_time" name="date_time" class="form-control" required>
						</div>
					</div>
					<div class="modal-footer">
					<input type="hidden" value="2" name="type">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="button" class="btn btn-info" id="update">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Delete Modal HTML -->
	<div id="deleteEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form>
						
					<div class="modal-header">						
						<h4 class="modal-title">Delete User</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="req_id" name="id" class="form-control">					
						<p>Are you sure you want to delete these Records?</p>
						<p class="text-warning"><small>This action cannot be undone.</small></p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="button" class="btn btn-danger" id="delete">Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>
</html>                                		                            