
<?php
include("./admin/config.php");
//database connection

session_start();
// Process login form submission

$email = sanitize_input($_POST['email']);
$password = sanitize_input($_POST['password']); 


if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $_SESSION['error']= "Invalid Email ";
    header("Location : login.php");
    exit();
}

if ( !isset($_POST['email'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
    $_SESSION['error']= "Please fill both the username and password fields!";
    header("Location : login.php");
    exit();
	
}
echo $_POST['email'];
echo $_POST['password'];


    // Retrieve user from database
    $stmt = $conn->prepare("SELECT user_id, fname, email, pass, user_role , user_type FROM registered_user WHERE email = ?");
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Account exists, now we verify the password
        $row = $result->fetch_assoc();
        $id = $row['user_id'];
        $hashed_password = $row['pass'];
        
        if (password_verify($password, $hashed_password)) {
            // Verification success! User has logged in
            // Create sessions
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $id;
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['Role'] = $row['user_role'];
            $_SESSION['Type'] = $row['user_type'];
            
            // Redirect to the admin dashboard or any other page
            if ($_SESSION['Role'] == 'admin') {
                          header("Location: admin/dashboard.php");
                      } else {
                          header("Location: home.php");
                      }
                      exit();
        }   else {
            // Incorrect password
            $_SESSION['error'] = "Invalid email or password.";
            }

    }   else {
        // Account not found
        $_SESSION['error'] = " Account not found";
        }

    mysqli_stmt_close($stmt); // Close prepared statement
    mysqli_close($conn); // Close connection
    
    header("Location: login.php"); // Redirect back to login form with error (if not successful)
    exit();
?>