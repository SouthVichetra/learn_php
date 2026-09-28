<?php
    /**. Imports another PHP file
     *   require -> inculde the file, fatal error if missing
     *   require_once -> include it only one time
     *  */ 
    require_once 'login.php';

    try
    {
        /** This creates a PDO object
         *  PHP Script -> PDO Connection -> MySQL Database
         */
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    /** This runs only if an error occurs.  $e is an exception object.
     *  for example: $user = 'wronguser'; 
     *           or  $pass = 'wrongpassword';
     *      PDO throws a PDOException: catch (PDOException $e) where $e->getMessage() might contain: Access denied for user and $e->getCode() might contain an error number
     * 
     */
    catch (PDOException $e)
    {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    // This is simply a string, contain query command
    $query = "SELECT * FROM classics";

    /** PDO sends the SQL to MySQL
     *    PHP   
     *     |
     *     |    SELECT * FROM classics
     *     v
     *    MySQL
     *     |
     *     |    Returns rows
     *     v
     *    $result
     * $result becomes a PDOStatement object containing the query result.
     */
    $result = $pdo->query($query);

    /** Read one row at a time
     * 
     *  $row = $result->fetch();
     * 
     *  returns: [
     *              'author' => 'Charles Dickens',
     *              'title' => 'The Old Curiosity Shop',
     *              'category' => 'Classic Fiction',    
     *              'year' => '1841',
     *              'isbn' => '9780099533474'
     *           ]
     *  no more rows $result->fetch() returns false
     */
    while ($row = $result->fetch())
        {
            /** Imagine someone inserts:  <script>alert('Hacked')</script> into the database.
             * 
             *    without: htmlspecialchars() the browser executes the script. 
             * 
             *    with: htmlspecialchars() it becomes: &lt;script&gt;alert('Hacked')&lt;/script&gt; 
             *          and is displayed as text instead of running. This helps prevent Cross-Site Scripting (XSS) attacks.
             */
            echo 'Anthor:   '.htmlspecialchars($row['author'])  ."<br>";
            echo 'Tittle:   '.htmlspecialchars($row['title'])   ."<br>";
            echo 'Category: '.htmlspecialchars($row['category'])."<br>";
            echo 'Year:     '.htmlspecialchars($row['year'])    ."<br>";
            echo 'ISBN:     '.htmlspecialchars($row['isbn'])    ."<br><br>";
        }
?>

<?php
    /**.  Overall flow
     * 
     *                                    require_once login.php
     *                                              |
     *                                              |
     *                                              V
     *                                    Create PDO connection
     *                                              |
     *                                              |
     *                                              V
     *                                   Run SELECT * FROM classics
     *                                              |
     *                                              |
     *                                              V 
     *                                        Get result set
     *                                              |
     *                                              |
     *                                              V
     *                                      fetch() first row
     *                                              |
     *                                              |
     *                                              V
     *                                         Display row
     *                                              |
     *                                              |
     *                                              V
     *                                        fetch() next row
     *                                              |
     *                                              |
     *                                              V
     *                                         Display row
     *                                              |
     *                                              |
     *                                              V
     *                                        No more rows
     *                                              |
     *                                              |
     *                                              V
     *                                          Loop ends
     */
?>