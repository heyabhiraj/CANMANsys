
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

if ($searchResults !== false) {
  // Display search results
  foreach ($searchResults as $row) {
    $name = "'".$row[$nameField]."'";  
    $id=$row[$columnNames[0]];
    echo "<tr class='bg-white border-b dark:bg-gray-900 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>";

    foreach ($columnNames as $col) {

      if (in_array($col, $foreignKey) !== false) { 
        $form = new form();
        
        $values = $form->getCategoryValues($col);
        // print category_name using category_id as index
        $k = $row[$col];
        echo '<td class="text-center "' . $hidden . '>' . $values[$k] . '</td>';
    }
    else if (isUploadFile($col)){
      $file =  $row[$col];
      $link = '../img/'.$file;
      echo '<td class="text-center"'."".'> <button onclick=openPopup("'.$link.'")>'. "$file" . '</button></td>';

  }
      else 
      
      echo "<td class='text-center'>"  . $row[$col] . "</td>";
      }


   echo"<td class='flex items-center px-6 py-4'>
    <a class='font-medium text-blue-600 dark:text-blue-500 hover:underline' href='table_edit.php?tablename=$tableName&id=$id'>Edit</a>
    <button class='font-medium text-red-600 dark:text-red-500 hover:underline ms-3' 
    onclick=\"DeleteConfirm($name,$id)\">Delete</button>
</td></tr>";

  }
// Echo the generated HTML content for frontend update
  echo $searchResults;
} else {
  echo "No results found.";
} 


?>

