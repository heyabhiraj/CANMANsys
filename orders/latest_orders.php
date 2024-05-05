<?php
 include('../admin/sidebar.php');
 
 global $conn;
 $sql = "UPDATE item_order SET order_status = 'cancelled' where order_status != 'delivered' && DATE(created_at)  != curdate();";
 $conn->query($sql);




?>
<head>
<!-- <meta http-equiv="refresh" content="60; "> -->
<title>Recent Orders</title>
</head>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<main id="main"></main>

<script>
    
    const closeModal = () => {
        const modal = document.getElementById("static-modal");
        modal.style.display = "none";
    }
    function load_orders(callback){
            $.ajax({
                url: 'load_orders.php',
                type: 'POST',
                success: function(data){
                    $("#main").html(data);
                    var orderCount = $(".order-buttons").length;
                    callback(orderCount); // Pass the orderCount to the callback function
                }
            });
        }
    $(document).ready(function(){
        var orderCount = 0;
        

        load_orders(function(orderCount) {
        // Use the orderCount here, or pass it to another function
            console.log("Total orders:", orderCount);
        });
        

        
    })

</script>