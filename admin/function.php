<?php
include_once('config.php');


/**
 *
 * @param $conn  variable database connection
 */
function Graphdata(){
    $sql = "SELECT DATE_FORMAT(created_at, '%d %b') AS order_date_formatted, SUM(item_quantity) AS total_quantity FROM item_order GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d') LIMIT 7 -- Group by date (without time) ORDER BY created_at ASC;";
    global $conn;
    // Execute the query
    $res = $conn->query($sql) or die("Could not get data");
    if($res->num_rows>0){
        $row = $res->fetch_all();
        
    }
    return $row;
}

function TotalSaleValue(){
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

function LatestOrder(){
    global $conn;
  // SQL query to get column information
    $sql = "SELECT * FROM item_order INNER JOIN registered_user ON item_order.user_id = registered_user.user_id INNER JOIN item_list ON item_order.item_id = item_list.item_id ORDER BY item_order.created_at DESC LIMIT 5";
    $res = $conn->query($sql) or die("Could not get Orders");
    if($res->num_rows>0){
        $rows = $res->fetch_all(); 
    } else {
      $rows = "No Current orders";
    }
    return $rows;
}




function vegMenuItem(){
  global $conn;
// SQL query to get column information
  $sql = "SELECT * FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id WHERE schedule_day = DAYNAME(CURDATE()) AND is_vegetarian = 'YES';";
  $res = $conn->query($sql) or die("Could not get Menu");
  if($res->num_rows>0){
      $rows = $res->fetch_all(); 
  }
  return $rows;
}



function nvegMenuItem(){
  global $conn;
// SQL query to get column information
  $sql = "SELECT * FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id WHERE schedule_day = DAYNAME(CURDATE()) AND is_vegetarian = 'NO';";
  $res = $conn->query($sql) or die("Could not get Menu");
  if($res->num_rows>0){
      $rows = $res->fetch_all(); 
  } else{
    echo "NO Menu";
  }
  return $rows;
}

/**
 *
 * @param $day Today "Monday"
 */
function getDayMenu(){
  global $conn;

  $sql = "SELECT * FROM item_list INNER JOIN item_schedule ON item_list.item_id = item_schedule.item_id WHERE schedule_day = DAYNAME(CURDATE());";
  $res = $conn->query($sql) or die("Could not get Menu");
  if($res->num_rows>0){
      $rows = $res->fetch_all(); 
  } else{
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

function updateCartItemQuantity($itemId, $quantity) {
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
