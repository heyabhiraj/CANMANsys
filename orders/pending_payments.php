<?php
include('../admin/sidebar.php');
include_once('../admin/function.php');

$data = walletPayment();

?>


<body>
  <div class="p-4 sm:ml-64 ">
    <main class="border-gray-200 rounded-lg">
      <h1 class="text-yellow-700 text-2xl ">Faculty Wallet Management</h1>
      <!-- Main Section -->
      <div id="main" class="flex h-max items-center">

        <!-- Content Here -->
        <div class="m-2 p-5 relative overflow-x-auto shadow-2xl sm:rounded-lg">

          <table class="min-w-full divide-y divide-gray-200">
          <?php
    if (isset($_SESSION['errors'])) {
        echo "<p class='text-center text-red-500 mb-2'>" . $_SESSION['errors'] . "</p>";
        unset($_SESSION['errors']); // Clear error after displaying
    }
    ?>
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Name
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Email
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Phone
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Cabin
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Total Due Amount
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Left Balance
                </th>
                <th class="px-6 py-3 bg-gray-50"></th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php foreach ($data as $rec) : ?>
                <tr>
                  <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900"><?= $rec['fname'] . " " . $rec['lname'] ?></td>
                  <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900"><?= $rec['email'] ?></td>
                  <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900"><?= $rec['phone'] ?></td>
                  <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900"><?= $rec['faculty_cabin'] ?></td>
                  <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900"><?= $rec['due'] ?></td>
                  <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900"><?= $rec['available_limit'] ?></td>
                  <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                    <button onclick="toggleModal(<?= $rec['user_id'] ?>)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Pay</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>


      <form id="modal" action="update_payment.php" method="POST" class="hidden fixed inset-0 flex items-center justify-center z-10">
        <input type="hidden" name="userId" id="userId" value="">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <!-- Modal Content -->
        <div class="modal-container bg-white w-2/3 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
          <!-- Modal Header -->
          <div class="modal-header flex justify-between items-center px-4 py-2 bg-gray-200">
            <h3 class="text-lg font-bold text-gray-700">Pay Now</h3>
            <button onclick="toggleModal()" type="button" id="closeModal" class="text-gray-500 hover:text-gray-600 focus:outline-none">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="modal-body px-4 py-2">
            <p class="text-gray-700 mb-4">Please enter the amount to pay:</p>
            <input type="text" name="totalAmount" class="w-full p-2 border-gray-300 rounded-md border" placeholder="Enter Amount" required>
            <p class="text-gray-700 my-2">Payment Method:</p>
            <div class="flex items-center space-x-4">
              <input required type="radio" id="cash" name="paymentMethod" value="cash" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
              <label for="cash" class="text-gray-700">Cash</label>

              <input type="radio" id="upi" name="paymentMethod" value="upi" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
              <label for="upi" class="text-gray-700">UPI</label>
              <!-- Add more radio buttons as needed -->
            </div>
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer flex justify-end items-center px-4 py-2 bg-gray-200">
            <button type="submit" id="payNow" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Pay Now</button>
          </div>
        
      </form>
  </div>

</body>

<script>
  const toggleModal = userId => {
    document.getElementById("modal").classList.toggle('hidden');
    if (userId)
      document.getElementById("userId").value = userId;
  }
</script>