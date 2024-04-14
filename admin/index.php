<?php
include("config.php");



?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Select Table</title>
        <!-- <link rel="stylesheet" href=""> -->
        <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
        <div class="flex h-screen justify-center items-center">

                <div class="">
                        <form class="" action="" method="post">


                                <!-- Select the Table Name -->
                                <select class="outline block margin-50" name="table" id="">
                                        <option value="null" selected disabled>Select Table</option>
                                        <?php
                                        foreach ($tables as $table) {

                                        ?>
                                                <option value="<?php echo $table; ?>">
                                                        <?php echo $table; ?> <br> <br>

                                                </option>

                                        <?php } ?>
                                </select>
                                <!--Submit Buttons Takes You The Respective Page-->
                                <input class="px-3 py-2 bg-gray-300 rounded " type="submit" value="Insert" name="operation">
                                <input class="px-3 py-2 bg-black text-white rounded" type="submit" value="show" name="operation">
                        </form>
                </div>
        </div>
</body>

</html>

<?php
if (isset($_REQUEST['table'])) {
        $table = $_REQUEST['table'];
        $operation = $_REQUEST['operation'];
        header("Location: table_$operation.php?tablename=$table");
}


?>
