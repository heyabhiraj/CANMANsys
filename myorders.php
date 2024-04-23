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
            <p class="text-md m-4 text-end"><a class="underline" href="./menu.php"> View Menu </a></p>
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = 5;
            $rows = MyPastOrders($page, $pageSize);
            if (!empty($rows)) {
                foreach ($rows as $i) { ?>
                    <div class="flex justify-center">
                        <div class="w-full md:w-2/4 mb-6 rounded-lg bg-white p-3 shadow-md sm:flex sm:justify-start">
                            <img src="img.svg" alt="product-image" class="items-center rounded-lg sm:w-40">
                            <div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
                                <div class="flex flex-col items-center">
                                    <span class="inline-block px-2 text-sm"> Bill ID : <?php echo $i['bill_id'];  ?> </span>
                                    <h2 class="text-md m-2 font-bold text-gray-900"> <?php echo $i['item_quantity'];  ?> X <?php echo $i['item_name'];  ?> </h2>
                                    <span class="inline-block mt-6 px-2 text-sm text-white bg-black rounded"> Date : <?php echo $i['created_at'];  ?> </span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <p class="text-xl text-center font-bold text-green-700"> ₹ <?php echo $i['order_amount'];  ?> </p>
                                    <span class="inline-block md:mt-10 px-2 py-1 text-sm text-white bg-gray-600 rounded"> Status : Pending...</span>
                                    
                                </div>

                            </div>
                        </div>
                    </div> <?php }
                    
                        $totalOrders = count(MyPastOrders(1, PHP_INT_MAX));

                        // Calculate total number of pages
                        $totalPages = ceil($totalOrders / $pageSize); // Display pagination links
                        echo '<ul class="flex justify-center -space-x-px text-sm h-8">';
                        if ($page > 1) {
                            echo "<li><a class='flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300' href='?page=" . ($page - 1) . "'>Previous</a></li>";
                        }
                    // Page number links
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $activeClass = ($i == $page) ? "active" : "bg-yellow-600";
                        echo "<li class='$activeClass bg-gray-600'><a class='flex items-center justify-center px-3 h-8 text-white border border-gray-300' href='?page=" . $i . "'>" . $i . "</a></li>";
                    }
                    if ($page < $totalPages) {
                        echo "<li><a class='flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 ' href='?page=" . ($page + 1) . "'>Next</a></li>";
                    }
                    echo '</ul>';
                    } else {
                        echo "No Past orders";
                    } ?>

        </div>

    </div>
    </div>
</body>

</html>