<?php
if (isset($_REQUEST['tablename']))
    $tableName = $_REQUEST['tablename'];
else
    die("Table Not Found");

include("config.php");
include("table_alias.php");
include("table_functions.php");
// print_r($tableName);
if (in_array($tableName, $blockEntries))
    die("Direct insertion not allowed in the selected table");

$form = new Form();

$columnNames = getFilteredColumns($tableName, $inputAliases);
// print_r($columnNames);
//
$required =  isRequired($tableName, $columnNames[0]);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add <?php echo $tableAliases[$tableName]; ?></title>
    <!-- <link rel="stylesheet" href="style.scss"> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style></style>
<link rel="stylesheet" href="tailwindmain.css"></head>


<body><?php include('./sidebar.php'); ?>
    <div class="p-4 sm:ml-64 ">
        <div class="border-gray-200 rounded-lg">
            <div class="flex h-screen justify-center items-center ml-20">
                <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-5 overflow-hidden">
                    <form id="insert" class="" action="table_save.php" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="pagename" value="Add">
                        <input type="hidden" name="tablename" value="<?php echo $tableName ?>">

                        <h1 class="text-4xl font-bold border-b text-yellow-600">
                            <?php echo "Add " . $tableAliases[$tableName] ?> </h1>


                        <?php
                        $value = "";
                        foreach ($columnNames as $column) {


                            //skip id column
                            if (isHidden($column))
                                continue;

                            $form->createLabel($column, $inputAliases[$column]);


                            $form->createInput($tableName, $column, $value);
                        }
                        ?>
                        <button class="bg-black rounded p-3 text-white mt-5 cursor-pointer" type="submit" value="Insert">Insert</button>

                    <br> <br>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('insert').addEventListener('submit', function(event) {
            console.log("Form submitted"); // Check if the form submission event is being captured
            var i = 0;
            while (document.getElementsByTagName('select').length) {
                var select = document.getElementsByTagName('select')[i]; // Get the first select element
                console.log("Selected index:", select.selectedIndex); // Check the index of the selected option
                if (select.selectedIndex === 0) {
                    console.log("Preventing form submission"); // Check if this block is being executed
                    select.setCustomValidity('Please select an option');
                    event.preventDefault(); // Prevent form submission
                } else {
                    console.log("Allowing form submission"); // Check if this block is being executed
                    select.setCustomValidity(''); // Clear any previous validation message
                }
                ++i;
            }
        });
        document.getElementById('insert').addEventListener('change', function() {
            var i = 0;
            while (document.getElementsByTagName('select').length) {
                var select = document.getElementsByTagName('select')[i];
                if (select.selectedIndex !== 0) {
                    select.setCustomValidity(''); // Reset custom validity message
                }
                ++i;
            }
        });
    </script>