<?php
if (!isset($_REQUEST['tablename'])) {
    die("No table found");
}

$tableName = $_REQUEST['tablename'];
// Include necessary files for configuration and table functions
include("config.php");


// Include file containing table aliases if needed
include("table_alias.php");
include("table_functions.php");

// Fetch column names of the specified table
// $columnNames = getColumnNames($tableName);  
// $columnNames is a 1D array of all the names of attributes

// Uncomment the line below to display column names (for debugging purposes)
// showColumnNames($columnNames);

// Initialize variables
$rows = [];
$where = "";    //where clause for the query

// Retrieve records from the specified table
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;;
$rows = getRecords($tableName, $where, $orderBy, $limit, $page);

// Filter and rename columns for display according to available aliases
$columnNames = getFilteredColumns($tableName, $showAliases);
$columnRenames = renameColumns($columnNames);

// Uncomment the line below to display column names (for debugging purposes)
// showColumnNames($columnNames);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=.8">
    <title>Show <?php echo $tableAliases[$tableName]; ?> </title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@28,600,1,200" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
</head>

<body>
    <?php include('./sidebar.php'); ?>
    <div class="p-4 sm:ml-64 ">
        <div class="border-gray-200 rounded-lg">

            <!-- Main Section -->
            <div id="main" class="flex h-max justify-center items-center">


                <!-- Content Here -->
                <div class="m-2 p-5 relative overflow-x-auto shadow-2xl sm:rounded-lg">

                    <!-- Name of the table -->
                    <h2 class="font-bold text-center text-2xl text-yellow-700 border-b"><?php echo $tableAliases[$tableName]; ?> </h2>


                    <!-- Search Area  -->
                    <div class="m-2  relative ">
                        <!-- Search Icon -->
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 1"> <span class="text-white material-symbols-outlined border-white">search</span> </div>

                        <!-- Search Bar-->
                        <input type="text" id="search" class="block w-full p-4 ps-10 text-sm text-gray-900 rounded-3xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Anything" />

                        <!-- X icon -->
                        <div id="clearSearch" class="absolute inset-y-0 end-5 flex items-center cursor-pointer "><span class="text-white material-symbols-outlined">close</span> </div>

                    </div>

                    <!-- Table Starts Here -->
                    <table class="w-max text-pretty text-sm text-left rtl:text-right text-gray-500 dark:text-gray-100">

                        <!-- Column Names/ Headings -->
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 ">
                            <tr class="divide-x divide-gray-600">
                                <!--  -->
                                <th scope="col" class="px-6 py-3">Serial No.</th>

                                <!-- Printing headings/ column aliases  -->
                                <?php foreach ($columnRenames as $field => $col) {
                                    $hidden = isHidden($col);
                                    echo '<th scope="col" class="px-6 py-3"' . $hidden . '>' . $col . '</th>';
                                } 
                                
                                if($tableName!=='item_order')
                                echo '<th scope="col" class="px-6 py-3">Options</th>';
                                ?>
                            </tr>
                        </thead>

                        <!-- table-data is manipulated by ajax during search -->
                        <tbody id="table-data">

                            <?php $ni = ($page - 1) * $limit + 1;       // pagination


                            // Loop to print n number of rows    
                            for ($n = 0; $n < count($rows); $n++) {     // $rows is a 2D array containing the whole table
                                $id = "";   // stores the id of the particular record for edit/delete
                                $name = '"' . addslashes($rows[$n][$nameField]) .'"';      // stores the name that represents the particular record
                            ?>

                                <!-- Printing a row  -->
                                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 divide-x divide-slate-700">
                                    <!-- First column - Serial No. -->
                                    <td class="text-center py-2.5"><?php echo $ni; $foreignKeyValues=NULL;

                                                            $ni++; ?> </td>

                                    <!-- Loop to print i number of columns -->
                                    <?php for ($i = 0; $i <count($columnNames); $i++) {
                                        // Hide id column from display 
                                        if ($hidden = isHidden($columnNames[$i]))
                                            $id = $rows[$n][$columnNames[$i]];    // storing the id to use in edit/delete

                                        // checking for foreign keys
                                        if (in_array($columnNames[$i], $foreignKey) !== false) {
                                            $form = new form();
                                            if(!isset($foreignKeyValues[$columnNames[$i]]))
                                            $foreignKeyValues[$columnNames[$i]]= $form->getCategoryValues($columnNames[$i]);
                                            $values = $foreignKeyValues[$columnNames[$i]];      // k stores the foreign key value for this record 
                                            $k = $rows[$n][$columnNames[$i]];       // k stores the foreign key value for this record 
                                            // check for name representation
                                            if ($columnNames[$i] === $nameField)
                                            $name = "'".addslashes($values[$k])."'";

                                            // make buttons to search category
                                            // if($columnNames[$i]===$searchField)
                                            echo '<td class="text-center font-medium text-blue-400 px-4	 dark:text-blue-400 "><input class="categorySearch cursor-pointer hover:underline" type="button" value="' . $values[$k] . '"</input></td>';


                                            // print fk_name using fk_id as index $fk normally
                                            // else
                                            // echo '<td class="text-center">' . $values[$k] . '</td>';
                                        }

                                        // check for upload files
                                        else if (isUploadFile($columnNames[$i])) {
                                            $file =  $rows[$n][$columnNames[$i]];
                                            $link = '../img/' . addslashes($file);
                                            // echo '<td class="text-center"'."".'> <button onclick=openPopup("'.$link.'")>'. "$file" . '</button></td>';
                                            echo '<td class="text-center"' . "" . '> <img class="img cursor-pointer h-20 w-20 object-cover rounded-full" alt="' . $file . '" src=' . $link . '>' . '</img></td>';
                                        }
                                        // print cell nomally
                                        else
                                            //  Print elements from assoc array 
                                            echo '<td class="text-center "' . $hidden . '>' .  $rows[$n][$columnNames[$i]] . '</td>';
                                    } ?>
                                        <!-- Options Column -->

                                    <?php if($tableName!=='item_order') 
                

                                    echo '
                                    <td class="flex items-center px-6 py-4">
                                        <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" 
                                        href="table_edit.php?tablename='.$tableName.'&id='.$id.'">Edit</a>

                                        
                                        <button class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3" 
                                        onclick="DeleteConfirm('.$name.','.$id.')">Delete</button>
                                    </td>';
                            echo '</tr>';    
                       } ?>
                        </tbody>

                    </table>


                    <!-- Table Navigation -->
                    <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">

                        <!-- Add more entries -->
                        <?php if (!in_array($tableName, $blockEntries)) echo
                        '<a href="table_Insert.php?tablename=' . $tableName . '" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Add + </a>'; ?>

                        <!-- If multiple pages -->
                        <?php
                        $totalPages = calculatePaginationInfo($tableName, $where, $limit);

                        if ($totalPages > 1) {  ?>

                            <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                                <?php
                                if ($page > 1) {
                                    echo "<li><a class='flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' href='table_Show.php?tablename=$tableName&page=" . ($page - 1) . "'>Previous</a></li>";
                                } ?>
                            <?php
                            // Page number links
                            for ($i = 1; $i <= $totalPages; $i++) {
                                $activeClass = ($i == $page) ? "active" : "";
                                echo "<li class='$activeClass'><a class='flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white' href='table_Show.php?tablename=$tableName&page=" . $i . "'>" . $i . "</a></li>";
                            }
                            if ($page < $totalPages) {
                                echo "<li><a class='flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' href='table_Show.php?tablename=$tableName&page=" . ($page + 1) . "'>Next</a></li>";
                            }

                            // 
                        } ?>

                            </ul>
                            <?php  ?>

                    </nav>
                </div>
            </div>

        </div>
    </div>
    <script>
        function DeleteConfirm(name, id) {

            let url = "table_save.php?<?php echo "tablename=$tableName&pagename=Del&id="; ?>";
            if (confirm("Are you sure to delete this record '" + name + "'?"))
                window.location.href = url + id;
            else
                // Force a hard reload (clear cache) if supported by the browser
                window.location.reload(true);
        }
    </script>

    <script>
        $(document).ready(function() {

            // search function
            const searchRecords = (search_term) => {
                if (search_term.length >= 0) {
                    $.ajax({
                        url: "search.php?tablename=<?php echo $tableName; ?>",
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

            $("#search").focus();
            
            // triggers when user type in search bar
            $("#search").keyup(function() {
                var search_term = $(this).val();
                searchRecords(search_term);
            });

            // triggers when click category button
            $(".categorySearch").click(function() {
                var search_term = $(this).val();
                searchRecords(search_term); // lists items of specified category
                $("#search").val(search_term);
                $("#search").focus();
                $("#clearSearch").style.display = '';
            });

            // triggers when click clear-search button
            $("#clearSearch").click(function() {
                var search_term = "";
                searchRecords(search_term);
                $("#search").val(search_term);
                $("#search").focus();
            });

            // triggers when click image to zoom it
            $(".img").click(function() {
                var url = $(this).attr("src");
                var width = 500;
                var height = 500;
                var left = (window.innerWidth - width) / 2;
                var top = (window.innerHeight - height) / 2;
                var features = "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top;

                // Open the popup window
                window.open(url, "_blank", features);

            });


            // Extracting table name from url
            const url = window.location.href;
            const queries = new URL(url);
            const tableName = queries.searchParams.get('tablename');
            console.log(tableName);

            // searchbar placeholder animation
            let placeholder = [];
            switch (tableName) {
                case 'item_list':
                    placeholders = [
                        "Search Anything",
                        "Search \"Margherita Pizza Large\"",
                        "Search \"Available\"",
                        "Search \"mozzarella\"",
                        "Search by clicking on any Category",
                    ];
                    break;
                case 'item_category':
                    placeholders = [
                        "Search Anything",
                        "Search \"Appetizer\"",
                        "Search \"Dessert\"",
                        "Search \"Active\"",
                        "Search \"Inactive\"",
                    ];
                    break;
                case 'item_order':
                    placeholders = [
                        "Search Anything",
                        "Search \"Cooking\"",
                        "Search by clicking on any Email",
                        "Search \"Served\"",
                        "Search by Amount",
                        "Search \"Cancelled\"",
                        "Search by clicking on any Item ",
                    ];
                    break;
                case 'item_schedule':
                    placeholders = [
                        "Search Anything",
                        "Search \"Monday\"",
                        "Search \"Active\"",
                        "Search by clicking on any Item ",
                    ];
                    break;
                case 'registered_user':
                    placeholders = [
                        "Search Anything",
                        "Search by Name",
                        "Search by Phone",
                        "Search by Email",
                    ];
                    break;

            }
            let i = 0;
            const changePlaceholder = () => {
                $("#search").attr("placeholder", placeholders[i]);
                i = (i + 1) % placeholders.length;
            }
            setInterval(changePlaceholder, 2000);
        });
    </script>
    <script>
        // opens image        }
    </script>
</body>

</html>