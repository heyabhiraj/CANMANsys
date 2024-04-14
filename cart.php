<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

include('./admin/function.php');
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - <?php echo $_SESSION['fname']; ?> </title>
  <link rel="stylesheet" href="style.css">
  <script src="../script.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
    @layer utilities {
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  }
</style>

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

    <div class="mx-auto max-w-5xl justify-center px-6 md:flex md:space-x-6 xl:px-0">
      <div class="rounded-lg md:w-2/3">
        <div class="justify-between mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start">
          <img src="img.svg" alt="product-image" class="w-full rounded-lg sm:w-40" />
          <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
            <div class="mt-5 sm:mt-0">
              <h2 class="text-lg font-bold text-gray-900">Pizza </h2>
              <p class="mt-1 text-xs text-gray-700">seasoned and grilled to perfection.</p>
            </div>
            <div class="mt-4 flex justify-between sm:space-y-6 sm:mt-0 sm:block sm:space-x-6">                
            <div class="flex justify-end">   
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 hover:text-red-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg></div> 
              <div class="flex items-center border-gray-100">
                <span class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-black hover:text-blue-50"> - </span>
                <input class="h-8 w-8 border bg-white text-center text-xs outline-none" type="number" value="1" min="1" />
                <span class="cursor-pointer rounded-r bg-gray-100 py-1 px-3 duration-100 hover:bg-black hover:text-blue-50"> + </span>
              </div>
                <p class="text-xl text-center font-bold text-green-700">₹ 50</p>
            </div>
          </div>
        </div>
<!-- product  -->
      </div>
      <!-- Sub total -->
      <div class="mt-6 h-full rounded-lg border bg-white p-6 shadow-md md:mt-0 md:w-1/3">
        <div class="mb-2 flex justify-between">
          <p class="text-gray-700">Subtotal</p>
          <p class="text-gray-700">₹ 50</p>
        </div>
        <hr class="my-4" />
        <div class="flex justify-between">
          <p class="text-lg font-bold">Total</p>
          <div class="">
          <p class="text-xl font-bold text-green-700">₹ 50</p>
            <p class="text-sm text-gray-700">including Tax</p>
          </div>
        </div>
        <button class="mt-6 w-full rounded-md bg-yellow-700 py-1.5 font-medium text-blue-50 hover:bg-black">Order Now</button>
      </div>
    </div>
  </div>
</body>