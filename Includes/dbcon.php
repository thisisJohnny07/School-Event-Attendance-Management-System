<?php
 	$conn = mysqli_connect('localhost', 'John Rey', 'Skoj24.,', 'cit');
	if($conn->connect_error){
		echo "Seems like you have not configured the database. Failed To Connect to database:" . $conn->connect_error;
	}
?>