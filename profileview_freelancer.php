<?php
session_start();

// Check if the username parameter is provided in the URL
if (isset($_GET['username'])) {
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database_name = "freelance_workers_portal";
    $conn = mysqli_connect($server_name, $username, $password, $database_name);

    if (!$conn) {
        die("Connection Failed: " . mysqli_connect_error());
    }

    $username_param = $_GET['username'];

    // Fetch freelancer data from the database based on the provided username
    $sql_query = "SELECT * FROM freelancer_details WHERE Username='$username_param'";
    $result = mysqli_query($conn, $sql_query);

    if (mysqli_num_rows($result) == 1) {
        $freelancer_data = mysqli_fetch_assoc($result);

        $my_username = $_SESSION['username'];

        // You can now access other details of the freelancer using $freelancer_data array
        $username_profile = $freelancer_data['Username'];
        $profile_pic = $freelancer_data['profile_pic'];
        $phone_number = $freelancer_data['Phone_Number'];
        $roles = $freelancer_data['Role']; 
        $summary = $freelancer_data['Summary'];
        $portfolio = $freelancer_data['Portfolio'];

        $average_rating = 0; // Default value if no reviews
        $review_count = 0; // Default value for review count

        $rating_query = "SELECT AVG(review) AS average_rating FROM jobs WHERE freelancer_username='$username_profile' AND review IS NOT NULL";
        $rating_result = mysqli_query($conn, $rating_query);


        $rating_count_query = "SELECT COUNT(*) AS review_count FROM jobs WHERE freelancer_username='$username_profile' AND review IS NOT NULL";
        $rating_count_result = mysqli_query($conn, $rating_count_query);
        $rating_count_data = mysqli_fetch_assoc($rating_count_result);

        $review_count = $rating_count_data['review_count'];

        if ($review_count > 0) {
            $rating_data = mysqli_fetch_assoc($rating_result);
            $average_rating = $rating_data['average_rating'];
        }



// // Fetch average rating for the freelancer
// $rating_query = "SELECT AVG(review) AS average_rating FROM jobs WHERE freelancer_username='$username_profile' AND review IS NOT NULL";
// $rating_result = mysqli_query($conn, $rating_query);

// $average_rating = 0; // Default value if no reviews
// if (mysqli_num_rows($rating_result) > 0) {
//     $rating_data = mysqli_fetch_assoc($rating_result);
//     $average_rating = $rating_data['average_rating'];
// }


    } else {
        echo "Freelancer not found.";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Username parameter not provided.";
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      href="https://fonts.cdnfonts.com/css/american-typewriter"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
    />
    <title>Profile</title>

    <script>
function confirmHire(freelancerUsername) {
    if (confirm("Are you sure you want to hire " + freelancerUsername + "?")) {
        // Redirect to profile_customer.php with parameters for hiring
        window.location.href = "profile_customer.php?action=hire&freelancer=" + encodeURIComponent(freelancerUsername);
    }
}
</script>

  </head>

  <body>


    <!-- nav bar -->
    <div class="nav-bar">
      <div class="container">
        <ul class="nav">
          <li><a href="profile_customer.php">My profile</a></li>
          <li><a href="index.html">Sign out</a></li>
        </ul>
      </div>
    </div>

            <!-- content -->

    <div class="content">
      <div class="container">
        <div class="main">
          <hr />
          <div>

            <div class="event-body clearfix">
                  <h2 style="font-size: 50px" >
                    Hi, <?php echo $my_username; ?>
                  </h2>

                  <h3 style="font-size: 50px" >
                    Profile for <?php echo $username_profile; ?>
                  </h3>


                  <h2>Rating:<div class="rating">
    <?php
    $fullStars = floor($average_rating);
    $halfStar = round($average_rating - $fullStars);
    $emptyStars = 5 - ($fullStars + $halfStar);
    
    for ($i = 0; $i < $fullStars; $i++) {
        echo '<span class="fa fa-star checked"></span>';
    }
    
    if ($halfStar) {
        echo '<span class="fa fa-star-half checked"></span>';
    }
    
    for ($i = 0; $i < $emptyStars; $i++) {
        echo '<span class="fa fa-star"></span>';
    }
    echo '<span class="rating-text">(' .  $review_count . ' reviews)</span>';

    ?>
    
                  </h2>

                  <!-- <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star"></span> -->
                    <br>
                    <div class="profile_picture_style" style="text-align: center;">
                    <div class="portfolio">
                    <img width="100%" src="<?php echo $profile_pic; ?>" alt="Profile Picture">
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
            <button onclick="confirmHire('<?php echo $username_profile; ?>')">Hire <?php echo $username_profile; ?></button>
            <!-- <button onclick="profile_customer.php" name="hire" type="submit">Hire <?php echo $username_profile; ?></button> -->
            </div>
          </div>
        </div>
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