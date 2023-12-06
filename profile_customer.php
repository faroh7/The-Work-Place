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

    $sql_query = "SELECT * FROM customer_details WHERE Username='$login_username'";
    $result = mysqli_query($conn, $sql_query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        $profile_picc = $user_data['profile_pic'];
    }

    $_SESSION['username'] = $login_username;

}

if (isset($_GET['action']) && $_GET['action'] === 'hire' && isset($_GET['freelancer'])) {
  $customer_username = $_SESSION['username'];
  $freelancer_username = $_GET['freelancer'];
  $status = 'ongoing';

  // Insert the job into the 'jobs' table
  $insert_query = "INSERT INTO jobs (customer_username, freelancer_username, status) 
                   VALUES ('$customer_username', '$freelancer_username', '$status')";
  if (mysqli_query($conn, $insert_query)) {
      echo '<script>alert("Successfully hired freelancer!");</script>';
  } else {
      echo '<script>alert("Error hiring freelancer.");</script>';
  }
}

$current_jobs_query = "SELECT * FROM jobs WHERE customer_username = '$login_username' AND status = 'ongoing'";
$current_jobs_result = mysqli_query($conn, $current_jobs_query);

$review_jobs_query = "SELECT * FROM jobs WHERE customer_username = '$login_username' AND status = 'review_pending'";
$review_jobs_result = mysqli_query($conn, $review_jobs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelance Portal - Job Listings</title>
    
    <link rel="stylesheet" href="css/style.css" />
    <link
      href="https://fonts.cdnfonts.com/css/american-typewriter"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
    />
    
    
    
    
   

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: linear-gradient(to bottom right, #f9cb4e, #f9739b);
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center; 
        }
        th {
            background-color: #f2f2f2;
        }
        table img {
            max-width: 200px;
            max-height: 300px;
        }
    </style>
    <script src="search_freelancers.php"></script>
    <script src="profileview_freelancer.php"></script>

    <script>
        function checkRating(input) {
        if (input.value > 5 || input.value < 1) {
            alert("Please enter a value 1-5.");
            input.value = '';
            return false;    
        } 
    }
    </script>
    </head>
    <body>
    <div class="nav-bar">
        <div class="container">
            <ul class="nav">
                <li><a href="profile_customer.php">My profile</a></li>
                <li><a href="index.html">Sign out</a></li>
            </ul>
        </div>
    </div>
    <h3 style="font-size: 50px">
        Hi <?php echo $_SESSION['username']; ?>
    </h3>

    <form class="example" onsubmit="searchFreelancers(); return false;">
        <input type="text" placeholder="Search.." name="search" id="search">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>

    <div id="searchResults">
    </div>

    <div class="section">
        <div class="title">
            <h1>Explore Our Services</h1>
        </div>

        <div class="services">
    <div class="card" id="GraphicDesign">
        <img src="img/graphic.jpeg" alt="Graphic Design Image">
        <h2><a href="#" onclick="populateTable('GraphicDesign')">Graphic Design</a></h2>
    </div>
    <div class="card" id="ContentWriting">
        <img src="img/contentwriting.jpeg" alt="ContentWriting Image">
        <h2><a href="#" onclick="populateTable('ContentWriting')">Content Writing</a></h2>
    </div>
    <div class="card" id="WebDevelopment">
        <img src="img/webdev.jpeg" alt="Web Development Image">
        <h2><a href="#" onclick="populateTable('WebDevelopment')">Web Development</a></h2>
    </div>
    <div class="card" id="DigitalMarketing">
        <img src="img/digital.webp" alt="Digital Marketing Image">
        <h2><a href="#" onclick="populateTable('DigitalMarketing')">Digital Marketing</a></h2>
    </div>
</div>

<!-- Table to be populated -->
<div id="tableContainer">
  <table id="freelancerTable">
    <tr>

    </tr>
  </table>
</div>
<div class="section" style="text-align:center">
    <div class="title">
        <h1>Current Jobs with below freelancers</h1>
    </div>

    <div class="current-jobs">
        <?php
        while ($job_row = mysqli_fetch_assoc($current_jobs_result)) {
            $freelancer_username = $job_row['freelancer_username'];
            // Fetch freelancer's details using $freelancer_username
            echo '<div class="job-item">';
            echo '<h2>' . $freelancer_username . '</h2>';
            // Display other job information as needed
            echo '</div>';
        }
        ?>
    </div>
</div>
<div class="rating-jobs" style="text-align:center">
    <hr>
    <?php
    while ($job_row = mysqli_fetch_assoc($review_jobs_result)) {
      $freelancer_username = $job_row['freelancer_username'];
      $status = $job_row['status'];
        // Fetch customer's details using $customer_username
        echo '<h1>Jobs Pending Review</h1>';
        echo '<div class="job-item">';
        echo '<h2>' . $freelancer_username . '</h2>';
        echo '<p>Status: ' . $status . '</p>';
        
        // if ($status === 'review pending') {
          // echo '<div class="job-item">';
          // echo '<h2>' . $freelancer_username . '</h2>';
          echo '<form method="POST" action="submit_review.php">'; 
          // echo '<input type="hidden" name="job_id" value="' . $job_id . '">';
          echo '<input type="hidden" name="freelancer_username" value="' . $freelancer_username . '">';
          echo 'Rating(Out of 5) : <input type="number" name="rating" min="1" max="5" required oninput="checkRating(this)">';
          echo '<input type="submit" name="submit" value="Submit Review">';
          echo '</form>';
          echo '</div>';
      // }
        
        echo '</div>';
    }
    ?>
</div>



<hr>
        
            <script src="js/search.js" charset="utf-8"></script>
           
</body>
<footer class="footer-enhanced">
        <div class="footer-section">
            <h3>Freelance Workers Portal</h3>
            <ul class="footer-links">
                <li><a href="index.html">Homepage</a></li>
                <li><a href="about.html">Contact</a></li>
            </ul>
            <p>&copy; 2023 Freelance Workers Portal</p>
        </div>
    
        <div class="footer-section">
            <h4>Contact Info</h4>
            <p><i class="fa fa-map-marker"></i> South C amana, Nairobi, Kenya</p>
            <p><i class="fa fa-phone"></i> +254726130837</p>
            <p><i class="fa fa-envelope"></i> <a href="mailto:fardosanur811@gmail.com">Email us</a></p>
        </div>
    
        <div class="footer-section">
            <h4>Our Commitment</h4>
            <p>We are dedicated to providing the best platform for freelancers and clients to connect and succeed together.</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/FWP"><i class="fa fa-facebook"></i></a>
                <a href="https://www.twitter.com/FWP"><i class="fa fa-twitter"></i></a>
                <a href="https://www.linkedin.com/FWP"><i class="fa fa-linkedin"></i></a>
            </div>
        </div>
    </footer>