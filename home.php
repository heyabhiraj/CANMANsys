<?php
$pageName = "Home";
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}
header("Location: menu.php");
include('./admin/function.php');

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


<body>
  <div class="h-20 p-5">
    <div class="flex p-5 mb-1 items-center justify-around bg-white ">
      <!-- Left side Logo -->
      <h1 class="text-xl md:text-4xl text-yellow-600 drop-shadow-lg"> <a href="">CANMANsys </a></h1>

      <!-- Right side buttons -->
      <div class="flex items-center">

        <button class="bg-black text-white rounded-full px-4 py-2" onclick='window.location.href="logout.php"'>logOut</button>
      </div>
    </div>
    <div class=" bg-orange-100 rounded-lg h-900 w-auto p-2 drop-shadow-lg">
      <?php
      include('navbar.php');
      ?>
      <div class="flex flex-wrap justify-center">
        <div class="w-full md:w-1/2 p-4">
          <div class=" items-center justify-center">
            <h2 class="text-4xl"> 0rder Food Anytime...</h2>
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
    <div>
</body>

</html>