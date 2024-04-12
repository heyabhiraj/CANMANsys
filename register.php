<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: home.php"); // your home page
    exit();
}
function sessionData($data){
    if(isset($_SESSION["$data"]))
        echo $_SESSION["$data"];
    unset($_SESSION["$data"]);
 }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./style.css">
    <script src="./script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container p-10">
        <div class=" bg-orange-100 rounded-lg h-700 p-10 drop-shadow-lg">
            <div class="max-w-md w-full mx-auto bg-white rounded-lg overflow-hidden shadow-2xl">
                <h1 class="text-4xl text-center text-yellow-600 drop-shadow-lg font-thin mt-5"> CANMANsys </h1>

                <div class="p-8">
                <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='text-center text-red-500 mb-2'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']); // Clear error after displaying
    }
    ?>
                    <form method="POST" onsubmit="return validatePasswords()" action="verify.php">
                        <div class="flex justify-center items-center">
                        <label for="text" class="block w-full mb-1 text-sm font-medium text-gray-600">First name</label>
                        <label for="text" class="block w-full ml-5 mb-1 text-sm font-medium text-gray-600">Last name </label>
                            </div>
                            <div class="flex mb-3 justify-around">
                            <input type="text" value="<?php sessionData("fname"); ?>" name="fname" class="block w-full p-3 mr-2 rounded bg-gray-200 border border-transparent focus:outline-none "required>
                            <input type="text" value="<?php sessionData("lname"); ?>"name="lname" class="block w-full p-3 ml-2 rounded bg-gray-200 border border-transparent focus:outline-none "required>
                            </div>
                        <div class="mb-3">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-600">Email</label>
                            <input type="email" value="<?php sessionData("email"); ?>" value="<?php sessionData("email"); ?>" name="email" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none" required>
                        </div>
                        <div class="mb-3">
                            <label for="Phone" class="block mb-2 text-sm font-medium text-gray-600">Phone</label>
                            <input type="tel" value="<?php sessionData("phone"); ?>" name="phone" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none " required>
                        </div>
                        <label for="text" class="block w-full mb-1 text-sm font-medium text-gray-600 border-b"> Note below fields are 0nly for Faculty</label>
                        <div class="flex justify-center items-center">
                        <label for="text" class="block w-full mb-1 text-sm font-medium text-gray-600">Faculty cabin</label>
                        <label for="text" class="block w-full ml-5 mb-1 text-sm font-medium text-gray-600">Faculty Ext </label>
                            </div>
                            <div class="flex mb-3 justify-around">
                            <input type="text" placeholder="Optional "  value="<?php sessionData("cabin"); ?>" name="faculty-cabin" class="block w-full p-3 mr-2 rounded bg-gray-200 border border-transparent focus:outline-none ">
                            <input type="text" placeholder="Optional " value="<?php sessionData("ext"); ?>"name="faculty-ext" class="block w-full p-3 ml-2 rounded bg-gray-200 border border-transparent focus:outline-none ">
                            </div>
                        <div class="mb-3">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-600">Password</label>

                            <input type="password" id="password" name="password" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none "required>
                        </div>
                        <div class="mb-3">
                            <label for="Cpassword" class="block mb-2 text-sm font-medium text-gray-600">Confirm Password</label>

                            <input type="password" id="confirm_password" name="Cpassword" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none "required>
                        </div>
                        <button type="submit" class="w-full p-3 mt-4 bg-black text-white rounded shadow active:bg-yellow-600">Create account</button>
                    </form>
                    <p class="font-medium text-black text-center mt-5"> <a href="login.php">Already Have an account ?</p>


                </div>

            </div>
        </div>

    </div>
</body>

</html>