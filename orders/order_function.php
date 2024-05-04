<?php

include_once('../admin/config.php');

if(isset($_POST['action']) && !empty($_POST['action'])){
  $action = $_POST['action'];
  $input = $_POST['input'];



  switch($action){
    case 'acceptOrder':
      acceptOrder($input);
      break;
    case 'deliverOrder':
      deliverOrder($input);
      break;
    case 'cancelOrder':
      cancelOrder($input);
      break;
    case 'orderDetails':
      $data = orderDetails($input);
      break;
  }

}


/**
 * Returns the first 10 Pending Orders
 * @return array $rows 
 */
function pendingOrders() : array
{
  global $conn;
  // SQL query to get column information
  $sql = "SELECT * FROM item_order INNER JOIN registered_user ON item_order.user_id = registered_user.user_id INNER JOIN item_list ON item_order.item_id = item_list.item_id WHERE order_status = 'Pending' ORDER BY item_order.created_at ASC LIMIT 10 ";
  $res = $conn->query($sql) or die("Could not get Orders");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_ASSOC);
  } else {
    $rows = [];
  }
  return $rows;
}


/**
 * Returns the first 10 Cooking Orders
 * @return array $rows 
 */
function cookingOrders() : array
{
  global $conn;
  // SQL query to get column information
  $sql = "SELECT * FROM item_order INNER JOIN registered_user ON item_order.user_id = registered_user.user_id INNER JOIN item_list ON item_order.item_id = item_list.item_id WHERE order_status = 'Cooking' ORDER BY item_order.modified_at ASC LIMIT 5 ";
  $res = $conn->query($sql) or die("Could not get Orders");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_ASSOC);
  } else {
    $rows = [];
  }
  return $rows;
}


/**
 * Returns the last 10 Delivered Orders
 * @return array $rows 
 */
function deliveredOrders() : array
{
  global $conn;
  // SQL query to get column information
  $sql = "SELECT * FROM item_order INNER JOIN registered_user ON item_order.user_id = registered_user.user_id INNER JOIN item_list ON item_order.item_id = item_list.item_id WHERE order_status = 'Delivered' ORDER BY item_order.created_at DESC LIMIT 4 ";
  $res = $conn->query($sql) or die("Could not get Orders");


  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_ASSOC);
  } else {
    $rows = [];
  }
  return $rows;
}


/**
 * Get the details of an order
 * @param string $orderId
 * @return array  
 */
function orderDetails($orderId){
  global $conn;
  $sql = "SELECT registered_user.fname, registered_user.lname, registered_user.phone, registered_user.faculty_cabin, registered_user.faculty_extension, 
    item_order.order_id, item_order.order_amount,item_order.order_notes, item_order.item_quantity, item_order.order_status, 
    item_list.item_name, item_list.item_price,  item_order.bill_id
    FROM item_order INNER JOIN registered_user ON item_order.user_id = registered_user.user_id 
    INNER JOIN item_list ON item_order.item_id = item_list.item_id WHERE order_id = $orderId";
  $res = $conn->query($sql) or die("Could not fetch order details");
  return ($res->fetch_assoc());

}


/**
 * Function to accept new orders and send them to cooking
 * @param string $orderId 
 */
function acceptOrder($orderId) {
  global $conn;

  $sql = "UPDATE item_order SET order_status = 'cooking' WHERE order_id = $orderId";
  $conn->query($sql) or die("Could not accept order");
  
 } 

/**
 * Mark the order as delivered and payment recived
 * @param string  $orderId 
 */
function deliverOrder($orderId) {
  global $conn;
  $facultyExt = orderDetails($orderId)['faculty_extension'];
  $sql = "UPDATE item_order SET order_status = 'delivered' WHERE order_id = $orderId";
  $conn->query($sql) or die("Could not deliver order");
  
  if(pendingOrderCount($orderId)===0 && !$facultyExt){
    $sql = "UPDATE order_payment SET payment_status = 'Paid' WHERE bill_id = (SELECT bill_id FROM item_order WHERE order_id = $orderId)";
    $conn->query($sql) or die("Could not update payment status");
  }
  $payable_amount = calculatePayableAmount($orderId)['payable_amount'];
  $bill_id = calculatePayableAmount($orderId)['bill_id'];
  $sql = "UPDATE order_payment SET payable_amount = $payable_amount WHERE bill_id = $bill_id";
  $conn->query($sql) or die("Could not update payment amount");
}

function cancelOrder($orderId) {
  global $conn;
  $sql = "UPDATE item_order SET order_status = 'cancelled' WHERE order_id = $orderId";
  $conn->query($sql) or die("Could not cancel order");

  // $sql = "UPDATE order_payment SET payable_amount = (SELECT payable_amount FROM
  //  WHERE bill_id = (SELECT bill_id FROM item_order WHERE order_id = $orderId)";

}


/**
 * Count the number of pending orders with the same bill id
 * @param string OrderId
 * @return integer total number of pending orders
 */
function pendingOrderCount($orderId):int{
  global $conn; 
  $sql = "SELECT 
  COUNT(CASE WHEN bill_id = (SELECT bill_id FROM item_order WHERE order_id = $orderId) THEN order_id END) AS all_orders,
  COUNT(CASE WHEN order_status = 'delivered' THEN order_id END) AS completed_orders,
  COUNT(CASE WHEN order_status = 'cancelled' THEN order_id END) AS cancelled_orders
  FROM item_order WHERE bill_id =  (SELECT bill_id FROM item_order WHERE order_id = $orderId)";

  $res = $conn->query($sql) or die("Could not extract count");
  $rows = $res->fetch_assoc();
  return $rows['all_orders']-$rows['completed_orders']-$rows['cancelled_orders'];
}

/**
 * Calculate the payable amount of the orders delivered to the customer
 * @param string $orderId
 * 
 * @return array [$bill_id,$payable_amount]
 */
function calculatePayableAmount($orderId): array {
  global $conn;
  $sql = "SELECT bill_id, SUM(order_amount)
  AS payable_amount FROM item_order 
  WHERE bill_id=(SELECT bill_id FROM item_order WHERE order_id = $orderId) 
  && order_status = 'delivered';";
  $res=$conn->query($sql);
  return $res->fetch_assoc();
}




?>