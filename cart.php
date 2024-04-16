<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

include('./admin/function.php');

$items = getDayMenu();

// Function to remove item from cart
function removeFromCart($itemId)
{
  foreach ($_SESSION['cart'] as $key => $cartItem) {
    if ($cartItem['id'] == $itemId) {
      unset($_SESSION['cart'][$key]);
      return;
    }
  }
}

// Function to calculate subtotal of items in the cart
function calculateSubtotal($cartItems)
{
  $subtotal = 0;
  foreach ($cartItems as $cartItem) {
    $subtotal += $cartItem['item'][3] * $cartItem['quantity'];
  }
  return $subtotal;
}

// Function to calculate total including taxes and shipping (if applicable)
function calculateTotal($subtotal)
{
  // Add taxes, shipping costs, or any other additional fees to the subtotal
  $total = $subtotal;
  return $total;
}

// Check if item removal request is received
if (isset($_POST['remove_item']) && isset($_POST['item_id'])) {
  $itemId = $_POST['item_id'];
  removeFromCart($itemId);
  // Redirect back to cart page to reflect changes
  header('Location: cart.php');
  exit;
}


// Check if an item's quantity should be updated
if (isset($_POST['update_quantity']) && isset($_POST['item_id']) && isset($_POST['quantity'])) {
  $itemId = $_POST['item_id'];
  $quantity = $_POST['quantity'];
  updateCartItemQuantity($itemId, $quantity);
}



// Initialize cart items array
$cartItems = array();

// Check if cart session variable exists
if (isset($_SESSION['cart'])) {
  // Loop through cart items and retrieve details from $items array
  foreach ($_SESSION['cart'] as $cartItem) {
    foreach ($items as $item) {
      if ($item[0] == $cartItem['id']) {
        $cartItems[] = array('item' => $item, 'quantity' => $cartItem['quantity']);
        break;
      }
    }
  }
}


// Calculate subtotal
$subtotal = calculateSubtotal($cartItems);

// Calculate total
$total = calculateTotal($subtotal);

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

        <button class="bg-black text-white rounded-full px-4 py-2" onclick='window.location.href="Logout.php"'>Log0ut</button>
      </div>
    </div>
    <div class="bg-orange-100 rounded-lg h-900 w-auto p-10 drop-shadow-lg">
      <?php
      include('./navbar.php');
      ?>

      <div class="mx-auto max-w-5xl justify-center px-6 md:flex md:space-x-6 xl:px-0">

        <div class="rounded-lg md:w-2/3">
          <?php if (empty($cartItems)) : ?>
            <p>Your cart is empty...</p>
            <a href="menu.php">
              <p class="w-50 mt-10 text-white text-center bg-black p-1 rounded-md"> Click to ADD more items</p>
            </a>
          <?php else : ?>
            <a href="menu.php">
              <p class="mb-8">
                < Back to menu</p>
            </a>
            <?php foreach ($cartItems as $cartItem) : ?>

              <div class="justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start">
                <img src="img.svg" alt="product-image" class="w-full rounded-lg sm:w-40" />
                <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
                  <div class="mt-5 sm:mt-0">
                    <h2 class="text-lg font-bold text-gray-900"><?= $cartItem['item'][1]; ?> </h2>
                    <p class="mt-1 text-xs text-gray-700"><?= $cartItem['item'][2]; ?> </p>
                  </div>
                  <div class="mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6">
                    <div class="flex justify-end">
                      <form action="cart.php" method="post">
                        <input type="hidden" name="item_id" value="<?= $cartItem['item'][0]; ?>">
                        <button type="submit" name="remove_item">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 hover:text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg></button>
                    </div>
                    <div class="flex items-center border-gray-100">
                      <button class="rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-black hover:text-blue-50" type="submit" name="update_quantity" onclick="decrementQuantity(this)">-</button>
                      <input class="h-8 w-8 border bg-white text-center text-xs outline-none" type="number" name="quantity" value="<?= $cartItem['quantity']; ?>">
                      <button class="rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-black hover:text-blue-50" type="submit" name="update_quantity" onclick="incrementQuantity(this)">+</button>
                    </div>
                    <p class="text-xl text-center font-bold text-green-700">₹ <?= $cartItem['item'][3]; ?> </p>
                  </div>
                </div>
                </form>
              </div><?php endforeach; ?>
          <?php endif; ?>
        </div>
        <!-- Sub total -->
        <div class="mt-10 h-full rounded-lg border bg-white p-6 shadow-md md:mt-0 md:w-1/3">
        <form action="order.php" method="POST">
          <div class="m-2 flex justify-between">
            <p class="text-gray-700">Subtotal</p>
            <p class="text-gray-700">₹ <?= $subtotal; ?></p>
          </div>
          <hr class="my-4 mb-10" />
          <div class="flex justify-between">
            <p class="text-lg font-bold">Total</p>
            <div class="">
              <p name="total" class="text-xl font-bold text-green-700">₹ <?= $total; ?></p>
              <input type="hidden" name="item_id" value="<?= $cartItem['item'][0]; ?>">
              <p class="text-sm text-gray-700">including Tax</p>
            </div>
          </div>
          <label class="block mb-2 text-sm font-medium text-gray-900">Order notes</label>
          <textarea name="ordernotes" rows="3" class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300" placeholder="Order Notes here..."></textarea>
          <label class="block mt-2 text-sm font-medium text-gray-900">Payment </label>
          <select id="payment" name="paymentmode" class=" mt-4 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
              <option> Cash On Delivery</option>
              <option> Credit or Pay Later</option>
            </select>
          <button type="submit" name="place_order" class="mt-6 w-full rounded-md bg-yellow-700 py-1.5 font-medium text-blue-50 hover:bg-black">Order Now</button>
            </form>
        </div>
      </div>
    </div>
    <script>
      function incrementQuantity(button) {
        var input = button.previousElementSibling;
        var value = parseInt(input.value);
        input.value = isNaN(value) ? 1 : value + 1;
      }

      function decrementQuantity(button) {
        var input = button.nextElementSibling;
        var value = parseInt(input.value);
        if (!isNaN(value) && value > 1) {
          input.value = value - 1;
        }
      }
    </script>
</body>

</html>