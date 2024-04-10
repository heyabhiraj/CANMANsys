<?php
session_start();

// Include database connection (replace with your credentials)
include('./admin/config.php');

$user_id = $_SESSION['user_id'];
$first_name = sanitize_input($_POST['fname']);
$last_name = sanitize_input($_POST['lname']);
$phone = sanitize_input($_POST['phone']);
$cabin = sanitize_input($_POST['faculty-cabin']);
$ext = sanitize_input($_POST['faculty-ext']);


// Update user details (excluding email)
$sql = "UPDATE registered_user SET fname = ?, lname = ?, phone = ?,faculty_cabin = ?,faculty_extension = ? WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssssi", $first_name, $last_name, $phone,$cabin, $ext, $user_id);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    $_SESSION['success'] = "Profile details updated successfully!"; // Success message
} else {
    $_SESSION['error'] = "Error updating profile: " . mysqli_error($conn); // Error message
}

mysqli_stmt_close($stmt);

// Handle password change (optional)
if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
    // Verify current password before updating
    $sql = "SELECT pass FROM registered_user WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id); // bind  param with int
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($_POST['new_password']==$_POST['confirm_password']) {
        // Hash new password before update
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $sql = "UPDATE registered_user SET pass = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $new_password, $user_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $_SESSION['success'] .= "<br>Password changed successfully!";
        } else {
            $_SESSION['error'] .= "<br>Error updating password: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] .= "<br> Cannot update password: Password do not match ";
    }

    mysqli_stmt_close($stmt);
}

// Handle adding default payment system (optional)

    mysqli_close($conn); // Close connection
    // Redirect to profile page after processing
    header("Location: profile.php");
    exit();
?>    