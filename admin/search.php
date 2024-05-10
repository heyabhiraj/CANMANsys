
<?php
// Include necessary files for configuration and table functions
include("config.php");


if (!isset($_REQUEST['tablename'])) {
  die("No table found");
}
$tableName = $_REQUEST['tablename'];
// Include file containing table aliases if needed
include("table_alias.php");
include("table_functions.php");


$tableName = $_REQUEST['tablename'];
$columnNames = getFilteredColumns($tableName,$showAliases);
// Sanitize search term to prevent SQL injection
$search = trim($_POST['search']);
$searchResults = searchbar($conn, $search ,$tableName, $columnNames);
$n=1;
if ($searchResults !== false) {
  $foreignKeyValues = NULL;
  // Display search results
  foreach ($searchResults as $row) {

    $name = "'".$row[$nameField]."'";  


    echo "<tr class='p-5 bg-white odd:bg-gray-50 even:bg-gray-100 border-b hover:bg-gray-200 divide-x divide-slate-700 text-pretty'>";
    echo ' <td class="text-center py-2.5">'; echo $n; echo "</td>";
    foreach ($columnNames as $col) {
      if ($hidden = isHidden($col))

      $id = $row[$col];
      if (in_array($col, $foreignKey) !== false) { 
        $form = new form();
        if(!isset($foreignKeyValues[$col]))
        $foreignKeyValues[$col]= $form->getCategoryValues($col);                   
        $values = $foreignKeyValues[$col];
        // print category_name using category_id as index
        $k = $row[$col];

        // if($col===$searchField)    
        echo '<td title="'. $values[$k] .'" class="text-center font-medium text-sky-500 px-4 dark:text-sky-500 "><input class="categorySearch cursor-pointer hover:underline max-w-auto overflow-hidden" type="button" value="' . $values[$k] . '"</input></td>';
 
        // else
        // echo '<td class="text-center "' . $hidden . '>' . $values[$k] . '</td>';
    }
    else if (isUploadFile($col)){
      $file =  $row[$col];
      $link = '../img/'.$file;
      // echo '<td class="text-center"'."".'> <button onclick=openPopup("'.$link.'")>'. "$file" . '</button></td>';
      echo '<td class="text-center"'."".'> <img class="img cursor-pointer h-20 w-20 object-cover rounded-full" alt="'. $file .'" src='.$link.'>'. '</img></td>';



  }
      else 
      
      echo "<td title='". $row[$col]  ."' class='text-center max-w-auto overflow-hidden px-1 '".$hidden.">"  . $row[$col] . "</td>";
      }
      $n++;
      // Options Column 
      if($tableName!=='item_order' && $tableName!=='order_payment' ) 
      echo '
      <td class="flex items-center px-6 py-4">
          <a class="font-medium text-white bg-black px-3 py-2 rounded hover:underline" 
          href="table_edit.php?tablename='.$tableName.'&id='.$id.'">Edit</a>
  
          
          <button class="font-medium text-white bg-red-600 rounded px-3 py-2 hover:underline ms-3" 
          onclick="DeleteConfirm('.$name.','.$id.')">Delete</button>
      </td>';
      echo '</tr>';   
  }

  echo $searchResults;
} else {
  echo "No results found.";
} 


?>


<script>
        $(document).ready(function() {
            function  searchRecords(search_term){
                if (search_term.length>=0) {
                    $.ajax({
                        url: "search.php?tablename=<?php echo $tableName;?>",
                        type: "POST",
                        data: {
                            search: search_term
                        },
                        success: function(data) {
                            $("#table-data").html(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error:", textStatus, errorThrown);
                            // Optional: Display an error message to the user
                        }
                    });
                }
            }




            $(".searchbtn").click(function() {
              var search_term = $(this).val(); // Assuming you want to use the value from #search
              searchRecords(search_term);
              $("#search").val(search_term);
              $("#search").focus();
            }); 


            // triggers when click category button
            $(".categorySearch").click(function() {
                var search_term = $(this).val();
                searchRecords(search_term); // lists items of specified category
                $("#search").val(search_term);
                $("#search").focus();
                $("#clearSearch").style.display = '';
            });

            
            $(".img").click(function(){
                var url = $(this).attr("src");
                var width = 500;
                var height = 500;
                var left = (window.innerWidth - width) / 2;
                var top = (window.innerHeight - height) / 2;
                var features = "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top;

                // Open the popup window
                window.open(url, "_blank", features);

            });

        });
    </script>