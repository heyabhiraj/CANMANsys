<?php

include_once('../admin/config.php');
if (isset($_POST['userId'], $_POST['totalAmount'], $_POST['paymentMethod'])) {
    $userId = $_POST['userId'];
    $amount = $_POST['totalAmount'];
    $paymentMode = $_POST['paymentMethod'];
    $errors = [];

    // Check if amount is a valid numeric format
    if (preg_match('/^[0-9]+$/', $amount)) {
        // Sanitize input to prevent SQL injection
        $userId = intval($userId);
        $amount = intval($amount); // Assuming amount is an integer
        // Assuming paymentMode is a string, you may need to sanitize it accordingly

        // Assuming $conn is the database connection object
        $sql = "UPDATE user_wallet SET available_limit = available_limit + $amount WHERE user_id = $userId AND available_limit + $amount <= credit_limit";
        
        // Execute the SQL query
        if ($conn->query($sql)) {
            $errors[] = "Amount Updated.";
        } else {
            $errors[] = "Error updating amount: " . $conn->error;
        }
    } else {
        $errors[] = "Invalid Amount format.";
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors); // Combine errors into a string
        header("Location: pending_payments.php"); // Redirect back with errors
        exit; // Terminate script execution after redirect
    }
}
