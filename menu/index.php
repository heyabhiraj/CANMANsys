<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

include('../admin/function.php');
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - <?php echo $_SESSION['fname']; ?> </title>
  <link rel="stylesheet" href="../style.css">
  <script src="../script.js"></script>
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
      include('../navbar.php');
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
      $foodtype ;
      $rows = nvegMenuItem();
        foreach ( $rows as $i){ ?>
        <div class="w-full md:w-1/4 p-4">
          <div class="sm:flex bg-white border border-gray-200 rounded-lg shadow flex-col"> 
            <img class="self-center p-5 rounded-lg" src="../img.svg" alt="image" />
            <div class="px-5 pb-5">
              <h5 class="text-xl font-semibold tracking-tight text-gray-900"><?php echo $i[1];  ?></h5>
              <p class="text-sm font-medium text-gray-900"><?php echo $i[2];  ?></p>
              <div class="flex w-full items-center justify-between px-3 py-1 rounded-lg"> <span class="text-xl font-bold text-green-700"><?php echo $i[3];  ?></span>
                <a href="#" class="text-white bg-black hover:bg-gray-600 rounded-lg text-sm px-5 py-2.5 text-center">Add to cart</a>
              </div>
            </div>
          </div>
        </div><?php }
         
        ?>


      </div>
    </div>
  </div>
</body>

</html>