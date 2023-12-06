<?php
session_start();
$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "freelance_workers_portal";
$conn = mysqli_connect($server_name, $username, $password, $database_name);

//now check the connection
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
echo "ok";

if (isset($_POST['submit'])) {
    $Username = mysqli_real_escape_string($conn, $_POST['Username']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    // $profile_pic = mysqli_real_escape_string($conn, $_POST['profile_pic']);
    $Password = mysqli_real_escape_string($conn, $_POST['Password']);
    $First_Name = mysqli_real_escape_string($conn, $_POST['First_Name']);
    $Last_Name = mysqli_real_escape_string($conn, $_POST['Last_Name']);
    $Phone_Number = mysqli_real_escape_string($conn, $_POST['Phone_Number']);

    $sql_query = "select * from customer_details where username='$Username'";
    $result = mysqli_query($conn, $sql_query);
    $count_Username = mysqli_num_rows($result);

    $sql_query = "select * from customer_details where Email='$Email'";
    $result = mysqli_query($conn, $sql_query);
    $count_Email = mysqli_num_rows($result);

	if ($count_Username == 0 && $count_Email == 0) {
		$hash = password_hash($Password, PASSWORD_DEFAULT);
	
		// Insert login credentials into 'login' table
		$login_sql = "INSERT INTO login (Username, Password) VALUES ('$Username', '$hash')";
		$login_result = mysqli_query($conn, $login_sql);
	
		if ($login_result) {
			// Get the ID of the newly inserted row in 'login' table
			$login_id = mysqli_insert_id($conn);
	
			// File Upload Handling for Profile Picture
			$profile_pic_file = $_FILES['profile_pic']['name'];
			$profile_pic_tmp_name = $_FILES['profile_pic']['tmp_name'];
			$profile_pic_extension = strtolower(pathinfo($profile_pic_file, PATHINFO_EXTENSION));
			$profile_pic_target_dir = "uploads/";
			$profile_pic_target_file = $profile_pic_target_dir . $Username . "_profile." . $profile_pic_extension;

			// Move the uploaded file to the desired location
			if (move_uploaded_file($profile_pic_tmp_name, $profile_pic_target_file)) {
				// The file has been uploaded successfully
				$profile_pic_path_in_database = $profile_pic_target_file;
			} else {
				// Handle the case when the file upload fails
				echo "File upload failed.";
				exit();
			}

			// Insert customer details into 'customer_details' table
			$customer_sql = "INSERT INTO customer_details (Username, Email, profile_pic, First_Name, Last_Name, Phone_Number)
				VALUES ('$Username', '$Email', '$profile_pic_path_in_database', '$First_Name', '$Last_Name', '$Phone_Number')";
			$customer_result = mysqli_query($conn, $customer_sql);
	
			if ($customer_result) {
				$_SESSION['username'] = $Username; // Set the session variable 'username'
				$_SESSION['profile_pic'] = $profile_pic_path_in_database;
				header("Location: profile_customer.php"); 
			} else {
				// Error occurred while inserting customer details
				echo "Error: " . mysqli_error($conn);
			}
		} else {
			// Error occurred while inserting login credentials
			echo "Error: " . mysqli_error($conn);
		}
	} else {
		if ($count_Username > 0) {
			echo '<script>
			window.location.href="index.php";
			alert("Username already exists, you will be redirected, kindly choose another");
			window.history.back();
			</script>';
		}
		if ($count_Email > 0) {
			echo '<script>
			window.location.href="index.php";
			alert("Email already exists, you will be redirected, kindly choose another");
			window.history.back();
			</script>';
		}
	}
	
}