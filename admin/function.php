<?php
include_once('config.php');


/**
 *
 * @param $conn  variable database connection
 */
function Graphdata()
{
  $sql = "SELECT DATE_FORMAT(created_at, '%d %b') AS order_date_formatted, SUM(item_quantity) AS total_quantity FROM item_order GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d') LIMIT 7 -- Group by date (without time) ORDER BY created_at ASC;";
  global $conn;
  // Execute the query
  $res = $conn->query($sql) or die("Could not get data");
  if ($res->num_rows > 0) {
    $row = $res->fetch_all();
  }
  return $row;
}

// get Total sales value in a past week 
function TotalSaleValue()
{
  global $conn;
  $sql = "SELECT SUM(order_amount * item_quantity) AS total_sales_value_past_week FROM item_order WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY);";
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


// get latest orders
function LatestOrder()
{
  global $conn;
  // SQL query to get column information
  $sql = "SELECT * FROM item_order INNER JOIN registered_user ON item_order.user_id = registered_user.user_id INNER JOIN item_list ON item_order.item_id = item_list.item_id ORDER BY item_order.created_at DESC LIMIT 5";
  $res = $conn->query($sql) or die("Could not get Orders");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all(MYSQLI_ASSOC);
  } else {
    $rows = "No Current orders";
  }
  return $rows;
}



// veg menu items 
function vegMenuItem()
{
  global $conn;
  // SQL query to get column information
  $sql = "SELECT * FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id WHERE schedule_day = DAYNAME(CURDATE()) AND is_vegetarian = 'YES';";
  $res = $conn->query($sql) or die("Could not get Menu");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all();
  }
  return $rows;
}


//non veg menu items 
function nvegMenuItem()
{
  global $conn;
  // SQL query to get column information
  $sql = "SELECT * FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id WHERE schedule_day = DAYNAME(CURDATE()) AND is_vegetarian = 'NO';";
  $res = $conn->query($sql) or die("Could not get Menu");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all();
  } else {
    echo "NO Menu";
  }
  return $rows;
}

/**
 *  Todays available menu items 
 * @param $day Today "Monday"
 */
function getDayMenu()
{
  global $conn;

  $sql = "SELECT * FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id WHERE schedule_day = DAYNAME(CURDATE());";
  $res = $conn->query($sql) or die("Could not get Menu");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all();
  } else {
    echo "NO Menu";
  }
  return $rows;
}
/**
 *  Todays available menu items 
 * @param $day Today "Monday"
 */
function getAllMenu()
{
  global $conn;

  $sql = "SELECT * FROM item_list;";
  $res = $conn->query($sql) or die("Could not get Menu");
  if ($res->num_rows > 0) {
    $rows = $res->fetch_all();
  } else {
    echo "NO Menu";
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
// Billing function to generate bill id 

function BillingData($paymentMode)
{

  global  $conn;
  // Get the current date and time
  $currentDateTime = date("Y-m-d H:i:s");
  $userid = $_SESSION['user_id'];
  $total = $_POST['total'];
  $status = 'paid';

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
  $currentDateTime = date("Y-m-d H:i:s");
  $userid = $_SESSION['user_id'];
  $itemId = '';
  // Calculate the total order amount and quantity

  $totalAmount = 0;
  $totalQuantity = 0;

  // Insert order details into the database
  $sql = "INSERT INTO item_order (order_amount,item_id, user_id, order_status, created_at, modified_at, payment_mode, order_notes, item_quantity, bill_id) 
          VALUES (?, ? ,?, 'Pending', ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("dssssssss", $totalAmount, $itemId, $userid, $currentDateTime, $currentDateTime, $paymentMode, $orderNotes, $totalQuantity, $billId);

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

function MyPastOrders( $page, $pageSize) {
  global $conn;
  $userId = $_SESSION['user_id'];
  // Calculate the offset
  $offset = ($page - 1) * $pageSize;

  // SQL query to get past orders with pagination
  $sql = "SELECT op.bill_id, 
          op.payment_status,
          io.item_quantity, 
          io.created_at,
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
  $stmt->bind_param("iii",  $userId ,$offset, $pageSize);

  // Execute the statement
  $stmt->execute();

  // Get the result set
  $result = $stmt->get_result();

  // Fetch the rows into an associative array
  $rows = $result->fetch_all(MYSQLI_ASSOC);

  // Close the statement
  $stmt->close();

  return $rows;
}
