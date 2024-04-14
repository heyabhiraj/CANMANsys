<?php
// Functions



/************ DISPLAY FUNCTIONS **************/

    /**
     * Get column names of a table using Desc command and fetching 
     * names under 'Field' column.
     * 
     * @param string $tableName - The name of the table.
     * @return array $col - An array containing the column names.
     */
    function getColumnNames($tableName)
    {
        global $conn;

        // SQL query to get column information
        $sql = "DESC $tableName";

        // Execute the query
        $result = $conn->query($sql) or die("Could not get column names");

        $col = []; // An array to store the names

        // Fetch each row and store the column names
        for ($i = 0; $row = $result->fetch_assoc(); $i++) {
            $col[$i] = $row['Field'];
        }

        // Return the array of column names
        return $col;
    }

    /**
     * Filter columns based on aliases by unsetting the index that does not match the
     * aliases array
     * 
     * @param array $columnNames - An array containing column names.
     * @return array - An array containing filtered column names.
     */
    function getFilteredColumns($tableName,$filters)
    {
        $columnNames = getColumnNames($tableName);
        define("LEN", count($columnNames)); // to keep the length constant
        
        // Unset elements based on aliases
        for ($i = 0; $i < LEN; $i++) {
            if (!isset($filters[$columnNames[$i]]))
            unset($columnNames[$i]);
    }
        // Re-index the array and return
        $columnNames = array_values($columnNames);
        return $columnNames;
    }

    /**
     * Rename columns based on aliases array.
     * 
     * @param array $columnNames - An array containing column names.
     * @return array - An array containing renamed column names.
     */
    function renameColumns($columnNames)
    {
        global $showAliases;
        $columnRename=[];

        // Iterate through column names and rename based on aliases
        for ($i = 0; $i < count($columnNames); $i++) {
            if (isset($showAliases[$columnNames[$i]]))
                $columnRenames[$columnNames[$i]] = $showAliases[$columnNames[$i]];
        }

        // Return the array of renamed column names
        return $columnRenames;
    }



    /**
     * Display column names.
     * 
     * @param array $columnNames - An array containing column names.
     */
    function showColumnNames($columnNames)
    {
        foreach ($columnNames as $col) {
            // Show the attribute names
            echo $col . "<br>";
        }
    }
//

/**
 *  Search the key in the table
 * 
 *  @param string $search - key value for search item
 *  @param string $tableName - Name of the table
 *  @param array $columnNames - headings of the columns
 *  @return array $searchResults - 2D array that stores all the columns
 */
function searchbar($conn, $search, $tableName, $columnNames)
{
    // Sanitize search term to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $search);

    // Construct the WHERE clause of the SQL query dynamically
    $whereClause = ""; $whereClause2="";
    foreach ($columnNames as $column) {
        global $foreignKey, $categoryColumnList;
        $form = new Form();
        if (in_array($column, $foreignKey) !== false){
            $value = $form->getCategoryValues($column);

            if($key=array_search($searchTerm,$value))
            $whereClause .= $column . " LIKE '%$key%' OR ";  

        }
        else 
        $whereClause .= $column . " LIKE '%$searchTerm%' OR ";
    }
    // Remove the last 'OR' from the WHERE clause
    $whereClause = rtrim($whereClause, "OR ");

    // Perform the search query
    $sql = "SELECT * FROM $tableName WHERE " . $whereClause. "LIMIT 10";

    // Execute the query
    $result = $conn->query($sql);
    // Check if any results were found
    if (mysqli_num_rows($result) > 0) {
        // Initialize an empty array to store search results
        $searchResults = array();

        // Fetch all rows from the result set
        while ($row = mysqli_fetch_assoc($result)) {
            // Add each row to the search results array
            $searchResults[] = $row;
        }

        // Return the search results array
        return $searchResults;
    } else {
        // No results found
        return false;
    }
}

//end of search


