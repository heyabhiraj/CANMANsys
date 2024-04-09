<?php
$item = $_REQUEST['item_id'];
include("config.php");
    // Delete operation
    $sql_delete = "DELETE FROM item_list WHERE item_id = $item";

    if ($conn->query($sql_delete) === TRUE) {
        
        header("Location: table_show.php?tablename=item_list");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
?>
