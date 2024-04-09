<?php 
if(!isset($_REQUEST['tablename'])){
    die("No table found");
}

$tableName = $_REQUEST['tablename']    ;
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
$rows = getRecords($tableName, $where);

// Filter and rename columns for display according to available aliases
$columnNames = getFilteredColumns($tableName);
$columnRenames = renameColumns($columnNames);

// Uncomment the line below to display column names (for debugging purposes)
    // showColumnNames($columnNames);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show <?php echo $tableAliases[$tableName];?> </title>
    <link rel="stylesheet" href="style.scss">
</head>
<body>
    <table border="1" width="100">
        <thead>
            <th>Serial No.</th>
            <!-- Printing column aliases  -->
            <?php foreach($columnRenames as $col) {
                
                $hidden = isHidden($col);
                echo '<th '.$hidden.'>'. $col .'</th>';
             } ?>

            <th colspan="2">Options</th>
        </thead>
        
        <!-- Loop to print n number of rows -->
        
            <?php for($n = 0; $n < count($rows); $n++) { ?>
            <tr> 
                    <td><?php echo $n+1; ?>  </td>
                <!-- Loop to print i number of columns -->
                <?php for($i = 0; $i < count($columnNames); $i++) { 
                     // Hide id column from display 
                    if($hidden = isHidden($columnNames[$i]) )
                     $id = $rows[$n][$columnNames[$i]];

                    // 
                    if(in_array($columnNames[$i],$foreignKey)!==false){{
                            $form = new form();
                            $values= $form->getCategoryValues();
                            // print category_name using category_id as index
                            $k= $rows[$n][$columnNames[$i]];
                        echo '<td '.$hidden.'>'.$values[$k].'</td>';
                    }}
                    
                    
                    else
                    //  Print elements from assoc array 
                    echo '<td '.$hidden.'>'.$rows[$n][$columnNames[$i]].'</td>';
                } ?> 
                  
                <td><a href="table_edit.php?<?php echo "tablename=".$tableName."&id=".$id;?>">Edit</a></td>
                <td><button class=del onclick="DeleteConfirm(<?php echo "$n,$id"; ?>)" >Delete</button></td>
                </li>
            </tr>
            <?php } ?>
        
    </table>

    <script> function DeleteConfirm(n,id) {

        n++;
    let url = "table_save.php?<?php echo "tablename=$tableName&pagename=Del&id=";?>";
      if(confirm("Are you sure to delete this item no. "+n+"?"))
      window.location.href=url+id;
        else
      // Force a hard reload (clear cache) if supported by the browser
    window.location.reload(true);
     }
</script>
</body>
</html>