/**
 * Get records from a table based on a WHERE condition.
 * 
 *  @param string $tableName - The name of the table.
 *  @param string $where - The WHERE condition for the query.
 *  @param int $limit - Number of entries limited per page.
 *  @param int $page - Current page number. 
 *  @return array - An associative 2D array containing fetch_all records.
 */
function getRecords($tableName, $where, $limit, $page)
{
    global $conn;

    // Calculate offset based on page and limit
    $offset = ($page - 1) * $limit;

    // Construct SQL query with LIMIT and OFFSET
    $sql = "SELECT * FROM $tableName $where LIMIT $limit OFFSET $offset";

    // Execute the query
    $result = $conn->query($sql);

    // Check for errors (optional)
    if (!$result) {
        echo "Error fetching data: " . mysqli_error($conn);
        return false; // Or throw an exception
    }

    // Fetch all records as an associative array
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    // Return the array of records
    return $rows;
}


/**
 *  Calculates the total number of pages
 *  
 *  @param string $tableName - Name of the table in question
 *  @param string $where - where clause
 *  @param int $limit - Limit of rows in a single page
 * 
 *  @return int $totalPages - Total number of pages required  
 */
function calculatePaginationInfo($tableName, $where, $limit)
{
    global $conn;

    // Construct SQL query to get total records
    $totalRecordsQuery = "SELECT COUNT(*) AS total FROM $tableName $where";

    // Execute the query
    $totalResult = $conn->query($totalRecordsQuery);

    // Check for errors
    if (!$totalResult) {
        echo "Error fetching total records: " . $conn->error;
        return false;
    }

    // Fetch total records from the result
    $totalRecords = $totalResult->fetch_assoc()["total"];

    // Calculate total pages
    $totalPages = ceil($totalRecords / $limit);

    // Return total pages
    return $totalPages;
}








/************ SAVE FUNCTIONS ***************/
class Save
{

    function keyByValue($array, $value)
    {
        foreach ($array as $key => $val) {
            if ($val == $value)
                return $key;
        }
        return null;
    }

    /**
     * Save new records into the database
     *  
     * @param string $tableName - stores name of the table to be inserted
     * @param array $columnNames - stores array of all the relevant columns in the table
     * 
     */

    function saveRecord($tableName, $columnNames)
    {
        global $conn;
        $fields = [];
        foreach ($columnNames as $column) {
            // skip hidden fields
            if (isHidden($column))
                continue;
            // check for upload files
            if(isUploadFile($column)){
                if(($_FILES['file']['error'] === UPLOAD_ERR_OK)){
                    $fileName = $_FILES['file']['name'];
                    // Remove whitespace and special characters from the file name
                    $cleanFileName = preg_replace("/[^a-zA-Z0-9-_\.]/", "", $fileName);
                    $record[$column] = $cleanFileName;
                }
                
            }
            // check for other data
            else
            if (isset($_REQUEST[$column]))
                // $record stores array of all the values
                $record[$column] = $_REQUEST[$column];
            else
                $record[$column] = "";
            // $fields store array of eligible columns
            array_push($fields, $column);
        }
        $sql = "INSERT INTO $tableName(" . implode(",", $fields) . ")
        VALUES('" . implode("','", $record) . "')";

        echo $sql;
        $conn->query($sql) or die("Query failed in insert");
    }



