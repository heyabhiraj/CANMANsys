<?php
include("config.php");



?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="style.scss">
</head>
<body>
        
        <form action="" method="post">
        
        <select name="table" id="">
                <option value="null" selected disabled >Select Table</option>
                <?php
                foreach($tables as $table){
                 ?>
                <option value="<?php echo $table;?>">
                <?php echo $table; ?>    <br> <br>
                
                </option>
     
        <?php }?>
        </select>
        <input type="submit" value="Insert" name="operation">
        <input type="submit" value="Show" name="operation">
        </form>
</body>
</html>

<?php
if(isset($_REQUEST['table'])){
        $table = $_REQUEST['table'];
        $operation = $_REQUEST['operation'];

        header("Location: table_$operation.php?tablename=$table");
}


?>