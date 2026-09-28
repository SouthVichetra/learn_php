<?php
    // The database server address 
    // localhost means MySQL is running on the same machine as your PHP application.
    $host = 'localhost';

    // The name of the database you want to use
    $db = 'publications';

    // MySQL username
    $user = 'tour';

    // Password for the MySQL user
    $pass = '7895';

    // Character set used for the connection
    // utf8mb4 is recommended because it supports: English, Khmer, Chinese, Japanese, Emojis
    // Without utf8mb4, some special characters may not be stored correctly
    $chrs = 'utf8mb4';

    /*
    This creates a DSN (Data Source Name) string.
    After variable substitution it becomes: mysql:host=localhost;dbname=publications;charset=utf8mb4
    PDO uses this string to know:
        Which database system? -> MySQL
        Which server?          -> localhost
        Which database?        -> publications
        Which charset?         -> utf8mb4
    Think of it as the database address
    */
    $attr = "mysql:host=$host;dbname=$db;charset=$chrs";

    // Starts an array of PDO options
    $opts = 
    [
        /* This tells PDO how to handle errors.
            Without it $stmt = $pdo->query("WRONG SQL"); might fail silently.
            With it PDOException: SQL syntax error is thrown immediately.
            This make debugging much easier.
        */
        PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,

        /* Controls how query results are returned.
            Example row:
            id                          name
            1                           Book

            Without FETCH_ASSOC :
            Array (
                [0] => 1
                [id] => 1
                [1] => Book
                [name] => Book
            )
            Notice the duplicated values.

            With FETCH_ASSOC :
            Array (
                [id] => 1
                [name] => Book
            )
        
        */
        PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,

        /* it tells PDO "Use MySQL's real prepared statements instead of pretending to prepare them in PHP."
        Benefits: Better security, Better type handling, More reliable against SQL injection
        
        */
        PDO::ATTR_EMULATE_PREPARES      => false,
    
    // Ends the options array
    ];
?>