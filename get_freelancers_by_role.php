<?php
$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "freelance_workers_portal";
$conn = mysqli_connect($server_name, $username, $password, $database_name);

//now check the connection
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
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
