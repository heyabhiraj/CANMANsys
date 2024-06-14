<?php
session_start();

include('./admin/config.php'); // Ensure this path is correct and it includes database connection setup


// Get form data
$fname = sanitize_input($_POST['fname']);
$lname = sanitize_input($_POST['lname']);
$email = sanitize_input($_POST['email']);
$phone = sanitize_input($_POST['phone']);
$cabin = sanitize_input($_POST['faculty-cabin']);
$ext = sanitize_input($_POST['faculty-ext']);
$password = password_hash(sanitize_input($_POST['password']), PASSWORD_DEFAULT); // Securely hash password

if (empty($cabin) || empty($ext)) {
    $utype = 'student';
    $cabin = null;
    $ext = null;
    $status = 'active';
} else {
    $utype = 'faculty';
    $status = 'inactive';

    // Check for duplicate entry for faculty cabin
    $checkSql = "SELECT * FROM registered_user WHERE faculty_cabin = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $cabin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'Error: Duplicate entry for faculty cabin.';
        exit;
    }
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
$sql_check_email = "SELECT email FROM registered_user WHERE email = ?";
$stmt = $conn->prepare($sql_check_email);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $errors[] = "Email already exists.";
}

// If no errors, insert data into database
if (empty($errors)) {
    $sql = "INSERT INTO registered_user (fname, lname, email, phone, pass, user_status, user_role, faculty_cabin, faculty_extension, user_type)
            VALUES (?, ?, ?, ?, ?, ?, 'regular', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $fname, $lname, $email, $phone, $password, $status, $cabin, $ext, $utype);

    if ($stmt->execute()) {
        $_SESSION['rsuccess'] = "Registration successful! Please log in.";
        if ($utype === 'faculty') {
            $userId = $conn->insert_id;
            $sql = "INSERT INTO user_wallet (user_id) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }
        header("Location: home.php"); // Redirect to home page on success
        exit();
    } else {
        $errors[] = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();

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
