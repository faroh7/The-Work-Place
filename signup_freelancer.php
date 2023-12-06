<?php
$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "freelance_workers_portal";
$conn = mysqli_connect($server_name, $username, $password, $database_name);

// Check the connection
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Escape form data to prevent SQL injection
    $Username = mysqli_real_escape_string($conn, $_POST['Username_f']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email_f']);
    $Password = mysqli_real_escape_string($conn, $_POST['Password_f']);
    $First_Name = mysqli_real_escape_string($conn, $_POST['First_Name_f']);
    $Last_Name = mysqli_real_escape_string($conn, $_POST['Last_Name_f']);
    $Phone_Number = mysqli_real_escape_string($conn, $_POST['Phone_Number_f']);
    $Role = implode(", ", $_POST['Role']); // Convert array to string
    $Summary = mysqli_real_escape_string($conn, $_POST['Summary']);

    // Check if the username and email already exist
    $sql_query = "SELECT * FROM freelancer_details WHERE username='$Username'";
    $result = mysqli_query($conn, $sql_query);
    $count_Username = mysqli_num_rows($result);

    $sql_query = "SELECT * FROM freelancer_details WHERE Email='$Email'";
    $result = mysqli_query($conn, $sql_query);
    $count_Email = mysqli_num_rows($result);

    if ($count_Username == 0 && $count_Email == 0) {
        // Hash the password
        $hash = password_hash($Password, PASSWORD_DEFAULT);

        // Insert login credentials into 'login' table
        $login_sql = "INSERT INTO login (Username, Password) VALUES ('$Username', '$hash')";
        $login_result = mysqli_query($conn, $login_sql);

        if ($login_result) {
            // Get the ID of the newly inserted row in 'login' table
            $login_id = mysqli_insert_id($conn);

			// File Upload Handling for Portfolio Image
			$portfolio_file = $_FILES['portfolio']['name'];
			$portfolio_tmp_name = $_FILES['portfolio']['tmp_name'];
			$portfolio_extension = strtolower(pathinfo($portfolio_file, PATHINFO_EXTENSION));
			$portfolio_target_dir = "uploads/";
			$portfolio_target_file = $portfolio_target_dir . $Username . "_portfolio." . $portfolio_extension;

			// Move the uploaded file to the desired location
			if (move_uploaded_file($portfolio_tmp_name, $portfolio_target_file)) {
				// The file has been uploaded successfully, you can store the file path in the database or perform any other necessary actions here.
				// For example:
				$portfolio_path_in_database = $portfolio_target_file;
			} else {
				// Handle the case when the file upload fails
				echo "File upload failed.";
				exit();
			}

			// File Upload Handling for Profile Picture
			$profile_pic_file = $_FILES['profile_pic_f']['name'];
			$profile_pic_tmp_name = $_FILES['profile_pic_f']['tmp_name'];
			$profile_pic_extension = strtolower(pathinfo($profile_pic_file, PATHINFO_EXTENSION));
			$profile_pic_target_file = $portfolio_target_dir . $Username . "_profile." . $profile_pic_extension;

			// Move the uploaded file to the desired location
			if (move_uploaded_file($profile_pic_tmp_name, $profile_pic_target_file)) {
				// The file has been uploaded successfully, you can store the file path in the database or perform any other necessary actions here.
				// For example:
				$profile_pic_path_in_database = $profile_pic_target_file;
			} else {
				// Handle the case when the file upload fails
				echo "File upload failed.";
				exit();
			}
			
            // Insert freelancer details into 'freelancer_details' table
            $freelancer_sql = "INSERT INTO freelancer_details (freelancer_id, Username, Email, First_Name, Last_Name, Phone_Number, Role, Summary, Portfolio, profile_pic)
                VALUES ('$login_id', '$Username', '$Email', '$First_Name', '$Last_Name', '$Phone_Number', '$Role', '$Summary', '$portfolio_path_in_database', '$profile_pic_path_in_database')";
            $freelancer_result = mysqli_query($conn, $freelancer_sql);

            if ($freelancer_result) {
                // Store user data in PHP sessions before redirecting
                session_start();
                $_SESSION['username'] = $Username;
                $_SESSION['phone_number'] = $Phone_Number;
                $_SESSION['roles'] = $Role;
                $_SESSION['summary'] = $Summary;
				$_SESSION['portfolio'] = $portfolio_path_in_database;
                $_SESSION['profile_picc'] = $profile_pic_path_in_database;
                header("Location: profile_freelancer.php");
                exit; // Terminate the script to prevent further execution
            } else {
                // Error occurred while inserting freelancer details
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
?>
