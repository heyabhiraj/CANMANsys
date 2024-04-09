<?php
session_start();

include('./admin/config.php');
// // Database connection details 



// // Function to sanitize user input (prevent SQL injection)
// function sanitize_input($data) {
//     global $conn;
//     $data = trim($data);
//     $data = stripslashes($data);
//     $data = htmlspecialchars($data);
//     return $conn->real_escape_string($data);
// }

// Get form data
$fname = sanitize_input($_POST['fname']);
$lname = sanitize_input($_POST['lname']);
$email = sanitize_input($_POST['email']);
$phone = sanitize_input($_POST['phone']);
$password = password_hash(sanitize_input($_POST['password']), PASSWORD_DEFAULT); // Securely hash password

// Validate data (example, add more validations as needed)
$errors = [];
if (empty($fname)) {
    $errors[] = "First name is required.";
}
if (empty($lname)) {
    $errors[] = "Last name is required.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

// Check for existing email
$sql_check_email = "SELECT email FROM registered_user WHERE email = '$email'";
$result = mysqli_query($conn, $sql_check_email);
if (mysqli_num_rows($result) > 0) {
    $errors[] = "Email already exists.";
}

// If no errors, insert data into database
if (empty($errors)) {
    $sql = "INSERT INTO registered_user (fname, lname, email, phone, pass, user_status, user_role )
            VALUES ('$fname', '$lname', '$email', '$phone', '$password','active', 'regular')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: home.php"); // Redirect to login page on success
        exit();
    } else {
        $errors[] = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close connection
closeDB();

// Store errors in session (optional)
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors); // Combine errors into a string
    header("Location: register.php"); // Redirect back to registration form with errors
    exit();
}
?>
