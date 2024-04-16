<?php
include_once('./admin/function.php');
// checking for not been accessed directly.............
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

?>

<nav class="navbar bg-white rounded-md drop-shadow-lg mb-20 ">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <!-- Mobile menu button-->
        <button onclick="toggleMenu()" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
          <span class="absolute -inset-0.5"></span>
          <span class="sr-only">Open main menu</span>
          <!--
            Icon when menu is closed.

            Menu open: "hidden", Menu closed: "block"
          -->
          <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <!--
            Icon when menu is open.

            Menu open: "block", Menu closed: "hidden"
          -->
          <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
        <div class="hidden sm:ml-6 sm:block">
          <div class="flex space-x-4">
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
            <a href="./home.php" class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white" aria-current="page">Home</a>
            <a href="./menu.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-600 hover:text-white">Menu</a>
            <a href="./myorders.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-600 hover:text-white">My 0rders</a>
          </div>
        </div>
      </div>
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <!-- Profile -->
        <div class="relative ml-3">
          <div class="flex">

            <a href="profile.php" type="button" class="relative flex rounded-full bg-yellow-800 text-sm" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
              <span class="absolute -inset-1.5"></span>
              <span class="sr-only">Open user menu</span>
              <svg class="mt-1 ml-1  w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd" />
              </svg>
              <span class="bg-yellow-800 text-white rounded-full px-4 py-2 mr-4">Welcome , <?php echo $_SESSION['fname']; ?>!</span>
            </a>

            <div class="relative cart-logo ml-4">
              <a href="./cart.php">
                <div class="t-0 absolute left-5">
                  <p class="flex h-2 w-2 items-center justify-center rounded-full bg-red-500 p-3 text-xs text-white"><?=getTotalQuantity();?></p>
                </div>
                <svg class="w-8 h-8 mr-1 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
                </svg>
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div class="hidden sm:hidden" id="mobile-menu">
    <div class="space-y-1 px-2 pb-3 pt-2">
      <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
      <a href="home.php" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white" aria-current="page">Home</a>
      <a href="menu.php" class="block rounded-md px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-400 hover:text-white">Menu</a>
      <a href="order.php" class="block rounded-md px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-400 hover:text-white">My 0rders</a>
      <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-400 hover:text-white">Contact Us</a>
    </div>
  </div>
</nav>