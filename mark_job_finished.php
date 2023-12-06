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

if (isset($_POST['submit']) && isset($_POST['customer_username'])) {
    $customer_username = $_POST['customer_username'];

    // Update job status to 'review pending' for the specific customer
    $update_query = "UPDATE jobs SET status = 'review_pending' WHERE customer_username = '$customer_username'";
    if (mysqli_query($conn, $update_query)) {
        // Show JavaScript alert and redirect back to the freelancer's profile page
        echo '<script>alert("Job marked as finished!");</script>';
        echo '<script>window.location.href = "profile_freelancer.php";</script>';
        exit();
    } else {
        echo "Error updating job status: " . mysqli_error($conn);
    }
}
?>
