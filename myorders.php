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
            <div class="w-full md:w-2/4 mb-6 rounded-lg bg-white p-3 shadow-md sm:flex sm:justify-start">
                <img src="img.svg" alt="product-image" class="w-100 rounded-lg sm:w-40">
                <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
                    <div class="block">
                    <span class="inline-block px-2 text-sm text-white bg-yellow-800 rounded"> Bill ID : #123 </span>
                        <h2 class="text-md m-2 font-bold text-gray-900"> 2  X  Chicken Alfredo Pasta </h2>
                        <span class="inline-block mt-4 px-2 text-sm text-white bg-black rounded"> Date :  12/02/2024 </span>
                    </div>
                    <div class="items-center">
                    <p class="text-xl text-center font-bold text-green-700">₹ 15.00 </p>
                    <span class="inline-block md:mt-10 px-2 py-1 text-sm text-white bg-gray-600 rounded"> Status : Pending...</span>
                    </div>
                        
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>
</body>

</html>