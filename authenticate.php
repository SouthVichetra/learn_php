<?php 
    require_once 'login.php';

    try{
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch (\PDOException $e){
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username=:un');
        $stmt->execute([':un' => $_SERVER['PHP_AUTH_USER']]);

        if (!$stmt->rowCount()) die("User not found");

        $row    =   $stmt->fetch();
        $fn     =   $row['forename'];
        $sn     =   $row['surname'];
        $us     =   $row['username'];
        $pw     =   $row['password'];

        if (password_verify($_SERVER['PHP_AUTH_PW'], $pw)){
            echo htmlspecialchars("$fn $sn : Hi $fn, you are now logged in as '$us'");
        } else die("Invalid username/password combination");
    } else {
        // instructs the browser to open a native popup dialog asking for a Username and Password.
        header('WWW-Authenticate: Basic realm="Restricted Are"');
        
        // The HTTP 401 Unauthorized status code tells the browser that access is denied until valid credentials are provided.
        header('HTTP/1.1 401 Unauthorized');

        //If the user clicks Cancel, execution halts and the die() message displays.
        die ("Please enter your username and password");
    }


?>