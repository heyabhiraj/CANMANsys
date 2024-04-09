<?php

$columnAliases = [];      // for storing aliases of the table fields
$where = "";        //for storing where clause of the query

/**
 *  Alias to be displayed instead of columnNames.
 *  These will be used as table headings and input labels
 */
switch($tableName){
    case 'item_category':
        $columnAliases = [
            'category_id' => 'Id',
            'category_name' => 'Name',
            'category_status' => 'Status',
            'category_description' => 'Description',
            'category_image' => 'Image'
            
           ] ; 
      
        break;
        
    case 'item_list':
        $columnAliases = [
            'item_id' => 'Id',
            'category_id' => 'Category',
            'item_name' => 'Name',
            'item_price' => 'Price',
            'item_description' => 'Description',
            'item_status' => 'Status',
            'prep_time' => 'Preparation Time',
            'item_image' => 'Image',
           ] ;  
        break;

    case 'item_schedule':
        $columnAliases = [
            'schedule_id' => 'Id',
            
        ];
        $columnAliases[$columnName];
        break;

    // Add more cases based on the added tables
    default:
    break;
}


// Aliases to be displayed instead of real entity names
// Used in title tag and form headings
$tableAliases= [
    'item_category' => 'Food Category',
    'item_list' => 'Food Item',
    'item_schedule' => 'Serve Schedule',
];

$foreignKey=[];     // Store data of foreign keys present in the table 
// like `related_table` => `their_primary_key_acting_as_foreign_key`
//

switch($tableName){
    case 'item_list':
        // says that `item_list` is related to `item_category` throigh `category_id`
        $foreignKey = [
            'item_category' => 'category_id'    
            // Add more as more fk constraints are created
        ];
        break;
    case 'item_schedule':
        $foreignKey = [
            'item_list' => 'item_id'
        ];
        break;
    // Add more cases according to new entities and relations created
    default:
    break;
}



$categoryColumnList = []; // Stores the relevant column name to be fetched using the foriegnkey
// like `related_table` => `column_required_to_display`
switch($tableName){
    case 'item_list':
        $categoryColumnList = [
            'item_category'=>'category_name'
            // Add more but only one column from each table
        ];
        break;
    default:
    break;
}


/** Field Names and keywords to create 'Text Area'
 *  Check against aliases/columnRenames.
 *  If column name match any in the below array a function is defined to create a textArea.
 *  Add more keywords according to your need but make sure you mention them in the 
 *  columnAliases array under the right table name case.
 */
$forTextArea = array(
    'COMMENT',
    'FEEDBACK',
    'DESCRIPTION',
    'BIO',
    'ADDRESS',
    'LOCATION',
    'NOTES',
    'REVIEWS'
);


/*********************```R I S K Y    Z O N E```******************/

/**
 *  Checks against the keywords that need to stay hidden in the form.
 *  Currently, it is limited to Id and may not be altered or modified since many functions are 
 *  dependent on this variable
 */
$toHide= array(
    'Id'   
);


/*********************```E N O U G H    S C R O L L I N G```*********************/


/** 2-D array of all input types and their corresponding data type in MySQL
 * Key is the input type in HTML and value is the corresponding data type in MySQL
 * Example: $dataTypes['number step = 0.01 '] = array('DECIMAL', 'FLOAT', 'DOUBLE');
 * This means that if the user selects the data type 'number step = 0.01 ' in the form,
 * the corresponding data type in MySQL will be 'DECIMAL', 'FLOAT', or 'DOUBLE' and vice
 */
$dataTypes = array(
    'number ' => array(
        'INT',
        'TINYINT',
        'SMALLINT',
        'MEDIUMINT',
        'BIGINT'
    ),
    'number step = 0.01 ' => array(
        'DECIMAL',
        'FLOAT',
        'DOUBLE',
    ),
    'date ' => array(
        'DATE'
    ),
    'text ' => array(
        'CHAR',
        'VARCHAR',
        'TINYTEXT',
        'TEXT',
        'MEDIUMTEXT',
        'LONGTEXT',
    ),
    'enum' => array(
        'ENUM'
    ),
    'spatial ' => array(
        'GEOMETRY',
        'POINT',
        'LINESTRING',
        'POLYGON',
        'MULTIPOINT',
        'MULTILINESTRING',
        'MULTIPOLYGON',
        'GEOMETRYCOLLECTION'
    )
);











/* Strings for sql clauses */
$order = "";        //for storing order by clause of the query
$limit = "";        //for storing limit clause of the query
$join = "";         //for storing join clause of the query
$group = "";        //for storing group by clause of the query
$having = "";       //for storing having clause of the query
$select = "";       //for storing select clause of the query
$from = "";         //for storing from clause of the query
$groupBy = "";      //for storing group by clause of the query
$having = "";       //for storing having clause of the query
$orderBy = "";      //for storing order by clause of the query
$limit = "";        //for storing limit clause of the query
$join = "";         //for storing join clause of the query
$group = "";        //for storing group by clause of the query
$having = "";       //for storing having clause of the query
$select = "";       //for storing select clause of the query
/**/

?>