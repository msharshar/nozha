<?php
    session_start();
    include "../db.php";
    include "lang.php";
    include "functions.php";
    account();
    friends();
    
    if($account["lang"] == "AR"){
        $lang = $arabic;
    }
    else{
        $lang = $english;
    }

    if(isset($_GET["action"]) && $_GET["action"] == "add"){
        $post_id = $_GET["post_id"];
        $commenttext = $_GET["commenttext"];

        $stmt = $con->prepare("INSERT INTO comments(post, user, content) VALUES(:post, :user, :content)");
        $stmt->execute(array(
            "post"    => $post_id,
            "user"    => $my_id,
            "content" => $commenttext
        ));
    }

    if(isset($_GET["action"]) && $_GET["action"] == "load"){
        $post_id = $_GET["post_id"];
        $stmt = $con->prepare("SELECT * FROM comments WHERE post = $post_id");
        $stmt->execute();
        $commentdata = $stmt->fetchAll();
        if($stmt->rowCount() == 0){
            echo "No comments";
        }else{
            foreach($commentdata as $comment){
                echo $comment["content"];
            }
        }
        
    }

            
?>

