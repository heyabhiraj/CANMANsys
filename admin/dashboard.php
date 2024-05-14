<?php
include('function.php');

global $conn;
$sql = "UPDATE item_order SET order_status = 'cancelled' where order_status != 'delivered' && DATE(created_at)  != curdate();";
$conn->query($sql);

include('sidebar.php');
 
?>

<!-- Start Content -->
<div class="p-4 sm:ml-64 bg-orange-100">

  <!-- Counter Cards -->
  <div class="grid grid-cols-1 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="p-4 bg-orange-300 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
      <div class="flex items-start justify-between">
        <div class="flex flex-col space-y-2">
          <span class="text-gray-700">Total Users</span>
          <span class="text-lg font-semibold"><?php echo calculateRecord("registered_user");  ?></span>
        </div>
        <div><svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
    </div>
    <div class="p-4 bg-blue-300 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
      <div class="flex items-start justify-between">
        <div class="flex flex-col space-y-2">
          <span class="text-gray-700">Total 0rders</span>
          <span class="text-lg font-semibold"><?php echo calculateRecord("item_order");  ?></span>
        </div>
        <div class=""><svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
    </div>
    <div class="p-4 bg-red-300 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
      <div class="flex items-start justify-between">
        <div class="flex flex-col space-y-2">
          <span class="text-gray-700">Total Payments</span>
          <span class="text-lg font-semibold"><?php echo calculateRecord("registered_user");  ?></span>
        </div>
        <div class=""><svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
    </div>
    <div class="p-4 bg-green-300 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
      <div class="flex items-start justify-between">
        <div class="flex flex-col space-y-2">
          <span class="text-gray-700">Total Items</span>
          <span class="text-lg font-semibold"><?php echo calculateRecord("item_list");  ?></span>
        </div>
        <div class=""><svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="https://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
    </div>
  </div>

  <!-- sidebar end -->
</div>
<div class="p-4 sm:ml-64 bg-orange-100 mt-5">
  <div class="flex flex-wrap justify-center">
    <div class="w-full md:w-1/2 p-4">
      <div class=" items-center justify-center">

        <!-- Chart  -->
        <div class="bg-white rounded-lg shadow p-4 md:p-6">
          <div class="flex justify-between">
            <div>
              <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">₹ <?php TotalSaleValue();?></h5>
              <p class="text-base font-normal text-gray-500 dark:text-gray-400">Sales this week</p>
            </div>
            <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
            </div>
          </div>
          <div id="area-chart"></div>
          <div class="grid grid-cols-1 items-center border-gray-200 border-t justify-between">
            <div class="flex justify-between items-center pt-5">
              
              <!-- Button -->
              <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown" data-dropdown-placement="bottom" class="text-sm font-medium text-gray-500 hover:text-gray-900 text-center inline-flex items-center" type="button">
                Last 7 days
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Latest Orders  -->
    <div class="w-auto md:w-1/2 p-4">
      <div class="rounded-lg items-center">
          <div class="p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8">
            <div class="flex justify-between mb-4">
              <h5 class="text-xl font-bold text-gray-900 mr-15">Latest Orders</h5>
              <a href="../orders/latest_orders.php" class="text-sm font-medium text-gray-600 hover:underline">
                View all
              </a>
            </div>
            <div class="flow-root">
              <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
              <?php $lorder = LatestOrder();
            if(!empty($lorder)){
              foreach ($lorder as $i) { ?>
                <li class="py-3 sm:py-4">
                  <div class="flex justify-between">
                        <span class="items-center text-base font-semibold">
                        <?php echo $i['item_name'];  ?>
                      </span>
                      <div class="ml-10 items-center text-base font-semibold text-green-600">
                      ₹ <?php echo $i['order_amount'];  ?>
                      </div>
                  </div>
                  <div class="flex items-center">
                        <p class="text-sm font-medium text-gray-900">
                        <?php echo $i['fname'] ." ". $i['lname']; ?>
                        </p>
                        <p class="ml-10 text-sm text-gray-500">
                        <?php echo $i['email'];  ?>
                        </p>
                  </div>

                </li>
                    <?php } } else { 
                      echo "No Latest Order";
                      }?>
              </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('verify_faculty.php')?>
</div>

</body>

<script>
  
// Chart Preparing
const options = {
  chart: {
    height: "100%",
    maxWidth: "100%",
    type: "area",
    fontFamily: "Inter, sans-serif",
    dropShadow: {
      enabled: false,
    },
    toolbar: {
      show: false,
    },
  },
  tooltip: {
    enabled: true,
    x: {
      show: false,
    },
  },
  fill: {
    type: "gradient",
    gradient: {
      opacityFrom: 0.55,
      opacityTo: 0,
      shade: "#1C64F2",
      gradientToColors: ["#1C64F2"],
    },
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    width: 6,
  },
  grid: {
    show: false,
    strokeDashArray: 4,
    padding: {
      left: 2,
      right: 2,
      top: 0
    },
  },
  series: [
    {
      name: "Sales",
      data: [<?php  $row = Graphdata();
  if(!empty($row)){ foreach($row as $r) echo" {$r[1]}, "; } else { echo "No Data";}?>],
      color: "#1A56DB",
    },
  ],
  xaxis: {
    categories: [<?php foreach($row as $r) echo" '{$r[0]}', "; ?>],
    labels: {
      show: false,
    },
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
  },
  yaxis: {
    show: false,
  },
}

if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("area-chart"), options);
  chart.render();
}

</script>

</html>