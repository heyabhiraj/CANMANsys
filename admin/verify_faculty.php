

<?php 

include_once('access.php');
include_once('function.php');


echo "

    <div id='details'></div>";
    // print_r(pendingOrderCount(118));
   echo" 
  <!-- Pending Faculty -->
    <section class='w-auto md:w-1/2 p-4'>
        <div class='rounded-lg items-center'>
            <div class='p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8'>
                <div class='flex justify-between mb-4'>
                <h5 class='text-xl font-bold text-gray-900 mr-15'>Verify Faculty</h5>
                </div>
                <div class='flow-root'>
                <ul role='list' class='divide-y divide-gray-200 dark:divide-gray-700'>";
                $faculty = getPendingFaculty();
                if(!empty($faculty)){
                foreach ($faculty as $i) {
                    echo "<li class='py-3 sm:py-4'>
                        <div class='flex justify-between'>
                            <div id='faculty_details'>
                            <div  class='items-center text-base font-semibold'>
                                $i[fname]  $i[lname]  
                               
                                </div>
                            </div>
                            <span title='$i[fname] $i[lname]' class=' self-center text-xs text-slate-600 font-semibold'>$i[faculty_extension]</span>

                            <form class='list-buttons self-center'>
                                <input type='hidden' name='user-id' value='$i[user_id]'>
                               
                                <button  type='button' class='faculty-details underline text-base font-semibold text-slate-600 '>
                                Details
                                </button>
                            </form>
                        </div>
                      
                    </li> ";
                        } } else { 
                        echo 'No New Faculty';
                        }
            echo "
                </ul>
            </div>
            </div>
        </div>
    </section>

"; ?>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const closeModal = () => {
    // $("#static-modal").hide(); 
    $("#details").html(""); 
    clearInterval(x);
}

$(document).ready(function(){
// Countinng the number of orders on screen
listCount = $(".list-buttons").length;
// alert(listCount);

if(listCount>0){


    $(".faculty-details").click(function(e){
        e.preventDefault();
        const userId = $(this).closest("form").find('input[name="user-id"]').val();   

        $.ajax({
            url: 'faculty_details.php',
            type: 'POST',
            data : {action:'userDetails', input:userId},
            success: function(response){
                console.log(response);
                $("#details").html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error executing PHP function:', error);
            }
        })
    })


    
    

}


});
</script>