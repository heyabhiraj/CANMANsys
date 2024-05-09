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
                            <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">₹ <?php TotalSaleValue(); ?></h5>
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
</script>

</html>