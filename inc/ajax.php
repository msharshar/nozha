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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <title>Response</title>
    </head>

    <body>
        <?php 
            $data = $_GET["post"];
            $data = explode("-", $data);
            $post_id = $data["0"];
            $post_user = $data["1"];

            $my_id = $_SESSION["id"];
            $my_name = $account["firstname"].' '.$account["lastname"];
            $stmt = $con->prepare("SELECT * FROM likes WHERE post = $post_id");
            $stmt->execute();

            if($stmt->rowCount() == 0){
                $stmt = $con->prepare("INSERT INTO likes(liker, post) VALUES(:liker, :post)");
                $stmt->execute(array(
                    "liker" => $my_id,
                    "post"  => $post_id,
                ));
                echo '<i class="fas fa-heart red"></i> '.$lang["liked"];

                $stmt = $con->prepare("SELECT id FROM notifications WHERE sender=$my_id AND receiver=$post_user AND post=$post_id");
                $stmt->execute();
                $count = $stmt->rowCount();
                if($count == 0){
                    $stmt = $con->prepare("INSERT INTO notifications(sender, receiver, post, text) VALUES(:sender, :receiver, :post, :text)");
                    $stmt->execute(array(
                    "sender"    => $my_id,
                    "receiver"  => $post_user,
                    "post"      => $post_id,
                    "text"      => "$my_name liked your post",
                    ));
                }else{
                    $stmt = $con->prepare("SELECT id FROM notifications WHERE sender=$my_id AND receiver=$post_user AND post=$post_id");
                    $stmt->execute();
                    $not_id = $stmt->fetch();
                    $not = $not_id["id"];

                    $text = "$my_name liked your post";
                    $stmt = $con->prepare("UPDATE notifications SET text=? WHERE id=?");
                    $stmt->execute(array($text, $not));
                }
            }else{
                $stmt = $con->prepare("DELETE FROM likes WHERE liker=$my_id AND post=$post_id");
                $stmt->execute();
                echo '<i class="far fa-heart"></i> '.$lang["like"];

                $stmt = $con->prepare("SELECT id FROM notifications WHERE sender=$my_id AND receiver=$post_user AND post=$post_id");
                $stmt->execute();
                $not_id = $stmt->fetch();
                $not = $not_id["id"];

                $text = "";
                $stmt = $con->prepare("UPDATE notifications SET text=? WHERE id=?");
                $stmt->execute(array($text, $not));
            }
        ?>
    </body>
    
</html>
<?php
/*
            $lastpost = $_GET["lpost"];
            echo $lastpost;
            $my_id = $_GET["id"];

            $stmt = $con->prepare("SELECT * FROM posts WHERE id > 3 ORDER BY date DESC");
            $stmt->execute();
            $posts = $stmt->fetchAll();

            foreach($posts as $post){
                if(in_array($post["user"], $friends)){
                    echo "<div class='post'>";
                        $stmt = $con->prepare("SELECT * FROM accounts WHERE id = ?");
                        $stmt->execute(array($post["user"]));
                        $user = $stmt->fetch();
                        echo '<img class="avatar" src="uploads/avatar/'.$user["avatar"].'">';
                        echo '<h3>'.$user["firstname"].' '.$user["lastname"].'</h3>';
                        //echo '<span>'.$post["date"].'</span>';
                        echo '<p>'.$post["content"].'</p>';
                        echo '<img src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
                        echo '
                                <div class="actions">
                                <a href="#" class="btn btn-light"><i class="far fa-heart"></i> '.$lang["like"].'</a>
                                <a href="#" class="btn btn-light"><i class="far fa-comment-alt"></i> '.$lang["comment"].'</a>
                                </div>
                        ';
                    echo "</div>"; 
                    $lastpost = $post["id"];
                }
            }
            echo $lastpost;
            */