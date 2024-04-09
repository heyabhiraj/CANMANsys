<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['Role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>



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
    <h1 class="text-4xl text-yellow-600 drop-shadow-lg"> CANMANsys </h1>
  
  <!-- Right side buttons -->
  <div class="flex items-center">

    <button class="bg-black text-white rounded-full px-4 py-2" onclick='window.location.href="LogOut.php"'>Log0ut</button>
  </div>
</div>
<div class=" bg-orange-100 rounded-lg h-700 w-auto p-10 drop-shadow-lg">
<a class="bg-yellow-800 text-white rounded-full px-4 py-2 mr-4" href="profile.php">Welcome , <?php echo $_SESSION['fname']; ?>!</a>
<div class="flex justify-around">
    <div class="mt-20">
   <h2 class="text-4xl"> 0rder Food Anytime...</h2>
   <p class="p-3">A premium Restaurant site</p>
   <div class="align-center drop-shadow-md">
  <input type="text-center" placeholder="Search..." class="border-2 border-gray-300 bg-white h-10 px-5 pr-10 rounded-full text-sm focus:outline-none">
</div>
   </div>
   <div class="image drop-shadow-xl">
<img src="img.svg">
</div>
</div>
</div>
<div>
</body>
</html>