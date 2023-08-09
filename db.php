<?php

    $dsn = "mysql:host=localhost;dbname=nozha";
    $user = "root";
    $pass = "";
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

    try{
        $con = new PDO($dsn, $user, $pass, $option);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "CONNECTED";
    }
    catch(PDOException $e){
        echo "FAILED TO CONNECT";
        echo $e->getMessage();
    }


//CHECK IF USER EXISTED IN DATABASE
// $stmt = $con->prepare('SELECT * FROM places WHERE id = ? LIMIT 1');
// $stmt->execute(array($id));
// $row = $stmt->fetch();