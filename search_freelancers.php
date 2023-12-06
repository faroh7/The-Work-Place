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

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    // Search for freelancers in the database with matching username or role
    $search_query = "SELECT Username, profile_pic, Role FROM freelancer_details WHERE Username LIKE '%$search%' OR Role LIKE '%$search%'";
    $result = mysqli_query($conn, $search_query);

	if (mysqli_num_rows($result) > 0) {
		echo "<table>";
		echo "<tr><th>Username</th><th>Profile Picture</th><th>Role</th></tr>";
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td><a href='profileview_freelancer.php?username=" . $row['Username'] . "'>" . $row['Username'] . "</a></td>";
			echo "<td><img src='" . $row['profile_pic'] . "' alt='Profile Picture'></td>";
			echo "<td>" . $row['Role'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "No results found.";
	}
}



if (isset($_GET['role'])) {
	$role = mysqli_real_escape_string($conn, $_GET['role']);
  
	// Fetch freelancers from the database with the matching role
	$search_query = "SELECT Username, profile_pic, Role FROM freelancer_details WHERE Role LIKE '%$role%'";
	$result = mysqli_query($conn, $search_query);
  
	$freelancers = array();
	while ($row = mysqli_fetch_assoc($result)) {
	  $freelancers[] = $row;
	}
  
	// Send the JSON response back to the JavaScript code
	header('Content-Type: application/json');
	echo json_encode($freelancers);
  }

?>