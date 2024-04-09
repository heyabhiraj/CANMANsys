<?php 
$tableName ='item_list'   ;
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
$form= new Form();

// Retrieve records from the specified table
$rows = getRecords($tableName, $where);

// Filter and rename columns for display according to available aliases
$columnNames = getFilteredColumns($tableName);
$columnRenames = renameColumns($columnNames);

// Uncomment the line below to display column names (for debugging purposes)
    // showColumnNames($columnNames);
    $id =  $columnNames[0] . " hello ";
    echo $id;
    // print_r($form->getInputValues($tableName,$columnNames[0],""));
    
?>