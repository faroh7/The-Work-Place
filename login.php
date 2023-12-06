<?php
session_start();

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "freelance_workers_portal";
$conn = mysqli_connect($server_name, $username, $password, $database_name);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

if (isset($_POST['login_submit'])) {
    $login_username = mysqli_real_escape_string($conn, $_POST['uname']);
    $login_password = mysqli_real_escape_string($conn, $_POST['psw']);

    if ($login_username === "Fardosa" && $login_password === "Fardosa") {
        // Admin login
        $_SESSION['username'] = $login_username;
        header("Location: admin.php");
        exit();
    } else {

    // Fetch user data from the database based on the provided username
    $sql = "SELECT * FROM login WHERE Username=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login_username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($login_password, $user_data['Password'])) {
            // Password is correct, set up a session and log in the user
            $_SESSION['username'] = $user_data['Username'];

            // Check if the user is in customer_details or freelancer_details
            $sql_customer = "SELECT * FROM customer_details WHERE Username=?";
            $sql_freelancer = "SELECT * FROM freelancer_details WHERE Username=?";

            $stmt_customer = mysqli_prepare($conn, $sql_customer);
            mysqli_stmt_bind_param($stmt_customer, "s", $login_username);
            mysqli_stmt_execute($stmt_customer);
            $result_customer = mysqli_stmt_get_result($stmt_customer);

            $stmt_freelancer = mysqli_prepare($conn, $sql_freelancer);
            mysqli_stmt_bind_param($stmt_freelancer, "s", $login_username);
            mysqli_stmt_execute($stmt_freelancer);
            $result_freelancer = mysqli_stmt_get_result($stmt_freelancer);

            if (mysqli_num_rows($result_customer) == 1) {
                // User is a customer
                header("Location: profile_customer.php");
                exit();
            } elseif (mysqli_num_rows($result_freelancer) == 1) {
                // User is a freelancer
                header("Location: profile_freelancer.php");
                exit();
            } else {
                // Neither customer nor freelancer (handle this case as needed)
                echo "Unknown user type.";
                exit();
            }
        } else {
            // Invalid password
            echo "Invalid password. Please try again.";
        }
    } else {
        // User not found
        echo "User not found. Please check your username.";
    }
}
} 
?>