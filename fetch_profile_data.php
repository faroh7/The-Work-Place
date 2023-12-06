<?php
session_start();
// Include database connection code
$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "freelance_workers_portal";
$conn = mysqli_connect($server_name, $username, $password, $database_name);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Fetch user data from the database based on the session username
if (isset($_SESSION['username'])) {
    $login_username = $_SESSION['username'];

    $sql_query = "SELECT * FROM freelancer_details WHERE Username='$login_username'";
    $result = mysqli_query($conn, $sql_query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        // Store user details in variables
        $phone_number = $user_data['Phone_Number'];
        $roles = $user_data['Role'];
        $summary = $user_data['Summary'];
    }
}
?>
