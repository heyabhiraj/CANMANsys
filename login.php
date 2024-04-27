<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: home.php"); // Change index.php to your dashboard or home page
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="tailwindmain.css"></head>


<body>
    <div class="container p-10">
        <div class=" bg-orange-100 rounded-lg h-700 p-10 drop-shadow-lg">
            <div class="max-w-md w-full mx-auto bg-white rounded-lg overflow-hidden shadow-2xl">
                <h1 class="text-4xl text-center text-yellow-600 drop-shadow-lg font-thin mt-5"> CANMANsys </h1>

                <div class="p-8">

                <!-- Error or Message  -->
                    <?php 
                    if(isset($_SESSION['error'])){
                        echo "<p class='text-center text-red-500'>".$_SESSION['error']."</p>";
                        unset($_SESSION['error']);
                    }
                    if(isset($_SESSION['rsuccess'])){
                        echo "<p class='text-center text-green-500'>".$_SESSION['rsuccess']."</p>";
                        unset($_SESSION['rsuccess']); 
                    }

                    ?>

                    <!-- Login Form Starts Here -->
                    <form method="POST" action="auth.php">
                        <div class="mb-5">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-600">Email</label>

                            <input type="email" name="email" placeholder="youremail@gmail.com" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none " required/>
                        </div>

                        <div class="mb-5">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-600">Password</label>

                            <input type="password" name="password" placeholder="Password"  class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none" required/>
                        </div>

                        <a title="Please contact the canteen counter for password change." href="#" class="text-gray-600">Forgot password?</a>

                        <button type="submit" class="w-full p-3 mt-4 bg-black text-white rounded shadow active:bg-yellow-600">Login</button>
                    </form>
                    <p class="font-medium text-black text-center mt-5"> <a href="register.php">Need an account ?</p>

                    
                </div>

            </div>
        </div>

    </div>
</body>

</html>