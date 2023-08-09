<?php
    ob_start();
    include "init.php";
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-lg-3 left">
            <div class="position">
                <div class="profile-box">
                    <div class="row">
                        <div class="col-sm-12">
                            <img class="cover" src="uploads/cover/<?php echo $account["cover"] ?>"/>
                        </div>
                        <div class="col-sm-5">
                            <img class="avatar" src="uploads/avatar/<?php echo $account["avatar"] ?>"/>
                        </div>
                        <div class="name col-sm-7">
                            <h3><?php echo $account["firstname"].' '.$account["lastname"] ?></h3>
                            <span><?php echo $account["username"] ?></span>
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-3">
                            <l><?php echo $lang["posts"] ?></l>
                            <span><?php 
                                $stmt = $con->prepare("SELECT * FROM posts WHERE user=$my_id");
                                $stmt->execute();
                                echo $stmt->rowCount();
                            ?></span>
                        </div>
                        <div class="col-sm-3">
                            <l><?php echo $lang["friends"] ?></l>
                            <span><?php echo count($friends); ?></span>
                        </div>
                        <div class="col-sm-3">
                            
                        </div>
                    </div>
                </div>
                <div class="places-fu">
                    <h3><?php echo $lang["placesforyou"] ?></h3>
                    <?php
                    $province = $account["province"];
                    $stmt = $con->prepare("SELECT * FROM places WHERE province LIKE '%$province%' ORDER BY RAND() LIMIT 2000");
                    $stmt->execute(array($account["country"]));
                    $row = $stmt->fetchAll();

                    $places_num = 0;
                    foreach($row as $place){
                        if($places_num < 3){
                            echo '
                                <div class="avatar">
                                    <img src="https://i.ytimg.com/vi/6y-OAsRZbas/maxresdefault.jpg" />
                                </div>
                                <div class="avatar-side">
                                    <a href="place.php?id='.$place["id"].'">'.$place["name"].'</a>
                                    <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i> '.$lang["visit"].'</a>
                                </div>
                            ';
                        }
                        $places_num++;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-lg-6 center">
            <div class="wpost">
                <div class="nav">
                    <span><?php echo $lang["writeapost"] ?></span>
                </div>
                <div class="content">
                    <form method="POST" action="feed.php?action=addpost" enctype="multipart/form-data">
                        <textarea name="posttext" placeholder="<?php echo $lang["writepostquote"] ?>"></textarea>
                        <hr>
                        <input id="postphoto" type="file" name="cover" class="btn btn-secondary" />
                        <a class="addphoto btn">اضف صورة</a>
                        <button type="submit" class="btn btn-primary"><i class="far fa-sticky-note"></i> <?php echo $lang["post"] ?></button>
                    </form>
                    <?php
                        if(isset($_GET["action"]) && $_GET["action"] == "addpost"){
                            //EXTENSIONS
                            $ext = array("jpg", "jpeg", "png", "gif");

                            //FILES
                            $cover     = $_FILES["cover"];
                            $covername = $_FILES["cover"]["name"];
                            $covertmp  = $_FILES["cover"]["tmp_name"]; 
                            $coversize = $_FILES["cover"]["size"];

                            //CHECK EXTENSION
                            $coverext = explode('.', $covername);
                            $coverext = end($coverext);
                            $coverext = strtolower($coverext);

                            if(!in_array($coverext, $ext)){
                                $error[] = "<div class='alert alert-danger'>File extension is not allowed</div>";
                            }

                            //CHECK SIZE
                            if($coversize > 4*1024*1024){
                                $error[] = "<div class='alert alert-danger'>Max size allowed is 4MB</div>";
                            }

                            //UNIQUE NAME
                            $covername = rand(0, 1000000).'_'.$covername;

                            //UPLOAD PROCESS
                            $postuser = $my_id;
                            $posttext = $_POST["posttext"];
                            $stmt = $con->prepare("INSERT INTO posts(user, content, photo) VALUES(:postuser, :posttext, :cover)");
                            $stmt->execute(array(
                                'postuser' => $postuser,
                                'posttext' => $posttext,
                                'cover'    => $covername,
                            ));
                            move_uploaded_file($covertmp, "uploads\post\\".$covername);

                            header("Location: feed.php");
                        }
                    ?>
                </div>
            </div>

            <div id="posts" class="posts">
                <button id="getposts" class="getposts">
                    <span id="getpostsword"><?php echo $lang["getposts"] ?></span>
                    <div id="getpostsspinner" class="spinner"></div>
                </button>
            </div>
            
        </div>

        <div class="col-xs-12 col-lg-3 right">
            <div class="position">
                <div class="add-place">
                    <a href="addplace.php"><i class="fas fa-compass"></i> <?php echo $lang["goforwalk"] ?></a>
                </div>
                <div class="wt-follow">
                    <h3><?php echo $lang["whotofollow"] ?></h3>
                        <?php
                        $city = $account["city"];
                        $stmt = $con->prepare("SELECT * FROM accounts WHERE city LIKE '%$city%' ORDER BY RAND() LIMIT 2000");
                        $stmt->execute();
                        $row = $stmt->fetchAll();
                        
                        $num = 0;
                        foreach($row as $people){
                            if($num < 3){
                                if(in_array($people["id"], $friends) || $people["id"] == $my_id){
                                    //No results...
                                }else{
                                    echo '
                                    <div class="avatar">
                                        <img src="uploads/avatar/'.$people["avatar"].'" />
                                    </div>
                                    <div class="avatar-side">
                                        <a href="profile.php?id='.$people["id"].'">'.$people["firstname"].' '.$people["lastname"].'</a>
                                        <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> '. $lang["addfriend"].'</a>
                                    </div>
                                    ';
                                }
                            }
                            $num++;
                        }
                        ?>
                </div>
                <br>
                <div class="bt-nav">
                    <a href=""><?php echo $lang["nozha2018"] ?></a>
                    <a href="#"> . <?php echo $lang["privacypolicy"] ?> . </a>
                    <a href="#"> <?php echo $lang["about"] ?> . </a>
                    <a href="#"><?php echo $lang["help"] ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include "inc/footer.php"; 
    ob_end_flush();

                    /*
                $lastpost = 0;
                $posts_num = 1;

                $stmt = $con->prepare("SELECT * FROM posts ORDER BY date DESC");
                $stmt->execute();
                $posts = $stmt->fetchAll();
                
                foreach($posts as $post){
                    if(in_array($post["user"], $friends)){
                        if($posts_num < 0){

                            $post_id = $post["id"];
                            $post_user = $post["user"];
                            echo "<div class='post'>";
                                $stmt = $con->prepare("SELECT * FROM accounts WHERE id = ?");
                                $stmt->execute(array($post["user"]));
                                $user = $stmt->fetch();
                                echo '<img class="avatar" src="uploads/avatar/'.$user["avatar"].'">';
                                echo '<h3>'.$user["firstname"].' '.$user["lastname"].'</h3>';
                                echo '<p>'.$post["content"].'</p>';
                                echo '<img src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
                                echo '<div class="actions" data-id="'.$post_id.'-'.$post_user.'">';
                                    $stmt = $con->prepare("SELECT * FROM likes WHERE post = $post_id");
                                    $stmt->execute();
                                    if($stmt->rowCount() == 0){
                                        echo '<a id="like-'.$post_id.'" class="btn btn-light like-btn"><div id="displaylike'.$post_id.'-'.$post_user.'"><i class="far fa-heart"></i> '.$lang["like"].'</div></a>';
                                    }else{
                                        echo '<a id="like-'.$post_id.'" class="btn btn-light like-btn"><div id="displaylike'.$post_id.'-'.$post_user.'"><i class="fas fa-heart red"></i> '.$lang["liked"].'</div></a>';
                                    }    
                                    echo'<a class="btn btn-light comment-btn"><i class="far fa-comment-alt"></i> '.$lang["comment"].'</a>';
                                    echo'<div class="comments" data-id="'.$post_id.'">';
                                        echo'<input id="commenttext" type="text" class="input-group" placeholder="'.$lang["leavecomment"].'"/>';
                                        echo'<button id="sendcomment" class="btn btn-primary">'.$lang["write"].'</button>';
                                    echo'</div>';
                                echo'</div>';
                            echo "</div>";
                            $posts_num++;
                        } 
                    }
                }
                */
                
?>
<!--<input id="postoffset" type="hidden" value="0">
            <button id="getposts" class="getposts">
                <span id="getpostsword"><?php //echo $lang["getposts"] ?></span>
                <div id="getpostsspinner" class="spinner"></div>
            </button>-->
