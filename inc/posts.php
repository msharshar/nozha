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


    $postoffset = $_GET["postoffset"];
    $all_posts = array();

    $stmt = $con->prepare("SELECT * FROM posts ORDER BY date DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();

    foreach($posts as $post){
        if(in_array($post["user"], $friends)){
            $post_id = $post["id"];
            $all_posts[] = $post_id;
        }
    }
    $newarr = array_slice($all_posts, $postoffset, 2);
    if(empty($newarr)){
        echo "No more posts here";
        echo "<br>";
    }else{
        foreach($newarr as $postid){
            $stmt = $con->prepare("SELECT * FROM posts WHERE id=?");
            $stmt->execute(array($postid));
            $postdata = $stmt->fetch();
            
            $post_id = $postdata["id"];
            $post_user = $postdata["user"];
            echo "<div class='post'>";
                $stmt = $con->prepare("SELECT * FROM accounts WHERE id = ?");
                $stmt->execute(array($postdata["user"]));
                $user = $stmt->fetch();
                echo '<img class="avatar" src="uploads/avatar/'.$user["avatar"].'">';
                echo '<h3>'.$user["firstname"].' '.$user["lastname"].'</h3>';
                echo '<p>'.$postdata["content"].'</p>';
                echo '<img src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
                echo '<div class="actions" data-id="'.$post_id.'-'.$post_user.'">';
                    $stmt = $con->prepare("SELECT * FROM likes WHERE post = $postid");
                    $stmt->execute();
                    if($stmt->rowCount() == 0){
                        echo '<a onclick="like('.$post_id.')" id="like-'.$post_id.'" class="btn btn-light like-btn"><div id="displaylike'.$post_id.'-'.$post_user.'"><i class="far fa-heart"></i> '.$lang["like"].'</div></a>';
                    }else{
                        echo '<a onclick="like('.$post_id.')" id="like-'.$post_id.'" class="btn btn-light like-btn"><div id="displaylike'.$post_id.'-'.$post_user.'"><i class="fas fa-heart red"></i> '.$lang["liked"].'</div></a>';
                    }    
                    echo'<a class="btn btn-light comment-btn"><i class="far fa-comment-alt"></i> '.$lang["comment"].'</a>';
                    echo'<div class="comments">';
                        echo'<input type="text" class="input-group" placeholder="'.$lang["leavecomment"].'"/>';
                        echo'<input type="submit" class="btn btn-primary" value="'.$lang["write"].'">';
                    echo'</div>';
                echo'</div>';
            echo "</div>";
        }
    }

    /*
        
    */
?>

