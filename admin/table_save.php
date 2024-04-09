<!DOCTYPE html>
<html lang="en">
<head>
  
    <title>Saving Changes...</title>
</head>
<body>
    
</body>
</html>

<?php

/****** Saves Changes After Insert, Update, or Delete  ******/

$record = [];
if(!isset($_REQUEST['tablename'])){
    die("Table Name Query Not Found");
}


    $tableName = $_REQUEST['tablename'];
    $pageName = $_REQUEST['pagename'];
    $fields = [];
    
    include("config.php");
    include("table_functions.php");
    include("table_alias.php");  
    $save = new Save();

    // Stores all the columns of the table
    $columnNames = getFilteredColumns($tableName);
    $columnRenames = renameColumns($columnNames);

    switch($pageName){
        case 'Add':
            $save->saveRecord($tableName,$columnNames);
            
            echo "<script>
            alert('Record Inserted.');
            window.location.href='table_insert.php?tablename=$tableName';
            </script>";
            

        break;
        case 'Edit':
            $save->updateRecord($tableName,$columnNames);
            
            echo "<script>
            alert('Record Updated.');
            window.location.href='table_show.php?tablename=$tableName';
            </script>";
            

        break;
        case 'Del':
            try{
                $save->deleteRecord($tableName,$columnNames);
            }
            catch(Exception $e){

            }
                echo "<script>
            alert('Record Deleted.');
            window.location.href='table_show.php?tablename=$tableName';
            </script>";
            

        break;
    } 
    
    









// header("Location: table_insert.php");
























/* For reference */
/**
    *
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $address = $_POST['Address'];
    $gender = $_POST['Gender'];
    $dob = $_POST['DOB'];
    $blood_group = $_POST['blood_group'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp,"images/$image");
        
    
    */
?>