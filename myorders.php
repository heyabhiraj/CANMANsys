<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include('./admin/function.php');

$items = getDayMenu();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - <?php echo $_SESSION['fname']; ?> </title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
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
        <div class="flex justify-center">
            <div class="w-full md:w-2/4 mb-6 rounded-lg bg-white p-6 shadow-md sm:flex sm:justify-start">
                <img src="img.svg" alt="product-image" class="w-100 rounded-lg sm:w-40">
                <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
                    <div class="mt-5 sm:mt-0">
                        <h2 class="text-lg font-bold text-gray-900">Chicken Alfredo Pasta </h2>
                        <p class="mt-1 text-md text-gray-700">Quantity :  1 </p>
                        <p class="mt-1 text-md text-gray-900">Status  :  Pending </p>
                    </div>
                        <p class="text-xl text-center font-bold text-green-700">₹ 15.00 </p>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>
</body>

</html>