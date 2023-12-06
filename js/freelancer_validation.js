//Validation for Freelancer//
function validateForm() {
    // Get form input values
    var username = document.forms["signup_Freelancer"]["Username_f"].value;
    var email = document.forms["signup_Freelancer"]["Email_f"].value;
    var profilepic = document.forms["signup_Freelancer"]["profile_pic_f"].value;
    var password = document.forms["signup_Freelancer"]["Password_f"].value;
    var firstName = document.forms["signup_Freelancer"]["First_Name_f"].value;
    var lastName = document.forms["signup_Freelancer"]["Last_Name_f"].value;
    var phoneNumber = document.forms["signup_Freelancer"]["Phone_Number_f"].value;
    var roles = document.forms["signup_Freelancer"]["Role[]"];
    var summary = document.forms["signup_Freelancer"]["Summary"].value;
    var portfolio = document.forms["signup_Freelancer"]["portfolio"].value;
  
    // Check if required fields are filled
    if (username === "" || email === "" ||  profilepic === "" || password === "" || firstName === "" || lastName === "" || phoneNumber === "" || summary === "" || portfolio === "") {
      alert("Please fill in all required fields and upload all files needed");
      return false;
    }
  
    // Check email format using a regular expression
    var emailRegex = /^\S+@\S+\.\S+$/;
    if (!emailRegex.test(email)) {
      alert("Please enter a valid email address.");
      return false;
    }
  
    // Check phone number format using a regular expression
    var phoneRegex = /^254\d{9}$/;
    if (!phoneRegex.test(phoneNumber)) {
      alert("Please enter a valid phone number in the format '254xxxxxxxxx'.");
      return false;
    }
  
    // Check if at least one role is selected
    var isRoleSelected = false;
    for (var i = 0; i < roles.length; i++) {
      if (roles[i].checked) {
        isRoleSelected = true;
        break;
      }
    }
    if (!isRoleSelected) {
      alert("Please select at least one role.");
      return false;
    }
  
    // If all validations pass, the form is valid
    return true;
  }