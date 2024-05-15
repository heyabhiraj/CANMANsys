<?php 
include_once('order_function.php');

echo "
<main class='p-4 sm:ml-64 bg-orange-100 mt-5 flex flex-wrap justify-between'>
    <div id='details'></div>";
    // print_r(pendingOrderCount(118));
   echo" 
  <!-- Pending Orders -->
    <section class='w-auto md:w-1/2 p-4'>
        <div class='rounded-lg items-center'>
            <div class='p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8'>
                <div class='flex justify-between mb-4'>
                    <h5 class='text-xl font-bold text-gray-900 mr-15'>Latest Orders</h5>
                </div>
                <div class='flow-root'>
                <table class='min-w-full bg-white border border-gray-50'>
                <thead>
                    <tr class='bg-gray-50 text-gray-700 text-sm rounded-md'>
                        <th class='py-3 text-left'>#Order id</th>
                        <th class='py-3 px-6 text-left'> Order item</th>
                    </tr>
                </thead>
                </table>
                    <ul role='list' class='divide-y divide-gray-200 dark:divide-gray-700'>";
                    $lorder = pendingOrders();
                    if (!empty($lorder)) {
                        foreach ($lorder as $i) {
                            echo "<li title='Notes: $i[order_notes]' class='py-3 sm:py-4'>
                                <div class='flex justify-between'>
                                    <div id='order_details'>
                                        <div class='m-1 items-center text-base font-semibold'>
                                          <p>$i[order_id] &nbsp  $i[item_name] x $i[item_quantity] 
                                            
                                            <span class='text-xs text-slate-600 font-semibold'>$i[faculty_cabin]</span> </p>
                                        </div>
                                    </div>
                                    <form class='order-buttons self-center'>
                                        <input type='hidden' name='order-id' value='$i[order_id]'>
                                        <button type='button' class='accept-order text-base rounded-md p-1.5 text-white bg-green-600 mr-3'>
                                            Accept
                                        </button>
                                        <button type='button' class='order-details underline text-base font-semibold text-slate-600'>
                                            Details
                                        </button>
                                    </form>
                                </div>
                            </li>";
                        }
                    } else { 
                        echo '<li>No Latest Orders</li>';
                    }
            echo "
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Cooking Orders -->
    <section class='w-auto md:w-1/2 p-4'>
        <div class='w-full'>
            <div class='rounded-lg items-center'>
                <div class='p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8'>
                    <div class='flex justify-between mb-4'>
                        <h5 class='text-xl font-bold text-gray-900 mr-15'>Cooking Orders</h5>
                    </div>
                    <div class='flow-root'>               
                     <table class='min-w-full bg-white border border-gray-50'>
                    <thead>
                        <tr class='bg-gray-50 text-gray-700 text-sm rounded-md'>
                            <th class='py-3 text-left'>#Order id</th>
                            <th class='py-3 px-12 text-left'> Order item</th>
                        </tr>
                    </thead>
                    </table>
                        <ul role='list' class='divide-y divide-gray-200 dark:divide-gray-700'>";
                        $lorder = cookingOrders();
                        if (!empty($lorder)) {
                            foreach ($lorder as $i) {
                                echo "<li title='Notes: $i[order_notes]' class='py-3 sm:py-4'>
                                    <div class='flex justify-between'>
                                        <div id='order_details'>
                                            <span class='items-center text-base font-semibold'>
                                            $i[order_id] &nbsp  $i[item_name] x $i[item_quantity]
                                            </span>
                                            <span class='text-base ml-4 font-semibold text-green-600'>
                                                ₹ $i[order_amount]
                                            </span>
                                        </div>
                                        <span title='$i[fname] $i[lname]' class='self-center text-xs text-slate-600 font-semibold'>$i[faculty_cabin]</span>
                                        <form class='order-buttons self-center'>
                                            <input type='hidden' name='order-id' value='$i[order_id]'>
                                            <button type='button' class='deliver-order text-base font-semibold rounded-md p-1.5 text-white bg-red-600 mr-3'>
                                                Delivered
                                            </button>
                                            <button type='button' class='order-details underline text-base font-semibold text-slate-600'>
                                                Details
                                            </button>
                                        </form>
                                    </div>
                                </li>";
                            }
                        } else { 
                            echo '<li>No Cooking Orders</li>';
                        }
                   echo "
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>";
?>
<script>
$(document).ready(function() {
    // Count the number of orders on screen
    const orderCount = $(".order-buttons").length;

    if (orderCount > 0) {
        $(".accept-order").click(function(e) {
            const orderId = $(this).closest("form").find('input[name="order-id"]').val();
            $.ajax({
                url: "order_function.php",
                type: "POST",
                data: { action: 'acceptOrder', input: orderId },
                success: function(response) {
                    // alert('Order accepted successfully!');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error executing PHP function:', error);
                }
            });
        });

        $(".deliver-order").click(function() {
            const $button = $(this); // Store button element
            const orderId = $button.closest("form").find('input[name="order-id"]').val();
            $button.prop('disabled', true); // Disable the button after click
            $.ajax({
                url: "order_function.php",
                type: "POST",
                data: { action: 'deliverOrder', input: orderId },
                success: function(response) {
                    // alert('Order marked as delivered!');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error executing PHP function:', error);
                    $button.prop('disabled', false); // Re-enable the button if there's an error
                }
            });
        });

        $(".order-details").click(function(e) {
            e.preventDefault();
            const orderId = $(this).closest("form").find('input[name="order-id"]').val();   
            $.ajax({
                url: 'order_details.php',
                type: 'POST',
                data: { action: 'orderDetails', input: orderId },
                success: function(response) {
                    $("#details").html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error executing PHP function:', error);
                }
            });
        });
    }
});
</script>