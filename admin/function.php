<?php
include_once('config.php');


if (isset($_POST['action']) && !empty($_POST['action'])) {
  $action = $_POST['action'];
  $input = $_POST['input'];



  switch ($action) {
    case 'verifyFaculty':
      verifyFaculty($input);
      break;
    case 'suspendUser':
      suspendUser($input);
      break;
    case 'userDetails':
      $data = getUserDetails($input);
      break;
  }
}

/**
 *
 * @param $conn  variable database connection
 */
function Graphdata()
{
  $sql = "SELECT order_date_formatted, SUM(total_quantity) AS total_quantity
  FROM (
      SELECT DATE_FORMAT(created_at, '%d %b') AS order_date_formatted, 
             SUM(item_quantity) AS total_quantity,
             MIN(created_at) AS min_created_at
      FROM item_order 
      WHERE order_status != 'cancelled' 
      GROUP BY DATE_FORMAT(created_at, '%d %b') -- Group by formatted date
  ) AS subquery
  GROUP BY order_date_formatted
  ORDER BY min_created_at ASC 
  LIMIT 7;";
  global $conn;
  // Execute the query
  $res = $conn->query($sql) or die("Could not get data");
  if ($res->num_rows > 0) {
    $row = $res->fetch_all();
  }
  return $row;
}


function saleGraphdata()
{
  $sql = "SELECT DATE(op.paid_at) AS transaction_date, COUNT(io.order_id) AS total_orders, SUM(io.order_amount) AS total_sales_amount FROM order_payment op JOIN item_order io ON op.bill_id = io.bill_id WHERE op.payment_status = 'paid' GROUP BY DATE(op.paid_at) ORDER BY DATE(op.paid_at) LIMIT 10";
  global $conn;
  // Execute the query
  $res = $conn->query($sql) or die("Could not get data");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_ASSOC);
  }
  return $rows;
}

//menu performnce
function Menuperform()
{
  $sql = "SELECT il.item_id, il.item_name, COUNT(io.order_id) AS total_orders, SUM(io.order_amount) AS total_sales_amount
  FROM item_list il
  JOIN item_order io ON il.item_id = io.item_id
  GROUP BY il.item_id, il.item_name
  ORDER BY total_sales_amount DESC LIMIt 5;
  ";
  global $conn;
  // Execute the query
  $res = $conn->query($sql) or die("Could not get data");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_ASSOC);
  }
  return $rows;
}
// get Total sales value in a past week 
function TotalSaleValue()
{
  global $conn;
  $sql = "SELECT SUM(order_amount * item_quantity) AS total_sales_value_past_week FROM item_order WHERE order_status!='cancelled' && created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY);";
  $result = $conn->query($sql) or die("Could not get Orders");
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalSalesValue = $row['total_sales_value_past_week'];
    // Format currency
    echo number_format($totalSalesValue, 2, '.', '');
  } else {
    echo "No sales data found for the past week.";
  }
}

function allTimesale(){
  global $conn;
  $sql = "SELECT SUM(order_amount) AS total_sales_value FROM item_order";

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the result row
    $row = $result->fetch_assoc();
    // Total sales value till date
    $total_sales_value = $row['total_sales_value'];
    // Output the total sales value
    return $total_sales_value;
} else {
    echo "No sales data available.";
}
}


// get latest orders
function LatestOrder()
{
  global $conn;
  // SQL query to get column information
  $sql = "SELECT * FROM item_order INNER JOIN registered_user ON item_order.user_id = registered_user.user_id INNER JOIN item_list ON item_order.item_id = item_list.item_id ORDER BY item_order.created_at DESC LIMIT 4";
  $res = $conn->query($sql) or die("Could not get Orders");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_ASSOC);
  } else {
    $rows = "";
  }
  return $rows;
}



// veg menu items 
function vegMenuItem()
{
  global $conn;
  $rows = "";
  // SQL query to get column information
  $sql = "SELECT item_list.item_id, item_name, item_description,item_price,item_image, category_name, item_list.category_id FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id INNER JOIN item_category ON item_list.category_id = item_category.category_id WHERE schedule_day = DAYNAME(CURDATE()) AND is_vegetarian = 'YES' AND item_status != 'Unavailable' AND schedule_status != 'Inactive' ORDER BY item_list.category_id ASC;";
  $res = $conn->query($sql) or die("Could not get Menu");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_BOTH);
  }
  return $rows;
}


