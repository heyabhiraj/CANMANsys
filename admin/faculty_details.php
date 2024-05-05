
<?php 
 include('function.php');
 if(!isset($data) || empty($data))
  echo "Faculty Details Unavailable";

 
 ?>

<!DOCTYPE html>
<html lang='en'>
<head>
<link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0' />
  <script src='https://cdn.jsdelivr.net/npm/apexcharts'></script>
  <script src='https://cdn.tailwindcss.com'></script>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>

</head>
<body>
    


<?php $modal = "
<!-- Main modal -->
<div  id='static-modal' data-modal-backdrop='static' tabindex='-1' aria-hidden='true' class=' fixed z-40 w-full h-full mt-0 ml-0 bg-gray-900/[.10]  inset-0 flex items-center justify-center overflow-hidden  '>
    <div class='z-50 relative p-4 ml-64 w-full max-w-lg max-h-full'>

      <!-- Modal content -->
        <div  class=' relative bg-white rounded-lg shadow dark:bg-white border border-gray-200 rounded-lg shadow sm:p-8'>
            <!-- Modal header -->
            <div class='flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600'>
                <h3 class='text-xl font-bold text-gray-900 '>
                  $data[fname] $data[lname]
                </h3>

                <button onclick='closeModal()' type='button' id='closeModal' class='text-gray-900 bg-transparent hover:bg-gray-900 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-900 dark:hover:text-white' data-modal-hide='static-modal'>
                    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                        <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
                    </svg>
                    <span class='sr-only'>Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <label for='text' class=' w-full text-xs font-medium text-gray-600'> Please contact the faculty on their phone or extension number before you verify.</label>
            <div class='mt-4 p-4 md:p-5 space-y-10 '>

                
                <div class='flex justify-between font-medium'>
                  <p class='text-base X leading-relaxed '>
                   Cabin: $data[faculty_cabin]
                  </p>
                  <p class='text-base leading-relaxed '>
                   Extension: $data[faculty_extension]
                  </p>

                </div>
                <div class='flex justify-between font-medium'>
                  <p class='text-base X leading-relaxed text-red-600'>
                  $data[phone]
                  </p>
                  <p class='text-base leading-relaxed '>
                    $data[email]
                  </p>
                </div>
                <div class='flex justify-between font-medium'>
                  <p class='text-base X leading-relaxed '>
                  Registered at :
                  </p>
                  <p class='text-base leading-relaxed border-b'>
                     $data[created]
                  </p>
                </div>
                
            </div>
            <!-- Modal footer -->
            <div class='flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600'>
            <form>
                <input id='userId' type = 'hidden' value = ' $data[user_id] ' >
                <button disabled  type='button' id='counter' data-modal-hide='static-modal'  class='text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-2xl text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800'></button>
                <button hidden   id='verifyFaculty' data-modal-hide='static-modal'  class='text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-2xl text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800'>Verify</button>

                <button hidden  id='suspendUser' data-modal-hide='static-modal'  class='py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-white rounded-2xl border border-red-200 hover:bg-red-100 hover:text-white focus:z-10 focus:ring-4 focus:ring-red-100 dark:focus:ring-red-700 dark:bg-red-800 dark:text-white dark:border-red-600 dark:hover:text-white dark:hover:bg-red-700'>Suspend</button>
                </form>
            </div>
        </div>
    </div>
</div>

";

echo $modal; 
?>

<script>
  $(document).ready(function(){
   
    let countdown = 60;

    x = setInterval(()=>{
      $("#counter").html(countdown);
      
      --countdown;
      if(countdown<0){
      clearInterval(x);
      $("button").show();
      $("#counter").hide();
      }

    },1000);



    $("#verifyFaculty").click(function(){
      const userId = $("#userId").val();
      // alert(userId);
      $.ajax({
        url: 'function.php',
        type: 'POST',
        data: {action:'verifyFaculty', input:userId},
        success: {},
        error: function(xhr, status, error) {
          console.error('Error executing PHP function:', error);
           }
      })
    })
    
    $("#suspendUser").click(function(){
      const userId = $("#userId").val();
      // alert(userId);
      $.ajax({
        url: 'function.php',
        type: 'POST',
        data: {action:'suspendUser', input:userId},
        success: function(response) {
            console.log('User suspended successfully');
        },
        error: function(xhr, status, error) {
          console.error('Error executing PHP function:', error);
           }
      })
    })

  })
</script>