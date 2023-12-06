
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
}

$jobs_query = "SELECT * FROM jobs";
$jobs_result = mysqli_query($conn, $jobs_query);

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
   

    <!-- nav bar -->
    <div class="nav-bar">
      <div class="container">
        <ul class="nav">
          <li><a href="index.html">Sign out</a></li>
        </ul>
      </div>
    </div>
     <!-- content -->

     <h3 style="font-size: 50px" >
                    Hi Admin - <?php echo $_SESSION['username']; ?>
                    <!-- Hi <?php echo $login_username; ?> -->
                  </h3>

            <form class="example" onsubmit="searchFreelancers(); return false;" >
              <input type="text" placeholder="Search.." name="search" id="search">
              <button type="submit"><i class="fa fa-search"></i></button>
            </form>

            <div id="searchResults">
            </div>


            <table>
    <thead>
        <tr>
            <th>Customer Username</th>
            <th>Freelancer Username</th>
            <th>Status</th>
            <th>Review</th>
        </tr>
    </thead>
    <tbody>
    <?php
        while ($job = mysqli_fetch_assoc($jobs_result)) {
            echo "<tr>";
            echo "<td>" . $job['customer_username'] . "</td>";
            echo "<td>" . $job['freelancer_username'] . "</td>";
            echo "<td>" . $job['status'] . "</td>";
            echo "<td>" . $job['review'] . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<hr>
        
            <script src="js/admin.js" charset="utf-8"></script>

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