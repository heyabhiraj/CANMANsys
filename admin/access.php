<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['Role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <link rel="stylesheet" href="../style.css">
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdn.tailwindcss.com"></script>
 </head>


<body>
  <div class="h-20 p-5 ">
    <div class="flex p-5 mb-1 items-center justify-around bg-white border-b border-gray-200">
      <!-- Left side Logo -->
      <h1 class="text-4xl text-yellow-600 drop-shadow-lg"><a href="">CANMANsys </a> </h1>

      <!-- Right side buttons -->
      <div class="flex items-center">
        <button class="bg-black text-white rounded-full px-4 py-2" onclick='window.location.href="../logout.php"'>Log 0ut</button>
      </div>
    </div>