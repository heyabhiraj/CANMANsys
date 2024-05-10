<?php
 include('../admin/sidebar.php');
 




?>
<head>
<!-- <meta http-equiv="refresh" content="60; "> -->
<title>Recent Orders</title>
</head>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<main id="main"></main>

<script>
    
    const closeModal = () => {
        const modal = document.getElementById("static-modal");
        modal.style.display = "none";
        load_orders();
    }
    const load_orders = ()=>{
            $.ajax({
                url: 'load_orders.php',
                type: 'POST',
                success: function(data){
                    $("#main").html(data);
                    
                }
            });
            var orderCount = $(".order-buttons").length;
            console.log("From load_orders: ",orderCount);
            return (orderCount);
        }
    $(document).ready(function(){
        let orderCount = load_orders();
        let flag = true;
        
        x = setInterval(()=>{

            if(flag){
                flag = false;
                console.log("from Interval: ", flag);
                orderCount = load_orders();
            }

            console.log(" from Interval: ", orderCount,);
            if(orderCount === 0)
            orderCount=load_orders();
         }, 5000);

        
    })

</script>