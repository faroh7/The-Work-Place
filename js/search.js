//for the search bar 
function searchFreelancers() {
    var searchQuery = document.getElementById("search").value.trim();
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("searchResults").innerHTML = this.responseText;
      }
    };

    xhttp.open("GET", "search_freelancers.php?search=" + searchQuery, true);
    xhttp.send();

    return false; // Prevent the form from submitting and page refreshing
  }


  function populateTable(role) {
    // Make an Ajax request to fetch the data for the selected role
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Parse the JSON response and populate the table
        var data = JSON.parse(this.responseText);
        var table = document.getElementById("freelancerTable");
        table.innerHTML = "<tr><th>Username</th><th>Profile Picture</th><th>Role</th></tr>";

        for (var i = 0; i < data.length; i++) {
          var row = table.insertRow(-1);
          var usernameCell = row.insertCell(0);
          var profilePicCell = row.insertCell(1);
          var roleCell = row.insertCell(2);

          usernameCell.innerHTML = "<a href='profileview_freelancer.php?username=" + data[i].Username + "'>" + data[i].Username + "</a>";
          profilePicCell.innerHTML = "<img src='" + data[i].profile_pic + "' alt='Profile Picture'>";
          roleCell.innerHTML = data[i].Role;
        }
      }
    };

    xhttp.open("GET", "get_freelancers_by_role.php?role=" + role, true);
    xhttp.send();
  }

