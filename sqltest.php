<?php

    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
    require_once 'login.php';

    try
    {
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch (PDOException $e)
    {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    /**  Checking for a Delete Request
     *   $_POST is an associative array containing data submitted from a form with: <form method="post">
     *      For example, if a form submits: <input name="isbn" value="12345">
     *                   then $_POST['isbn'] contains 12345
     *   
     *   isset() Checks whether a variable exists and is not null
     *      Example: isset($_POST['isbn'])  returns:    true if the form sent isbn
     *                                                  false otherwise
     *   
     *   isset($_POST['delete']) && isset($_POST['isbn']) means: Execute this block only if BOTH values were submitted.
     * 
     *  */ 
    if (isset($_POST['delete']) && isset($_POST['isbn']))
        {
            $isbn   = sanitize_post_value($pdo, 'isbn');
            $query  = "DELETE FROM classics WHERE isbn = $isbn";
            $result = $pdo->query($query);
        }

    /**  Checking for New Record Submission
     *   This checks whether the Add Record form was submitted.
     *   All fields must exist.
     */
    if (isset($_POST['author'])     &&
        isset($_POST['title'])      &&
        isset($_POST['category'])   &&
        isset($_POST['year'])       &&
        isset($_POST['isbn'])
    )
        {
            $author     = sanitize_post_value($pdo, 'author');
            $title      = sanitize_post_value($pdo, 'title');
            $category   = sanitize_post_value($pdo, 'category');
            $year       = sanitize_post_value($pdo, 'year');
            $isbn       = sanitize_post_value($pdo, 'isbn');

            $query      = "INSERT INTO classics VALUES" . "($author, $title, $category, $year, $isbn)";
            $result     = $pdo->query($query);
        }
    /**. Heredoc Systax
     *      Instead of: echo "<form>";         you can write:  echo <<<_END
     *                   echo "<input>";                            <form>
     *                   echo "</form>";                                .....
     *                                                              </form>
     *                                                          _END;
     * 
     *   <form action="sqltest.php" method="post"> Means:   Send data to sqltest.php
     *                                                      Use POST method
     * 
     *   <input type="submit" value="ADD RECORD"> When clicked:     Form data is sent
     *                                                              PHP receives it in $_POST
     * 
     * 
     *  Hidden Inputs
     *      <input type='hidden' name='delete' value='yes'> A hidden field is invisible. 
     *                                                      The user never sees it. But it is submitted.
     *              Result: $_POST['delete'] contains: yes
     *      <input type='hidden' name='isbn' value='$r4'> Suppose: $r4 = 12345; 
     *                                                    Then the generated HTML becomes: <input type='hidden' name='isbn' value='12345'>
     *                                                    When Delete is clicked, PHP knows which record to remove.
     *  
     *  Delete Button
     *      <input type='submit' value='DELETE RECORD'>     When pressed:   Hidden fields are submitted.
     *                                                                      delete=yes is sent.
     *                                                                      isbn=12345 is sent.
     *                                                                      The first if block runs.    
     *                                                                      The row is deleted.
     */
    echo <<<_END
        <form action = "sqltest.php" method="post">
        <pre>
            Author      <input type = "text" name = "author">
            Title       <input type = "text" name = "title">
            Category    <input type = "text" name = "category">
            Year        <input type = "text" name = "year">
            ISBN        <input type = "text" name = "isbn">
                        <input type = "submit" value = "ADD RECORD">
        </pre>
        </form>
    _END;

    $query  = "SELECT * FROM classics";

    /** $result represents the result set containing the rows returned by the query, but $result itself is not an ordinary PHP array containing all the data
     *  it's a PDOStatement object
     */
    $result = $pdo->query($query);

    while ($row = $result->fetch())
        {
            $r0 = htmlspecialchars($row['author']);
            $r1 = htmlspecialchars($row['title']);
            $r2 = htmlspecialchars($row['category']);
            $r3 = htmlspecialchars($row['year']);
            $r4 = htmlspecialchars($row['isbn']);

            echo <<<_END
                <pre>
                    Author: $r0
                     Title: $r1
                  Category: $r2
                      Year: $r3
                      ISBN: $r4
                </pre>
                <form action = 'sqltest.php' method = 'post'>
                    <input type = 'hidden' name = 'delete' value = 'yes'>
                    <input type = 'hidden' name = 'isbn' value = '$r4'>
                    <input type = 'submit' value = 'DELETE RECORD'>
                </form>
            _END;
        }
    
    /**   Suppose the user entered: 12345   Then: $pdo->quote('12345')   returns: '12345'
     *    Notice the quotes were added. Why? To help prevent SQL injection.
     */
    function sanitize_post_value($pdo, $var)
    {
        return $pdo->quote($_POST[$var]);
    }

?>

<?php
    /**.  Overall Flow
     * 
     *                                                                                                              Page Loads
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               Show Add Record Form
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               User submits form
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               INSERT INTO classics
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               Refresh page
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               Display all records
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               User clicks DELETE RECORD
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               DELETE FROM classics WHERE isbn = ...
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               Refresh page
     *                                                                                                                   |
     *                                                                                                                   v
     *                                                                                                               Record disappears
     * 
     * 
     * 
     */


?>