//non veg menu items 
function nvegMenuItem()
{
  global $conn;
  $rows = "";
  // SQL query to get column information
  $sql = "SELECT item_list.item_id, item_name, item_description,item_price,item_image, category_name, item_list.category_id FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id INNER JOIN item_category ON item_list.category_id = item_category.category_id WHERE schedule_day = DAYNAME(CURDATE()) AND is_vegetarian = 'NO' AND item_status != 'Unavailable' AND schedule_status != 'Inactive' ORDER BY item_list.category_id ASC;";
  $res = $conn->query($sql) or die("Could not get Menu");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_BOTH);
  }
  return $rows;
}

/**
 *  Todays available menu items 
 * 
 */
function getDayMenu()
{
  global $conn;
  $rows = "";

  $sql = "SELECT item_list.item_id, item_name, item_description,item_price,item_image, category_name, item_list.category_id FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id INNER JOIN item_category ON item_list.category_id = item_category.category_id  WHERE schedule_day = DAYNAME(CURDATE()) AND item_status != 'Unavailable' AND schedule_status != 'Inactive' ORDER BY item_list.category_id ASC;";
  $res = $conn->query($sql) or die("Could not get Menu");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_BOTH);
  }
  return $rows;
}
/**
 *  Todays available menu items 
 * 
 */
function getAllMenu()
{
  global $conn;
  $rows = "";

  $sql = "SELECT DISTINCT item_list.item_id, item_name, item_description, item_price, item_image, category_name, item_list.category_id
  FROM item_list 
  INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id 
  INNER JOIN item_category ON item_list.category_id = item_category.category_id 
  WHERE item_status != 'Unavailable' AND schedule_status != 'Inactive' 
  ORDER BY item_list.category_id ASC
  ";
  $res = $conn->query($sql) or die("Could not get Menu");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_BOTH);
  }
  return $rows;
}

// Function to get total quantity of items in cart
function getTotalQuantity()
{
  $totalQuantity = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cartItem) {
      $totalQuantity += $cartItem['quantity'];
    }
  }
  return $totalQuantity;
}

// update cart item 

function updateCartItemQuantity($itemId, $quantity)
{
  // Check if cart session variable exists
  if (isset($_SESSION['cart'])) {
    // Loop through cart items and update quantity of matching item
    foreach ($_SESSION['cart'] as &$cartItem) {
      if ($cartItem['id'] == $itemId) {
        $cartItem['quantity'] = $quantity;
        break;
      }
    }
  }
}
/**
 * Billing function to generate bill id 
 *  
 * */

function billingData($paymentMode)
{

  global  $conn;
  // Get the current date and time
  $currentDateTime = date("Y-m-d H:i:s");
  $userid = $_SESSION['user_id'];
  $total = 0;
  $status = 'Paid';

  // Insert order details into the database
  $sql = "INSERT INTO order_payment (user_id, payable_amount, payment_mode, paid_at, payment_status) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("idsss", $userid, $total, $paymentMode, $currentDateTime, $status);
  // Execute the prepared statement for each item in the cart
  if ($stmt->execute() === TRUE) {
    // Get the ID of the last inserted record (bill_id)
    $billId = $conn->insert_id;
    return $billId;
  } else {
    echo "Error inserting order details: " . $stmt->error;
  }
}