    function updateRecord($tableName, $columnNames)
    {
        global $conn;
        $set = [];
        $id = "";
        foreach ($columnNames as $column) {
            // if column is an id
            if (isHidden($column)) {
                $id = " $column = $_REQUEST[id]";
                continue;
            }
            // check whether the column sends a file
            if(isUploadFile($column)){
                // checks that there is no error
                if(($_FILES['file']['error'] === UPLOAD_ERR_OK)){
                    $fileName = $_FILES['file']['name'];
                    // Remove whitespace and special characters from the file name
                    $cleanFileName = preg_replace("/[^a-zA-Z0-9-_\.]/", "", $fileName);
                    $set[$column] = "$column = '$cleanFileName'";  // stores array for columnName  = value
                }
                 continue;
            }
            $value = mysqli_real_escape_string($conn, $_REQUEST[$column]);
            $set[$column] = "$column = '$value'";
        }

        $sql = "UPDATE $tableName set " . implode(",", $set) . " WHERE $id";
        echo $sql;
        $conn->query($sql) or die("Query Failed in update");
    }
    function deleteRecord($tableName, $columnNames)
    {
        global $conn;
        $id = "";
        foreach ($columnNames as $column) {
            if (isHidden($column)) {
                $id = "$column = $_REQUEST[id]";
                break;
            }
        }
        // Delete Query
        $sql = "DELETE FROM $tableName WHERE $id";
        echo $sql;

        $conn->query($sql) or die("Query Failed in update");
    }
}
/************************************/






/*********** INSERT/ UPDATE FUNCTIONS ***********/
class Form
{



    public function createInput($tableName, $columnName, $value)
    {
        global $foreignKey; 
        // detects the input type of the particular column
        $check = $this->setInputType($tableName, $columnName);
        // check if it is a selection type
        if ($check == "enum")
            return $this->createSelection($tableName, $columnName, $value);
        // check if it qualifies for text area
        $check = isTextArea($columnName);
        if ($check == "text")
            return $this->createTextArea($columnName, $value);
        // check if it qualifies for an uploade file
        $check = isUploadFile($columnName);
        if ($check == "file")
            return $this->createUpload($columnName, $value);
        // 
        if (in_array($columnName, $foreignKey) !== false)
            return $this->createCategorySelection($columnName, $value);
        // default
        return $this->createInputTag($tableName, $columnName, $value);
    }

    /** Input Tag Functions **/
    /**
     *  Create inpput tags except for enums and descriptions
     * 
     *  @param string $tableName - The name of the table used in other function calls.
     *  @param string $columnName - The name of the column from the columnNames array.
     *  @param string $selectedValue - The selected value.
     ***/
    protected  function createInputTag($tableName, $columnName, $value)
    {
        global $showAliases;
        $form = new Form();
        $required = isRequired($tableName, $columnName);     // Check if column is required 
        $inputType = $form->setinputType($tableName, $columnName);
        $hidden = isHidden($columnName);           // Check if column is hidden
        $required = $hidden ? "" : $required;
        $inputType = $hidden ? "hidden" : $inputType;

        // class for tailwind 
        $class = ' mb-1 block border border-gray-300 text-gray-900 text-sm rounded-lg w-full h-8';


        $spell = "spellcheck=true";
        echo "<input class='$class' type=$inputType name=$columnName 
                    id=$columnName value='$value' $spell $required >";
    }

    /**
     *  Set the input type of a Field.
     *  Combines getFieldType and getDataTypeName, defined below.
     * 
     *  @param string $tableName - The name of the table.
     *  @param string $columnName - The name of the field.
     *  @return string - The input type of the field.
     **/
    protected static function setInputType($tableName, $columnName)
    {
        $form = new Form();
        $fieldType = $form->getFieldType($tableName, $columnName);
        $inputType = $form->getDataTypeName($fieldType);
        return $inputType;
    }
    /** Get Data Type Functions **/

    /**
     *  Get the Type of a Field from DB. Eg. varchar(100), int(5), enum("",""), etc
     * 
     * @param string $tableName - The name of the table.
     * @param string $columnName - The name of the field.
     * @return string - The type of the field.
     **/
    protected function getFieldType($tableName, $columnName)
    {
        global $conn;
        $sql = "DESCRIBE $tableName $columnName";
        $result = $conn->query($sql) or die("Query failed");
        $row = $result->fetch_assoc();
        return $row['Type'];
    }

