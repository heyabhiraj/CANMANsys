<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include('./admin/function.php');

$items = getDayMenu();

// Retrieve cart items from session
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
if (isset($_POST['place_order'])) {
    // Get necessary details for placing the order
    $paymentMode = $_POST['paymentmode'];
    $orderNotes = $_POST['ordernotes'];
}



if (!isset($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}
// Clear cart after placing the order
unset($_SESSION['cart']);

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

                <button class="bg-black text-white rounded-full px-4 py-2" onclick='window.location.href="logout.php"'>logout</button>
            </div>
        </div>
        <div class=" bg-orange-100 rounded-lg h-900 w-auto p-2 drop-shadow-lg">
            <?php
            include('./navbar.php');
            ?>
            <div class="checkmark flex items-center justify-around"><svg class="w-100 h-100 text-gray-800" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd" />
                </svg>
            </div>
            <h1 class="text-2xl mb-10 text-center">Congratulations !</h1>
            <?php
            $billId = billingData($paymentMode);
            $data = saveOrderDetails($cartItems, $paymentMode, $orderNotes, $billId);
            foreach ($data as $item) {
                echo "<div class='flex items-center justify-center m-2'><p class='text-lg text-bold ml-3 text-center'>"   . $item['quantity'] . "  X  &nbsp; </p> ";
                echo "<p class='text-lg text-bold text-center'>   "  . $item['item'][1] . " - ₹ "  . $item['item'][3] . "</p></div>";

            } ?>
            <p class="text-md mt-10 text-center">Your Order is placed Successfully...<a class="underline" href="./myorders.php"> View Orders </a></p>
        </div>
    </div>
</body>
<script>
    let duration = 3 * 1000;
    let animationEnd = Date.now() + duration;
    let defaults = {
        startVelocity: 10,
        spread: 360,
        ticks: 60,
        zIndex: 0
    };

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    let interval = setInterval(function() {
        let timeLeft = animationEnd - Date.now();

        if (timeLeft <= 0) {
            return clearInterval(interval);
        }

        let particleCount = 50 * (timeLeft / duration);
        // since particles fall down, start a bit higher than random
        confetti(Object.assign({}, defaults, {
            particleCount,
            origin: {
                x: randomInRange(0.3, 0.6),
                y: randomInRange(0.3, 0.5)
            }
        }));

    }, 250);
</script>

</html>