// save order details 
// @2darray $cartItems 
function saveOrderDetails($cartItems, $paymentMode, $orderNotes, $billId)
{

  global  $conn;
  // Get the current date and time
  $userid = $_SESSION['user_id'];
  $itemId = "";
  $itemId = '';
  // Calculate the total order amount and quantity

  $totalAmount = 0;
  $totalQuantity = 0;

  // Insert order details into the database
  $sql = "INSERT INTO item_order (order_amount,item_id, user_id, order_status,  payment_mode, order_notes, item_quantity, bill_id) 
          VALUES (?, ? ,?, 'Pending', ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("dssssss", $totalAmount, $itemId, $userid,  $paymentMode, $orderNotes, $totalQuantity, $billId);

  // Execute the prepared statement for each item in the cart
  foreach ($cartItems as $cartItem) {


    // Assuming each item has a 'price' and 'quantity' attribute
    $itemId = $cartItem['item'][0];
    $totalAmount = $cartItem['item'][3] * $cartItem['quantity'];
    $totalQuantity = $cartItem['quantity'];
    $stmt->execute();
  }
  return $cartItems;
}

// get all my orders 

function MyPastOrders($page, $pageSize)
{
  global $conn;
  $userId = $_SESSION['user_id'];
  // Calculate the offset
  $offset = ($page - 1) * $pageSize;

  // SQL query to get past orders with pagination
  $sql = "SELECT op.bill_id, 
          op.payment_status,
          io.order_id,
          io.item_quantity, 
          io.created_at,
          io.payment_mode,
          io.order_status,
          il.item_name,
          io.order_amount
          FROM order_payment op 
          JOIN item_order io ON op.bill_id = io.bill_id 
          JOIN item_list il ON io.item_id = il.item_id
          WHERE op.user_id = ? 
          ORDER BY op.paid_at DESC
          LIMIT ?, ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("iii",  $userId, $offset, $pageSize);

  // Execute the statement
  $stmt->execute();

  // Get the result set
  $result = $stmt->get_result();

  // Fetch the rows into an associative array
  $rows = $result->fetch_all(MYSQLI_ASSOC);


  return $rows;
}



function updateOrderStatus($orderId, $newStatus)
{
  // Include your database connection code here
  // Assuming you have already established a connection to your database
  global $conn;
  // SQL query to update the order status
  $sql = "UPDATE item_order SET order_status = ? WHERE order_id = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("si", $newStatus, $orderId);

  // Execute the statement
  if ($stmt->execute()) {
    // Order status updated successfully
    return true;
  } else {
    // Error updating order status
    return false;
  }
}

function getPendingFaculty(): array
{
  global $conn;
  $sql = "SELECT user_id, fname, lname, phone, email, faculty_cabin, faculty_extension FROM registered_user WHERE user_type = 'faculty' && user_status='inactive'  ";
  $res = $conn->query($sql);
  return $res->fetch_all(MYSQLI_BOTH);
}
function getUserDetails($userId): array
{
  global $conn;
  $sql = "SELECT user_id, created, fname, lname, phone, email, faculty_cabin, faculty_extension FROM registered_user WHERE user_id = $userId  ";
  $res = $conn->query($sql);
  return $res->fetch_assoc();
}

function verifyFaculty($user_id)
{
  global $conn;
  $sql = "UPDATE registered_user SET user_status='active' WHERE user_id=$user_id";
  $conn->query($sql) or die("Could not verify faculty");
}
function suspendUser($user_id)
{
  global $conn;
  $sql = "UPDATE registered_user SET user_status='suspended' WHERE user_id=$user_id";
  $conn->query($sql) or die("Could not verify faculty");
}

function getCurrentBalance($userId): int
{
  try {
  global $conn;
  $sql = "SELECT available_limit FROM user_wallet WHERE user_id = $userId";
  $res = $conn->query($sql);
  return $res->fetch_assoc()['available_limit'];
  } catch (Exception $e) {
    echo ' ' .$e->getMessage();
  }
}


function walletPayment()
{
  global $conn;

  // SQL query to get past orders with pagination
  $sql = "SELECT up.credit_limit,
		      (up.credit_limit-up.available_limit) AS 'due',
          up.available_limit,
          io.user_id,
          io.faculty_cabin, 
          io.fname,
          io.lname,
          io.phone,
          io.email
          FROM user_wallet up 
          JOIN registered_user io ON up.user_id = io.user_id ";


  // Prepare the statement
  $stmt = $conn->prepare($sql);


  // Execute the statement
  $stmt->execute();

  // Get the result set
  $result = $stmt->get_result();

  // Fetch the rows into an associative array
  $rows = $result->fetch_all(MYSQLI_ASSOC);


  return $rows;
}
