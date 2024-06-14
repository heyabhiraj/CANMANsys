

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
    <button class="bg-yellow-600 text-white rounded-full px-4 py-2 mr-4" onclick='window.location.href="login.php"'>Login</button>
    <button class="bg-black text-white rounded-full px-4 py-2" onclick='window.location.href="register.php"'>Register</button>
  </div>
</div>
<div class=" bg-orange-100 rounded-lg h-700 w-auto p-10 drop-shadow-lg">

<div class="flex flex-wrap justify-center">
        <div class="w-full md:w-1/2 p-4">
          <div class=" items-center justify-center">
            <h2 class="text-4xl"> Order Food Anytime...</h2>
            <p class="p-3">A Canteen Management System</p>
          </div>
        </div>
        <div class="w-full md:w-1/2 p-4">
          <div class="rounded-lg flex items-center justify-center">
          <div class="object-fit drop-shadow-xl">
          <img src="img.svg">
        </div>
          </div>
        </div>
      </div>
</div>      
      <?php
$pageName = "Menu";
session_start();
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

  $itemExists = false;

  // If item exists, update the quantity
  foreach ($_SESSION['cart'] as &$cartItem) {
    if ($cartItem['id'] == $itemId) {
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


// Get filter option from query string
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Filter items based on the selected option
switch ($filter) {
  case 'veg':
    $items = vegMenuItem();
    $dayitems = getDayMenu();
    break;
  case 'nonveg':
    $items = nvegMenuItem();
    $dayitems = getDayMenu();
    break;
  case 'all':
    $items = getAllMenu();
    $dayitems = getDayMenu();
    break;
  case 'today':
  default:
    $items = getDayMenu();
    $dayitems = getDayMenu();
    break;
}
?><h1 class="mt-4 text-yellow-800 p-2 text-4xl underline text-center">Featured Menu</h1>
    <div class="mt-2 bg-orange-100 rounded w-auto p-2 drop-shadow-lg">
      <div class="mb-4">
        <form action="menu.php" method="get">
          <select name="filter" id="filter" class=" px-2 py-2 rounded-md text-sm font-medium bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <option class="cursor-pointer" value="today" <?php if ($filter === 'today') echo 'selected'; ?>>Today</option>
            <option class="cursor-pointer" value="veg" <?php if ($filter === 'veg') echo 'selected'; ?>>Veg</option>
            <option class="cursor-pointer" value="nonveg" <?php if ($filter === 'nonveg') echo 'selected'; ?>>Non-Veg</option>
            <option class="cursor-pointer" value="all" <?php if ($filter === 'all') echo 'selected'; ?>>All</option>
          </select>
          <!-- <button class="p-1 bg-black text-white rounded-md" type="submit">Filter</button> -->
        </form>
      </div>

      <div class="flex flex-wrap justify-center">

      <?php
      if(!empty($items)){
        foreach ($items as $item) : ?>
          <div class="w-full md:w-1/4 p-5">
            <div class="sm:flex bg-white border border-gray-200 rounded-lg shadow flex-col relative"> 
              
              <div class="absolute h-10 pl-2 pr-2 pt-2 -left-4 -t op-3 odd:bg-red-500 even:bg-violet-500 -skew-y-3 text-white"><?=$item['category_name']?></div>
              <!-- <img class="self-center p-5 rounded-lg object-cover h-40 w-40" src="<?=($img = $item['item_image'])?"img/$img":"img.svg"?>" alt="image" /> -->
              <div class="px-5 pb-5">
              <img class="self-center p-5 rounded-lg object-cover h-40 w-40" src="<?=($img = $item['item_image'])?"img/$img":"img.svg"?>" alt="image" />
              <form action="menu.php" method="post">
                  <input type="hidden" name="item_id" value="<?= $item[0]; ?>">
                  <h5 class="text-xl font-semibold  tracking-tight text-gray-900"><?= $item[1];  ?></h5>
                  <p class="text-sm font-medium text-gray-900"> <?= $item[2];  ?></p>
                  <input type="hidden" id="quantity" name="quantity" value="1" min="1">
                  <div class="flex w-full items-center justify-between px-3 py-1 rounded-lg"> <span class="text-xl font-bold text-green-700">₹ <?php echo $item[3];  ?></span>
                  <?php
                    if(in_array($item,$dayitems))
                    echo '<button name="add_to_cart" class="text-white bg-black hover:bg-gray-600 rounded-lg text-sm px-5 py-2.5 text-center">Add + </button>';
                    else
                    echo '<button disabled class="text-white bg-gray-700 rounded-lg text-sm px-5 py-2.5 text-center"> Unavailable </button>';
                  ?>
                  
              </div>
                </form>
              </div>
            </div>
          </div><?php endforeach; ?>
          <?php } else  echo "No Available Items";?>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function(){
    $('#filter').change(function(){
        const filter = $(this).val();
        window.location.href = "menu.php?filter=" + filter;
    });
});
  </script>
</body>


</html>
</div>
<div>
</body>
</html>