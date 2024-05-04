<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}



// Check if user is logged in
include('./admin/config.php');
//  Database connection details 
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM registered_user WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id); // Bind user ID
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// Close prepared statement and connection (optional)
mysqli_stmt_close($stmt);
// mysqli_close($conn);
closeDB();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
 </head>


<body>
    <div class="h-20 p-5">
        <div class="flex p-5 mb-1 items-center justify-around bg-white ">
            <!-- Left side Logo -->
            <h1 class="text-4xl text-yellow-600 drop-shadow-lg"> <a href="">CANMANsys </a></h1>

            <!-- Right side buttons -->
            <div class="flex items-center">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['Role'] == 'admin') {
                echo '<a href="./admin/" class="bg-yellow-700 mr-2 text-white rounded-full px-4 py-2"> Admin </a>';
            } else {
                echo '';
            } ?>
                <button class="bg-black text-white rounded-full px-4 py-2" onclick='window.location.href="Logout.php"'>logOut</button>
            </div>
        </div>
        <div class="bg-orange-100 rounded-lg h-900 w-auto p-2 drop-shadow-lg">
            <?php
            include('navbar.php');
            ?>

            <div class="container mx-auto">
                <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-4">
                        <?php
                        if (isset($_SESSION['error'])) {
                            echo "<p class='text-center text-red-500'>" . $_SESSION['error'] . "</p>";
                            unset($_SESSION['error']); //Clear Error after display
                        }
                        if (isset($_SESSION['success'])) {
                            echo "<p class='text-center text-green-500'>" . $_SESSION['success'] . "</p>";
                            unset($_SESSION['success']); //Clear success after display
                        }
                        ?>
                        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Edit Profile</h1>
                        <form action="update.php" method="POST">
                            <div class="mb-4">
                                <label for="fname" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" id="fname" name="fname" value="<?php echo $user_data['fname']; ?>" class="mt-2 block w-full p-2 rounded border border-black outline-yellow-500">
                            </div>
                            <div class="mb-4">
                                <label for="lname" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" id="lname" name="lname" value="<?php echo $user_data['lname']; ?>" class="mt-2 block w-full p-2 rounded border border-black outline-yellow-500">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" disabled class="mt-2 block w-full p-2 rounded bg-gray-100 border border-transparent focus:outline-none ">
                            </div>
                            <div class="mb-4">
                                <label for="Phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" id="phone" name="phone" value="<?php echo $user_data['phone']; ?>" class="mt-2 block w-full p-2 rounded border border-black outline-yellow-500">
                            </div>
                            <label for="text" class="block text-sm font-medium text-gray-700"> Note Only for Faculty...</label>
                            <div class="flex justify-around">
                                <label for="text" class="block text-sm font-medium text-gray-700">Faculty cabin</label>
                                <label for="text" class="block text-sm font-medium text-gray-700">Faculty Ext </label>
                            </div>
                            <div class="flex mb-3 justify-content">
                                <input type="text" placeholder="Optional" value="<?php echo $user_data['faculty_cabin']; ?>" name="faculty-cabin" class="mr-2 block w-full p-2 rounded border border-black outline-yellow-500">
                                <input type="text" placeholder="Optional" value="<?php echo $user_data['faculty_extension'];  ?>" name="faculty-ext" class="ml-2 block w-full p-2 rounded border border-black outline-yellow-500">
                            </div>
                            <label for="password" class="block text-center text-sm font-medium text-gray-700">Change Password</label>
                            <hr>
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" id="password" name="new_password" class="mt-1 p-2 border border-black outline-yellow-500 block w-full rounded-md">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" id="password" name="confirm_password" class="mt-1 p-2 border border-black outline-yellow-500 block w-full rounded-md">
                            </div>
                            <div class="flex justify-between mt-6">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">Save Changes</button>
                                <a href="home.php" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700"> Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

</body>

</html>