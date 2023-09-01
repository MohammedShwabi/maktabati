<?php

// for search in category and publisher and author pages
function search_pages($table, $col_id, $col_name, $search_txt)
{
    //to get category data form database
    global $con;
    $sql = "SELECT $col_id, $col_name FROM $table WHERE $col_name LIKE ? LIMIT 10";
    $stmt = $con->prepare($sql);
    $stmt->execute(["%" . $search_txt . "%"]);

    //check if there is more data
    if ($stmt->rowCount() > 0) {
        //store the result of select statement in $results var
        return $stmt->fetchAll();
    } else {
        return 0;
    }
}

// to get all data multiple row
function get_all_data($sql, $param = null)
{
    global $con;
    //to get data form database
    $stmt = $con->prepare($sql);
    $stmt->execute($param);

    //check if there is more data
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return 0;
    }
}

// to get the selected data by id only on row
function get_data($sql, $param = null)
{
    global $con;
    //to get data form database
    $stmt = $con->prepare($sql);
    $stmt->execute($param);

    //check if there is more data
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return 0;
    }
}
// to insert data in DB
function insert_data($sql, $param = null)
{
    global $con;
    //to trim spaces
    if($param != null){
        $param = array_map('trim',$param);
    }
    //to get data form database
    $stmt = $con->prepare($sql);
    $stmt->execute($param);

    //check if there is more data
    return $stmt->rowCount() > 0;
  
}

// to delete any items 
function delete_item($sql, $id)
{
    global $con;
    $con->prepare($sql)->execute([$id]);
}
