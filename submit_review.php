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


if (isset($_POST['submit']) && isset($_SESSION['username']) && isset($_POST['freelancer_username']) && isset($_POST['rating'])) {
    $customer_username = $_SESSION['username'];
    $freelancer_username = $_POST['freelancer_username'];
    $rating = $_POST['rating'];

    // Update job status to 'finished' and set the review column
    $update_query = "UPDATE jobs SET status = 'finished', review = '$rating' WHERE customer_username = '$customer_username' AND freelancer_username = '$freelancer_username' AND status = 'review_pending'";
    
    if (mysqli_query($conn, $update_query)) {
        // Show JavaScript alert and redirect back to the customer's profile page
        echo '<script>alert("Review submitted successfully!");</script>';
        echo '<script>window.location.href = "profile_customer.php";</script>'; // Change this line to the correct profile page
        exit();
    } else {
        echo "Error updating job status and review: " . mysqli_error($conn);
    }
}

?>


