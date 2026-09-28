<?php
    /** We already set this option in login.php 
     *          
     *      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
     *  
     *  That means whenever you call:
     *  
     *     $row = $result->fetch();
     * 
     *  PDO automatically behaves as if you wrote:
     * 
     *      $row = $result->fetch(PDO::FETCH_ASSOC);
     * 
     *  So these two versions are quivalent:
     * 
     *          version 1                                               version 2
     *                  while ($row = $result->fetch())                          while ($row = $result->fetch(PDO::FETCH_ASSOC))
     *                  {       // ...          }                                {           // ...          }
     * 
     * PDO's default mode ( PDO::FETCH_BOTH ) , a row might look like:
     *      Array
     *          (
     *              [0] => Mark Twain
     *              [author] => Mark Twain
     *              [1] => Tom Sawyer
     *              [title] => Tom Sawyer
     *          )
     * 
     */


    require_once 'login.php';

    try
    {
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch (PDOException $e)
    {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    $query = "SELECT * FROM classics";
    $result = $pdo->query($query);

    while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            echo 'Author:   '.htmlspecialchars($row['author'])  ."<br>";
            echo 'Title:   '.htmlspecialchars($row['title'])   ."<br>";
            echo 'Category: '.htmlspecialchars($row['category'])."<br>";
            echo 'Year:     '.htmlspecialchars($row['year'])    ."<br>";
            echo 'ISBN:     '.htmlspecialchars($row['isbn'])    ."<br><br>";
        }
?>