<?php

/**
 * Search for records in a database table based on a specified column and search text.
 *
 * This function performs a SQL query to search for records in a specified database table
 * based on a given column and search text. It returns an array of matching records or 0 if
 * no records are found.
 *
 * @param string $table The name of the database table to search.
 * @param string $col_id The name of the column containing record IDs.
 * @param string $col_name The name of the column to search for matches.
 * @param string $search_txt The search text to match against the specified column.
 *
 * @global PDO $con The database connection (assumed to be global).
 *
 * @return array|int Returns an array of matching records or 0 if no records are found.
 *
 * @example
 * $results = search_pages("products", "product_id", "product_name", "widget");
 * // This will search for products containing "widget" in their name column.
 * // $results will either be an array of matching records or 0 if no matches were found.
 */
function search_pages($table, $col_id, $col_name, $search_txt)
{
    // Access the global database connection.
    global $con;

    // Define the SQL query to search for records.
    $sql = "SELECT $col_id, $col_name FROM $table WHERE $col_name LIKE ? LIMIT 10";

    // Prepare and execute the SQL statement with wildcard search.
    $stmt = $con->prepare($sql);
    $stmt->execute(["%" . $search_txt . "%"]);

    // Check if matching records were found.
    if ($stmt->rowCount() > 0) {
        // Return an array of matching records.
        return $stmt->fetchAll();
    } else {
        // Return 0 if no records were found.
        return 0;
    }
}


/**
 * Retrieve data from the database using a prepared SQL statement.
 *
 * This function prepares and executes a SQL statement to retrieve data from the
 * database. It can be used to fetch multiple rows of data based on the provided
 * SQL query and optional parameters.
 *
 * @param string $sql The SQL query to execute for data retrieval.
 * @param array|null $param An optional array of parameters to bind to the SQL statement.
 *
 * @global PDO $con The database connection (assumed to be global).
 *
 * @return array|int Returns an array of fetched data (associative arrays) if records are found,
 *                   or 0 if no records are found.
 *
 * @example
 * $sql = "SELECT * FROM users WHERE status = ?";
 * $param = ["active"];
 * $results = get_all_data($sql, $param);
 * // This will fetch all active users and return an array of user data,
 * // or 0 if no active users are found.
 */
function get_all_data($sql, $param = null)
{
    // Access the global database connection.
    global $con;

    // Prepare and execute the SQL statement with optional parameters.
    $stmt = $con->prepare($sql);
    $stmt->execute($param);

    // Check if matching records were found.
    if ($stmt->rowCount() > 0) {
        // Return an array of fetched data as associative arrays.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Return 0 if no records were found.
        return 0;
    }
}


/**
 * Retrieve a single row of data from the database using a prepared SQL statement.
 *
 * This function prepares and executes a SQL statement to retrieve a single row of data
 * from the database. It can be used to fetch a single record based on the provided SQL
 * query and optional parameters.
 *
 * @param string $sql The SQL query to execute for data retrieval.
 * @param array|null $param An optional array of parameters to bind to the SQL statement.
 *
 * @global PDO $con The database connection (assumed to be global).
 *
 * @return array|int Returns an associative array of fetched data if a record is found,
 *                   or 0 if no records are found.
 *
 * @example
 * $sql = "SELECT * FROM users WHERE user_id = ?";
 * $param = [123];
 * $result = get_data($sql, $param);
 * // This will fetch a user with user_id 123 and return an associative array of user data,
 * // or 0 if no user with that ID is found.
 */
function get_data($sql, $param = null)
{
    // Access the global database connection.
    global $con;

    // Prepare and execute the SQL statement with optional parameters.
    $stmt = $con->prepare($sql);
    $stmt->execute($param);

    // Check if a matching record was found.
    if ($stmt->rowCount() > 0) {
        // Return an associative array of fetched data.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Return 0 if no records were found.
        return 0;
    }
}



/**
 * Insert data into the database using a prepared SQL statement.
 *
 * This function prepares and executes a SQL statement to insert data into the database.
 * It can be used to perform database insertions based on the provided SQL query and optional
 * parameters.
 *
 * @param string $sql The SQL query to execute for data insertion.
 * @param array|null $param An optional array of parameters to bind to the SQL statement.
 *
 * @global PDO $con The database connection (assumed to be global).
 *
 * @return bool Returns true if the insertion was successful and at least one row was affected,
 *              or false if the insertion failed or no rows were affected.
 *
 * @example
 * $sql = "INSERT INTO users (username, email) VALUES (?, ?)";
 * $param = ["john_doe", "john@example.com"];
 * $isInserted = insert_data($sql, $param);
 * // This will attempt to insert a new user into the 'users' table and return true if the
 * // insertion was successful, or false if it failed.
 */
function insert_data($sql, $param = null)
{
    // Access the global database connection.
    global $con;

    // Trim any leading and trailing spaces from the parameters.
    if ($param !== null) {
        $param = array_map('trim', $param);
    }

    // Prepare and execute the SQL statement with optional parameters.
    $stmt = $con->prepare($sql);
    $stmt->execute($param);

    // Check if at least one row was affected (insertion successful).
    return $stmt->rowCount() > 0;
}

/**
 * Delete a database record based on its ID using a prepared SQL statement.
 *
 * This function prepares and executes a SQL statement to delete a database record based
 * on its ID. It is designed for deleting a single record from a table.
 *
 * @param string $sql The SQL query to execute for record deletion, with a placeholder for the ID.
 * @param int $id The ID of the record to be deleted.
 *
 * @global PDO $con The database connection (assumed to be global).
 *
 * @return void
 *
 * @example
 * $sql = "DELETE FROM products WHERE product_id = ?";
 * $id = 123;
 * delete_item($sql, $id);
 * // This will attempt to delete a product with product_id 123 from the 'products' table.
 */
function delete_item($sql, $id)
{
    // Access the global database connection.
    global $con;
    
    // Prepare and execute the SQL statement with the provided ID.
    $con->prepare($sql)->execute([$id]);
}


function update_book($sql, $param = null) {
    // Access the global database connection.
    global $con;

    // Trim any leading and trailing spaces from the parameters.
    if ($param !== null) {
        $param = array_map('trim', $param);
    }

    // Prepare and execute the SQL statement with optional parameters.
    $stmt = $con->prepare($sql);
    $stmt->execute($param);

    // Check if at least one row was affected (insertion successful).
    return $stmt->rowCount() > 0;


}

