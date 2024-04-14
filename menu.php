<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}
include('./admin/function.php');

$items = getDayMenu();


// Add item to cart
if (isset($_POST['add_to_cart']) && isset($_POST['item_id']) && isset($_POST['quantity'])) {
  $itemId = $_POST['item_id'];
  $quantity = $_POST['quantity'];
  // Check if cart session variable exists, if not, initialize it
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }
  // Check if the item already exists in the cart
  $itemExists = false;
  foreach ($_SESSION['cart'] as &$cartItem) {
    if ($cartItem['id'] == $itemId) {
      // If item exists, update the quantity
      $cartItem['quantity'] += $quantity;
      $itemExists = true;
      break;
    }
  }
  // If item doesn't exist, add it to the cart
  if (!$itemExists) {
    $_SESSION['cart'][] = array('id' => $itemId, 'quantity' => $quantity);
  }
  // Redirect back to menu page
  header('Location: cart.php');
  exit;
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

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - <?php echo $_SESSION['fname']; ?> </title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="h-20 p-5">
    <div class="flex p-5 mb-1 items-center justify-around bg-white ">
      <!-- Left side Logo -->
      <h1 class="text-4xl text-yellow-600 drop-shadow-lg"> <a href="">CANMANsys </a></h1>

      <!-- Right side buttons -->
      <div class="flex items-center">

        <button class="bg-black text-white rounded-full px-4 py-2" onclick='window.location.href="../Logout.php"'>Log0ut</button>
      </div>
    </div>
    <div class="bg-orange-100 rounded-lg h-900 w-auto p-10 drop-shadow-lg">
      <?php
      include('./navbar.php');
      ?>



      <div class="mb-4">
        <select name="food-type" class="block px-3 py-2 rounded-md text-sm font-medium bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <option value="all">All</option>
          <option value="veg">Veg</option>
          <option value="non-veg">Non-Veg</option>
        </select>
      </div>

      <div class="flex flex-wrap justify-center">

        <?php
        $items = getDayMenu();
        foreach ($items as $item) : ?>
          <div class="w-full md:w-1/4 p-5">
            <div class="sm:flex bg-white border border-gray-200 rounded-lg shadow flex-col">
              <img class="self-center p-5 rounded-lg" src="img.svg" alt="image" />
              <div class="px-5 pb-5">
                <form action="menu.php" method="post">
                  <input type="hidden" name="item_id" value="<?= $item[0]; ?>">
                  <h5 class="text-xl font-semibold tracking-tight text-gray-900"><?= $item[1];  ?></h5>
                  <p class="text-sm font-medium text-gray-900"> <?= $item[2];  ?></p>
                  <input type="hidden" id="quantity" name="quantity" value="1" min="1">
                  <div class="flex w-full items-center justify-between px-3 py-1 rounded-lg"> <span class="text-xl font-bold text-green-700">₹ <?php echo $item[3];  ?></span>
                    <button name="add_to_cart" class="text-white bg-black hover:bg-gray-600 rounded-lg text-sm px-5 py-2.5 text-center">Add to cart</button>
                  </div>
                </form>
              </div>
            </div>
          </div><?php endforeach;?>
      </div>
    </div>
  </div>
</body>

</html>