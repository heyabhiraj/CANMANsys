<?php
session_start();

include('./admin/config.php');
//  Database connection details 

// Get form data
$fname = sanitize_input($_POST['fname']);
$lname = sanitize_input($_POST['lname']);
$email = sanitize_input($_POST['email']);
$phone = sanitize_input($_POST['phone']);
$cabin = sanitize_input($_POST['faculty-cabin']);
$ext = sanitize_input($_POST['faculty-ext']);
$password = password_hash(sanitize_input($_POST['password']), PASSWORD_DEFAULT); // Securely hash password


if(empty($cabin) || empty($ext)){
    $utype = 'student';
    $cabin="NULL";
    $ext="NULL";
    $status = 'active';
} else {
    $utype = 'faculty';
    $status = 'inactive';
}


// Validate data (example, add more validations as needed)
$errors = [];
if (!preg_match("/^[a-zA-Z'-]+(\s[a-zA-Z'-]+)*$/", $fname)) {
    $errors[] = "First name is invalid.";
}
if (!preg_match("/^[a-zA-Z'-]+$/", $lname)) {
    $errors[] = "Last name is invalid.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}
$phone = preg_replace("/[^0-9]/", "", $phone);
if (!preg_match("/^[1-9][0-9]{9}$/", $phone) || strlen($phone) !== 10) {
    $errors[] = "Invalid phone format.";
}

// Check for existing email
$sql_check_email = "SELECT email FROM registered_user WHERE email = '$email'";
$result = mysqli_query($conn, $sql_check_email);
if (mysqli_num_rows($result) > 0) {
    $errors[] = "Email already exists.";
}

// If no errors, insert data into database
if (empty($errors)) {
    $sql = "INSERT INTO registered_user (fname, lname, email, phone, pass, user_status, user_role, faculty_cabin, faculty_extension, user_type)
            VALUES ('$fname', '$lname', '$email', '$phone', '$password','$status', 'regular', '$cabin', '$ext','$utype')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['rsuccess'] = "Registration successful! Please log in.";
        if($utype === 'faculty'){
            $userId = mysqli_insert_id($conn);
            $sql = "INSERT INTO user_wallet (user_id) VALUES ('$userId')";
            mysqli_query($conn, $sql);}
        header("Location: home.php"); // Redirect to login page on success
        exit();
    } else {
        $errors[] = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close connection
closeDB();

// Store errors and data in session (optional)
if (!empty($errors)) {
    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['cabin'] = $cabin;
    $_SESSION['ext'] = $ext;

    $_SESSION['error'] = implode("<br>", $errors); // Combine errors into a string
    header("Location: register.php"); // Redirect back to registration form with errors
    exit();
}
?>