    /** 
     * Get the name of the data type from an array in alias.php to be used in input tag type. E.g varchar(100) becomes 'text', int(5) becomes 'number' 
     *
     * @param $fieldType - data type of the field 
     * @return - name of the data type
     **/
    protected function getDataTypeName($fieldType)
    {
        global $dataTypes;
        foreach ($dataTypes as $key => $value) {    // $key is a string while $value is an array here    
            foreach ($value as $v) {
                // Check if any of the elements $v of array $value match from field type
                if (stripos($fieldType, $v) !== false)
                    return $key;
            }
        }
        return 'text';
    }

    /***/
    /**
     * 
     *  Create a text area for description
     * 
     *  @param string $columnName - The name of the column from the columnNames array.
     *  @param string $selectedValue - The selected value.
     * 
     * */
    protected function createTextArea($columnName, $value)
    {
        if (!isTextArea($columnName)) {
            echo "TextArea not applicable here.";
            return;
        }
        global $showAliases;

        $spell = "spellcheck=true";
        global $required;
                $class = ' mb-2 block border border-gray-300 text-gray-900 text-sm rounded-lg w-full h-20';

        echo "<textarea class='$class' name=$columnName id=$columnName '$spell' cols=22 rows=5 $required>$value</textarea>";
    }
    
    /***/
    /**
     * 
     *  Create a upload for file
     * 
     *  @param string $columnName - The name of the column from the columnNames array.
     *  @param string $selectedValue - The selected value.
     * 
     * */
    protected function createUpload($columnName, $value)
    {
        if (!isUploadFile($columnName)) {
            echo "Upload button invalid";
            return;
        }
        $accept = uploadFileType($columnName);
        global $inputAliases;

        global $required;
        $class = ' mb-2 block border border-gray-300 text-gray-900 text-sm rounded-lg w-full ';
        
        echo "<input type=file class='$class' name=file id=$columnName accept=$accept >$value</textarea>";
    }

    
    /**
     *  Create a selection for enum
     * 
     *  @param string $tableName
     *  @param string $columnName
     *  @param string $selectedValue
     */
    protected function createSelection($tableName, $columnName, $selectedValue)
    {
        $form = new Form();
        if ($form->setInputType($tableName, $columnName) !== "enum") {
            echo "Selection not applicable here.";
            return;
        }
        $required = isRequired($tableName, $columnName);
        global $inputAliases;

        $class = 'border border-gray-300 text-gray-900 text-sm rounded-lg w-full h-8';
        echo "<select class='$class' name=$columnName id=$columnName $required>";      // Selection tag

        echo "<option disabled selected>Select</option>";            // Disabled option
        $enum = $form->getEnumValues($tableName, $columnName);                  // Get enum values
        foreach ($enum as $value) {
            $selected = isSelected($value, $selectedValue);
            echo "<option value=$value $selected>$value</option>";
        }
        echo "</select>";
    }

    /****** Get Enum Values Functions ******/
    /**
     *  Get the predefined values of enum.
     * 
     *   @param string $tableName - The name of the table.
     *   @param string $columnName - The name of the field.
     *   @return array - An array containing the predefined values of the enum.
     *  E.g. getEnumValues("table_name","column_name")
     */
    protected function getEnumValues($tableName, $columnName)
    {
        global $conn;
        $sql = "DESC $tableName $columnName";        //  Query
        $result = $conn->query($sql);
        $row = $result->fetch_array();
        $enum = $row['Type'];       // retrieves the value of the 'Type' column from the current row. E.g "enum('Yes','No','Maybe')"
        $enum = substr($enum, 6, -2);        // extracts a substring from the 'Type' value. The substring starts at position 6 and ends 2 characters before the end of the string 
        //E.g "enum('Yes','No','Maybe')" becomes "Yes','No','Maybe"                         
        $enum = explode("','", $enum);       // split the string into an array based onthe delimiter (in this case, ',')
        // E.g "Yes','No','Maybe" becomes array("Yes","No","Maybe")
        return $enum;
    }
    /***/

