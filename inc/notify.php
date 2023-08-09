<?php
    session_start();
    include "../db.php";
    include "functions.php";
    account();
    


    $stmt = $con->prepare("SELECT * FROM notifications ORDER BY id DESC");
    $stmt->execute();
    $row = $stmt->fetchAll();
    $count = 0;
    foreach($row as $r){
        $receivers = explode(",", $r["receiver"]);
        if(in_array($my_id, $receivers)){
            $count++;
        }

    }
    $old_count = $account["notnum"];
    if($count > $old_count){
        $new_count = $count - $old_count;
        echo '<span class="new">'.$new_count.'</span>';
    }

