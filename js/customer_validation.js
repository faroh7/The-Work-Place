function validateForm() {
    // Get form input values
    var username = document.forms["signup_Customer"]["Username"].value;
    var email = document.forms["signup_Customer"]["Email"].value;
    var profilepic = document.forms["signup_Customer"]["profile_pic"].value;
    var password = document.forms["signup_Customer"]["Password"].value;
    var firstName = document.forms["signup_Customer"]["First_Name"].value;
    var lastName = document.forms["signup_Customer"]["Last_Name"].value;
    var phoneNumber = document.forms["signup_Customer"]["Phone_Number"].value;

    // Check if required fields are filled
    if (username === "" || email === "" ||  profilepic === "" || password === "" || firstName === "" || lastName === "" || phoneNumber === "") {
      alert("Please fill in all required fields and upload all files needed.");
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
      alert("Please enter a valid phone number in the format '254*********'.");
      return false;
    }

    // If all validations pass, the form is valid
    return true;
  }