    /**
     *  Fetching all the values of the relevant column from tables whose primary key has 
     *  been used as foriegn key in the current table
     * 
     *  @return array $categoryValues - stores values of the relevant column such as category_name 
     *  indexed with the primary key values such as category_id
     * 
     *  */
    public function getCategoryValues($columnName)
    {
        global $conn, $foreignKey, $categoryColumnList;
        // 
        $tableName = array_search($columnName,$foreignKey);

            $sql = "SELECT $columnName, $categoryColumnList[$tableName] FROM $tableName";
            $result = $conn->query($sql) or die("could retrive foriegn key values");
            $categoryValues = [];
            while ($row = $result->fetch_row()) {
                $categoryValues[$row[0]] = $row[1];
            }
            return $categoryValues;
        
    }




    /**
     *  Create selection for category list using foriegn key
     * 
     *  @param string $columnName 
     *  @param string $selectedValue
     * 
     **/
    function createCategorySelection($columnName, $selectedValue)
    {
        $form = new Form();
        global $conn, $categoryList, $foreignKey, $inputAliases;
        $value = $form->getCategoryValues($columnName);
        $class = ' mb-1 border border-gray-800 text-gray-900 text-sm rounded-lg w-full h-8';
        echo "<select class='$class' name=$columnName id=$columnName required>"; // Selection tag
        echo "<option disabled selected>Selected</option>";    // Disabled option
        foreach ($value as $id => $name) {
            $selected = isSelected($id, $selectedValue);
            echo "<option value= $id $selected>$name</option>";
        }


        echo "</select>";
    }




    // }

    /**
     *  Create lables for input fields
     * 
     *  @param string $columnName - The name of the column from the columnNames array.
     *  @param string $columnRename - The name of the column from the columnRenames array.
     * 
     * 
     * */
    public function createLabel($columnName, $columnRename)
    {
        if (isHidden($columnName))   // Check if column is hidden


            return;
        $lclass = 'mt-3 block text-sm font-medium text-gray-700';

        echo "<label class='$lclass' for=$columnName>$columnRename</label>";
    }

    public function getInputValues($tableName, $columnName, $where)
    {
        global $conn;
        $sql = "SELECT $columnName FROM $tableName WHERE $where";
        $result = $conn->query($sql);
        $value = $result->fetch_row();
        return $value[0];
    }
}
/**
 *  Check if a column is required based on NOT NULL
 * 
 *  @param string $tableName - The name of the table.
 *  @param string $columnName - The name of the column from the columnNames array.
 * 
 * */
function isRequired($tableName, $columnName)
{
    global $conn;
    $sql = "DESC $tableName $columnName";
    $result = $conn->query($sql);
    $null = $result->fetch_assoc();
    if ($null['Null'] == "NO")
        return "required";
    else
        return "";
}


function isSelected($value, $selectedValue)
{
    if ($value == $selectedValue)
        $selected = "selected";
    else $selected = "";
    return $selected;
}

function isHidden($columnName)
{
    global $toHide, $showAliases, $foreignKey;
    if (in_array($columnName, $foreignKey))
        return ;
    if (in_array($columnName, $toHide) !== false)
        return "hidden";
    
    return ;
}

function isTextArea($columnName)
{
    global $forTextArea;
    foreach ($forTextArea as $value) {
        if (stripos($columnName, $value) !== false)
            return "text";
    }
}

function isUploadFile($columnName)
{
    global $forUploadFiles;
    foreach ($forUploadFiles as $value) {
        if (stripos($columnName, $value) !== false)
            return "file";
    }
}
function uploadFileType($columnName)
{   
    global $forUploadFiles;
    $type = "";
    foreach ($forUploadFiles as $value) {
        if (stripos($columnName, $value) !== false){
            $type = $value;
            break;   
        }
    }
    switch($type){
        case 'FILE':
        case 'DOC':
            return "";
        case 'PIC':
        case 'PHOTO':
        case 'IMAGE':
            return "\"image/jpg, image/png\"";
        case 'PDF':
            return "application/pdf";

    }
}




// /**
 

// //UNAPPROVED

