<?php
    $DBServer = 'localhost';
    $DBUser   = 'root';
    $DBPass   = '';
    $get = $_GET;

    // Functions Routing
    if(isset($get['action']))
    {
        switch($get['action'])
        {
            case 'drop_db':
                dropDB($get['db']);
                break;
            case 'new_db':
                newDB($get['db']);
                break;
            case 'new_table':
                newTable($_GET['db'], $_GET['table'], $_GET['sql']);
                break;
            case 'remove_rows':
                removeRows($get['db'],$get['table'], $get['field'], $get['value']);
                break;
            case 'drop_tables':
                drop_tables($_GET['db'], $_GET['table']);
                break;
            case 'add_row':
                add_row($_GET['sql'],$_GET['db']);
                break;
        }
    }

    function newTable($db, $table, $sql){
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass'], $db);
        if ($conn->connect_error)
          $rs = 'Database connection failed: ' . $conn->connect_error . E_USER_ERROR;
        $rs=$conn->query($sql);
        if($rs === false) {
            echo 'Wrong SQL: ' . $sql . ' Error: ' . $conn->error . E_USER_ERROR;
        }else {
            $rs = "Table created successfully!";
        }
        mysqli_close($conn);
        echo $rs;
    }

    function drop_tables($db,$table){
        $sql= 'DROP TABLE ' . $table . ';';
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass'], $db);
        if ($conn->connect_error)
            $rs = 'Database connection failed: ' . $conn->connect_error . E_USER_ERROR;
        $rs=$conn->query($sql);
        if($rs === false) {
          trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
        }else {
            $rs = "Tables deleted successfully!";
        }
        mysqli_close($conn);
        echo $rs;
    }

    function add_row($sql, $db){
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass'], $db);
        if ($conn->connect_error)
          $rs = 'Database connection failed: ' . $conn->connect_error . E_USER_ERROR;
        $rs=$conn->query($sql);
        if($rs === false) {
            echo 'Wrong SQL: ' . $sql . ' Error: ' . $conn->error . E_USER_ERROR;
        }else{
            $rs = "Row inserted successfully!";
        }
        mysqli_close($conn);
        echo $rs;
    }

    function removeRows($db, $table, $field, $value){
        $sql= 'delete from ' . $table . ' where ' . $field . '='. $value . ';';
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass'], $db);
        if ($conn->connect_error)
            $rs = 'Database connection failed: ' . $conn->connect_error . E_USER_ERROR;
        $rs=$conn->query($sql);
        if($rs === false){
          trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
        }else{
            $arr = "Rows removed successfully!";
        }
        mysqli_close($conn);
        echo $rs;
    }


    function dropDB($db){
        $sql= 'DROP DATABASE ' . $db . ';';
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass']);
        if ($conn->connect_error)
            $rs = 'Database connection failed: ' . $conn->connect_error . E_USER_ERROR;
        $rs=$conn->query($sql);
        if($rs === false)
            $rs = 'Wrong SQL: ' . $sql . ' Error: ' . $conn->error . E_USER_ERROR;
        else
            $rs = "Database delete successfully!";
        mysqli_close($conn);
        echo $rs;
    }

    if(isset($_GET['query'])){
        $sql= $_GET['query'];
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass']);
        if ($conn->connect_error)
            trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
        if (mysqli_multi_query($conn,$sql)){
            do{
                if ($result=mysqli_store_result($conn)) {
                    while ($row=mysqli_fetch_row($result)){
                        for($i = 0; $i < sizeof($row); $i++)
                            Echo($row[$i]);
                        echo "<br>";
                    }
                    mysqli_free_result($result);
                }
            }while(mysqli_next_result($conn));
        }
        mysqli_close($conn);
    }

    function newDB($db){
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass']);
        $sql='CREATE DATABASE ' . $db . ';';
        if ($conn->connect_error)
            $rs = 'Database connection failed: '  . $conn->connect_error . E_USER_ERROR;
        $rs=$conn->query($sql);
        if($rs === false)
           $rs  = 'Wrong SQL: ' . $sql . ' Error: ' . $conn->error . E_USER_ERROR;
        else
            $rs = "Databse created successfully!";
        mysqli_close($conn);
        echo $rs;
    }

    function getPrimaryKey($DBName,$tableName)
    {
        $sql='describe ' . $tableName;
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass']);
        if ($conn->connect_error)
          trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
        $rs=$conn->query($sql);
        if($rs === false){
          trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
        }else{
            $primary_key = 'ee';
            while ($meta = $rs->fetch_field()){
                if ($meta->flags & MYSQLI_PRI_KEY_FLAG){
                    $primary_key = $meta->name;
                    echo $primary_key . ', ';
                }
            }
        }
        mysqli_close($conn);
        return $primary_key;
    }

    function getDB()
    {
        $sql='SHOW DATABASES;';
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass']);
        if ($conn->connect_error)
          trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
        $rs=$conn->query($sql);
        if($rs === false){
          trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
        }else{
            $arr = $rs->fetch_all(MYSQLI_ASSOC);
        }
        mysqli_close($conn);
        return $arr;
    }

    function structure($db)
    {
        $sql='SHOW TABLES;';
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass'], $db);
        if ($conn->connect_error)
          trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
        $rs=$conn->query($sql);
        if($rs === false){
          trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
        }else{
            //$arr = $rs->fetch_all(MYSQLI_ASSOC);
        }
        mysqli_close($conn);
        return $rs; // $arr o $rs idk
    }

    function getColumnsNames($db,$table)
    {
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass'], $db);
        $sql = "SHOW COLUMNS FROM " . $table;
        $rs = $conn->query($sql);
        if (!$rs){
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        mysqli_close($conn);
        return $rs;
    }

    function select($db,$table)
    {
        $sql='SELECT * FROM ' . $table . ';';
        $conn = new mysqli($GLOBALS['DBServer'], $GLOBALS['DBUser'], $GLOBALS['DBPass'], $db);
        if ($conn->connect_error)
          trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
        $rs=$conn->query($sql);
        if($rs === false){
          trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
        }else{
            $arr = $rs->fetch_all(MYSQLI_ASSOC);
        }
        mysqli_close($conn);
        return $rs; // return $arr o $rs? IDK
    }
?>
