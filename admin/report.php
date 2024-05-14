<?php
include('function.php');


include('sidebar.php');

?>

<!-- Start Content -->

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

    <div class="w-auto md:w-1/2 p-4">
    <!-- Chart 2 -->
    <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <div class="flex justify-between">
            <div>
                <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">₹ <?= allTimesale(); ?></h5>
                <p class="text-base font-normal text-gray-500 dark:text-gray-400">Total Sales </p>
            </div>
            <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
            </div>
        </div>
        <div id="column-chart"></div>
    </div>
    </div>
  </div>

  <div class="flex flex-wrap justify-center">
    <div class="w-full md:w-1/2 p-4">
      <div class=" items-center justify-center">
      <div class="p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8">
            <div class="flex justify-between mb-4">
              <h5 class="text-xl font-bold text-gray-900 mr-15">Menu Performance report</h5>
            </div>
            <div class="flow-root">
              <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
              <?php $top = Menuperform();
            if(!empty($top)){
              foreach ($top as $i) { ?>
                <li class="py-3 sm:py-4">
                <div class="flex justify-between">
                        <span class="items-center text-base font-semibold">
                        <?php echo $i['item_name'];  ?>      x <?php echo $i['total_orders'];  ?>
                      </span>
                      <div class="ml-10 items-center text-base font-semibold text-green-600">
                      ₹ <?php echo $i['total_sales_amount'];  ?>
                      </div>
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

    <!-- Latest Orders  -->
    <div class="w-auto md:w-1/2 p-4">
      <div class="rounded-lg items-center">
 
      </div>
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
        series: [{
            name: "Sales",
            data: [<?php $row = Graphdata();
                    if (!empty($row)) {
                        foreach ($row as $r) echo " {$r[1]}, ";
                    } else {
                        echo "No Data";
                    } ?>],
            color: "#1A56DB",
        }, ],
        xaxis: {
            categories: [<?php foreach ($row as $r) echo " '{$r[0]}', "; ?>],
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

    
const options2 = {
  colors: ["#09a672"],
  series: [
    {
      name: "Total Sale amount in ₹",
      color: "#09a672",
      data: <?php
 $bardata = saleGraphdata();
 foreach ($bardata as $row ){
    $chartData[] = [
        'x' => $row['transaction_date'],
        'y' => $row['total_sales_amount'], 
    ];
 }
 $chartDataJson = json_encode($chartData);
 echo $chartDataJson;
?>,
    },
  ],
  chart: {
    type: "bar",
    height: "300px",
    fontFamily: "Inter, sans-serif",
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: "50%",
      borderRadiusApplication: "end",
      borderRadius: 8,
    },
  },
  tooltip: {
    shared: true,
    intersect: false,
    style: {
      fontFamily: "Inter, sans-serif",
    },
  },
  states: {
    hover: {
      filter: {
        type: "darken",
        value: 1,
      },
    },
  },
  stroke: {
    show: true,
    width: 0,
    colors: ["transparent"],
  },
  grid: {
    show: false,
    strokeDashArray: 4,
    padding: {
      left: 2,
      right: 2,
      top: -14
    },
  },
  dataLabels: {
    enabled: false,
  },
  legend: {
    show: false,
  },
  xaxis: {
    floating: false,
    labels: {
      show: true,
      style: {
        fontFamily: "Inter, sans-serif",
        cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
      }
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
  fill: {
    opacity: 1,
  },
}

if(document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("column-chart"), options2);
  chart.render();
}



</script>

</html>