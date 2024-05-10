<?php

include_once('../admin/config.php');

if(isset($_POST['userId']) && isset($_POST['totalAmount'])   ){

    $userId = $_POST['userId'];
    $amount = $_POST['totalAmount'];
    $paymentMode = $_POST['paymentMethod'];

global  $conn;


$sql = "UPDATE user_wallet SET available_limit = available_limit + $amount WHERE user_id = $userId AND available_limit + $amount <= credit_limit";
$res = $conn->query($sql);

}
header("Location: pending_payments.php");
?>