// function createCheckbox($tableName, $columnName, $selectedValue){
//     $fieldType = getFieldType($tableName, $columnName) ;
//     $inputType = setinputType($tableName, $columnName) ;
//     global $conn ;
//     echo "<input type='checkbox' name='$columnName' id='$columnName' class='form-control' value='$selectedValue'>";
//     return $inputType ; 
// }

// function createRadio($tableName, $columnName, $selectedValue){
//     $fieldType = getFieldType($tableName, $columnName) ;
//     $inputType = setinputType($tableName, $columnName) ;
//     global $conn ;
//     echo "<input type='radio' name='$columnName' id='$columnName' class='form-control' value='$selectedValue'>";
//     return $inputType ; 
// }




// function isSelected($value,$selectedValue){
//     if($value==$selectedValue)
//         return "selected" ;
//     else
//         return "" ;
// }

// function isDisabled($value,$selectedValue){
//     if($value==$selectedValue)
//         return "disabled" ;
//     else
//         return "" ;
// }

// function isReadOnly($value,$selectedValue){
//     if($value==$selectedValue)
//         return "readonly" ;
//     else
//         return "" ;
// }



// function isMultiple($value,$selectedValue){
//     if($value==$selectedValue)
//         return "multiple" ;
//     else
//         return "" ;
// }



// function isChecked($value,$selectedValue){
//     if($value==$selectedValue)
//         return "checked" ;
//     else
//         return "" ;
// }

// function isAutoFocus($value,$selectedValue){
//     if($value==$selectedValue)
//         return "autofocus" ;
//     else
//         return "" ;
// }

// function isAutoComplete($value,$selectedValue){
//     if($value==$selectedValue)
//         return "autocomplete" ;
//     else
//         return "" ;
// }

// function isMin($value,$selectedValue){
//     if($value==$selectedValue)
//         return "min" ;
//     else
//         return "" ;
// }

// function isMax($value,$selectedValue){
//     if($value==$selectedValue)
//         return "max" ;
//     else
//         return "" ;
// }

// function isStep($value,$selectedValue){
//     if($value==$selectedValue)
//         return "step" ;
//     else
//         return "" ;
// }

// function isPattern($value,$selectedValue){
//     if($value==$selectedValue)
//         return "pattern" ;
//     else
//         return "" ;
// }

// function isPlaceholder($value,$selectedValue){
//     if($value==$selectedValue)
//         return "placeholder" ;
//     else
//         return "" ;
// }

// function isList($value,$selectedValue){
//     if($value==$selectedValue)
//         return "list" ;
//     else
//         return "" ;
// }

// function isSize($value,$selectedValue){
//     if($value==$selectedValue)
//         return "size" ;
//     else
//         return "" ;
// }

// // BEH
// function isForm($value,$selectedValue){
//     if($value==$selectedValue)
//         return "form" ;
//     else
//         return "" ;
// }

// function isFormAction($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formaction" ;
//     else
//         return "" ;
// }

// function isFormEncType($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formenctype" ;
//     else
//         return "" ;
// }

// function isFormMethod($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formmethod" ;
//     else
//         return "" ;
// }

// function isFormNoValidate($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formnovalidate" ;
//     else
//         return "" ;
// }

// function isFormTarget($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formtarget" ;
//     else
//         return "" ;
// }

// function isFormAcceptCharset($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formacceptcharset" ;
//     else
//         return "" ;
// }

// function isFormName($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formname" ;
//     else
//         return "" ;
// }

// function isFormType($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formtype" ;
//     else
//         return "" ;
// }

// function isFormActionURL($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formactionurl" ;
//     else
//         return "" ;
// }

// function isFormActionMethod($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formactionmethod" ;
//     else
//         return "" ;
// }

// function isFormActionURLMethod($value,$selectedValue){
//     if($value==$selectedValue)
//         return "formactionurlmethod" ;
//     else
//         return "" ;
// }

// // Close PHP tag
// */
