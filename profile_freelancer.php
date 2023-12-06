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
        $profile_picc = $user_data['profile_pic'];
        $portfolio = $user_data['Portfolio'];
    }
}

$current_jobs_query = "SELECT * FROM jobs WHERE freelancer_username = '$login_username' AND status = 'ongoing'";
$current_jobs_result = mysqli_query($conn, $current_jobs_query);

$rating_query = "SELECT AVG(review) AS average_rating FROM jobs WHERE freelancer_username='$login_username' AND review <> 0";
$rating_result = mysqli_query($conn, $rating_query);

$average_rating = 0; // Default value if no reviews
if (mysqli_num_rows($rating_result) > 0) {
    $rating_data = mysqli_fetch_assoc($rating_result);
    $average_rating = $rating_data['average_rating'];
}

$review_count_query = "SELECT COUNT(review) AS num_reviews FROM jobs WHERE freelancer_username='$login_username' AND review <> 0";
$review_count_result = mysqli_query($conn, $review_count_query);

$num_reviews = 0; // Default value if no reviews
if (mysqli_num_rows($review_count_result) > 0) {
    $review_count_data = mysqli_fetch_assoc($review_count_result);
    $num_reviews = $review_count_data['num_reviews'];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/bootstrap.css" />
    
    <link
      href="https://fonts.cdnfonts.com/css/american-typewriter"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
    />
    <title>Freelance Portal - Job Listings</title>
    
</head>
<body>
    <div class="nav-bar">
        <div class="container">
            <ul class="nav">
                <li><a href="profile_freelancer.php">My profile</a></li>
                <li><a href="index.html">Sign out</a></li>
            </ul>
        </div>
    </div>
    <div class="content">
      <div class="container">
        <div class="main">
          <h2>My Profile</h2>
          <hr />
          <div>

            <div class="event-body clearfix">
                  <h3 style="font-size: 50px" >
                    <?php echo $_SESSION['username']; ?>
                  </h3>
                  <h2>Rating:<div class="rating">
    <?php
    // $fullStars = floor($average_rating);
    // $halfStar = round($average_rating - $fullStars);
    // $emptyStars = 5 - ($fullStars + $halfStar);
    
    // for ($i = 0; $i < $fullStars; $i++) {
    //     echo '<span class="fa fa-star checked"></span>';
    // }
    
    // if ($halfStar) {
    //     echo '<span class="fa fa-star-half checked"></span>';
    // }
    
    // for ($i = 0; $i < $emptyStars; $i++) {
    //     echo '<span class="fa fa-star"></span>';
    // }
    // echo '<span class="rating-text">(' . $num_reviews . ' ' . ($num_reviews == 1 ? 'review' : 'reviews') . ')</span> <br>';
    // echo '<span class="rating-text">Average rating: ' .  round($average_rating,2) . ' </span>';

    ?>

<?php
  // Assume $averageRating is the average rating (e.g., 3.23)
//   $averageRating = 3.23;

    echo '<span class="rating-text">(' . $num_reviews . ' ' . ($num_reviews == 1 ? 'review' : 'reviews') . ')</span> <br>';
    echo '<span class="rating-text">Average rating: ' .  round($average_rating,2) . ' </span>';
  // Calculate the number of full stars and half star
  $fullStars = floor($average_rating);
  $halfStar = round($average_rating - $fullStars);

  // Display stars based on the calculated values
  echo '<div class="rating">';
  for ($i = 0; $i < $fullStars; $i++) {
    echo '<span class="fa fa-star"></span>';
  }

  if ($halfStar) {
    echo '<span class="fa fa-star-half"></span>';
  }

  echo '</div>';
  ?>
    
    
</div></h2>




                  <!-- <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star"></span> -->
                    <br>
                    <div class="profile-picture-container">
                    <div class="profile_picture_style" style="text-align: center;">
                    <div class="portfolio">
                    <img width="100%" src="<?php echo $profile_picc; ?>" alt="Profile Picture">
</div>


                    <hr>
                    <h2>About</h2>

                    <h3>
                    Phone Number: <a href="tel:<?php echo $phone_number; ?>"><?php echo $phone_number; ?></a>
                  <br>
                  Roles: <?php echo $roles; ?>
                </h3> 
                <hr>
                <h2>Summary</h2>
                <h3>
                  <?php echo $summary; ?>
                </h3>
                <br>
                    </div>
                </div>
                <hr>
            </div>
            <div class="portfolio">
                <h1>Portfolio</h1>
                <img width="100%" src="<?php echo $portfolio; ?>" alt="Portfolio">
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="section">
    <div class="title">
        <h1>Current Jobs</h1>
    </div>

    <div class="current-jobs" style="text-align:center">
    <hr>
    <?php
    while ($job_row = mysqli_fetch_assoc($current_jobs_result)) {
        $customer_username = $job_row['customer_username'];
        $status = $job_row['status'];
        // $job_id = $job_row['job_id']; // Include the job_id for reference

        // Fetch customer's details using $customer_username

        echo '<div class="job-item">';
        echo '<h2>Customer: ' . $customer_username . '</h2>';
        echo '<p>Status: ' . $status . '</p>';
        
        if ($status === 'ongoing') {
            echo '<form method="POST" action="mark_job_finished.php">';
            echo '<input type="hidden" name="customer_username" value="' . $customer_username . '">';
            // echo '<input type="hidden" name="job_id" value="' . $job_id . '">';
            echo '<input type="submit" name="submit" value="Mark as Finished">';
            echo '</form>';
            echo '<hr>';
        }
        
        echo '</div>';
    }
    ?>
</div>

</div>
<footer class="footer-enhanced">
        <div class="footer-section">
            <h3>Freelance Workers Portal</h3>
            <ul class="footer-links">
                <li><a href="index.html">Homepage</a></li>
                <li><a href="contact.html">Contact</a></li>
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