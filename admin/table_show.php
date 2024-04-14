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
$rows = getRecords($tableName, $where, $limit, $page);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show <?php echo $tableAliases[$tableName]; ?> </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
</head>

<body>

    <div class="flex h-screen justify-center items-center">

        <div class="relative overflow-x-auto shadow-lg sm:rounded-lg p-5">

            <!-- Title of the table -->
            <h2 class="font-bold text-center text-2xl text-yellow-700 border-b"><?php echo $tableAliases[$tableName]; ?> </h2>
            <!-- Search Box  -->

            <div class="m-2 relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..." />
                <div class="absolute inset-y-0 end-0 flex items-center " id="clearSearch">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" mr-2 w-4 h-4 text-gray-500 dark:text-gray-400 hover:text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <!-- search end  -->

            <!-- Table Starts Here -->
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-100">
                <!-- Column Names/ Headings -->
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Serial No.</th>
                        <!-- Printing headings/ column aliases  -->
                        <?php foreach ($columnRenames as $field => $col) {
                            $hidden = isHidden($col);
                            echo '<th scope="col" class="px-6 py-3"' . $hidden . '>' . $col . '</th>';
                        } ?>

                        <th scope="col" class="px-6 py-3">Options</th>
                    </tr>
                </thead>


                <!-- Search data  -->
                <tbody id="table-data">
                    </tr>
                    <!-- Pagination -->
                    <?php $ni = ($page - 1) * $limit + 1;


                    // Loop to print n number of rows    // $rows is a 2D array of the whole table
                    for ($n = 0; $n < count($rows); $n++) {
                        $id = "";
                        $name = "'" . $rows[$n][$nameField] . "'";  ?>

                        <!-- Row  -->
                        <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <!-- Col 1 Serial No. -->
                            <td class="text-center"><?php echo $ni;
                                                    $ni++; ?> </td>

                            <!-- Loop to print i number of columns -->
                            <?php for ($i = 0; $i < count($columnNames); $i++) {
                                // Hide id column from display 
                                if ($hidden = isHidden($columnNames[$i]))
                                    $id = $rows[$n][$columnNames[$i]];    // storing the id to use in operations

                                // checking for foreign keys
                                if (in_array($columnNames[$i], $foreignKey) !== false) {
                                    $form = new form();
                                    $values = $form->getCategoryValues($columnNames[$i]);
                                    $fk = $rows[$n][$columnNames[$i]];
                                    // print fk_name using fk_id as index $fk
                                    echo '<td class="text-center">' . $values[$fk] . '</td>';
                                } else if (isUploadFile($columnNames[$i])) {
                                    $file =  $rows[$n][$columnNames[$i]];
                                    $link = '../img/' . $file;
                                    echo '<td class="text-center"' . "" . '> <button onclick=openPopup("' . $link . '")>' . "$file" . '</button></td>';
                                } else
                                    //  Print elements from assoc array 
                                    echo '<td class="text-center"' . $hidden . '>' .  $rows[$n][$columnNames[$i]] . '</td>';
                            } ?>
                            <!-- Options Column -->
                            <td class="flex items-center px-6 py-4">
                                <!-- Edit -->
                                <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline" href="table_edit.php?<?php echo "tablename=" . $tableName . "&id=" . $id; ?>">Edit</a>
                                <!-- Delete -->
                                <button class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3" onclick="DeleteConfirm(<?php echo $name . ',' . $id; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php } ?>
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


                    <?php  ?>

            </nav>
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



        $(document).ready(function() {
            $("#search").keyup(function() {
                var search_term = $(this).val();
                // alert(search_term);
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
            });
        });
    </script>
    <script>
        function openPopup(url) {
            // Define the URL and properties of the popup window

            var width = 500;
            var height = 500;
            var left = (window.innerWidth - width) / 2;
            var top = (window.innerHeight - height) / 2;
            var features = "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top;

            // Open the popup window
            window.open(url, "_blank", features);
        }
    </script>
</body>

</html>