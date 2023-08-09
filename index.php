<?php
    session_start();
    include "db.php";
    include "inc/functions.php";
    include "inc/lang.php";
    include "inc/header.php"; 

    if(isset($_SESSION["id"])){
        header("Location: feed.php");
    }
    ob_start();

    if(isset($_GET["lang"])){
        if($_GET["lang"] == "en"){
            $lang = $english;
        }
        if($_GET["lang"] == "ar"){
            $lang = $arabic;
        }
    }else{
        $lang = $english;
    }
?>

<div class="login">
    <!-- Top content -->
    <div class="top-content">    	
        <div class="inner-bg">
            <div class="container">
                
                <div class="row">
                    <div class="col-sm-12 text">
                        <h1><?php echo $lang["welcometo"] ?><strong class="fonty"> <?php echo $lang["nozha"] ?></strong></h1>
                        <div class="description">
                            <p>
                                <?php echo $lang["welcomequote"] ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12 col-lg-5">
                        
                        <div class="form-box">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <h3><?php echo $lang["login"] ?></h3>
                                    <p><?php echo $lang["loginquote"] ?></p>
                                </div>
                                <div class="form-top-right">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                            </div>
                            <div class="form-bottom">
                                <form role="form" action="?form=login" method="POST" class="login-form">
                                    <div class="form-group">
                                        <label class="sr-only" for="form-username"><?php echo $lang["username"] ?></label>
                                        <input type="text" name="username" placeholder="<?php echo $lang["username"] ?>" class="form-username form-control" id="form-username">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-password"><?php echo $lang["password"] ?></label>
                                        <input type="password" name="password" placeholder="<?php echo $lang["password"] ?>" class="form-password form-control" id="form-password">
                                    </div>
                                    <button type="submit" class="btn"><?php echo $lang["login"] ?></button>
                                </form>
                            </div>
                        </div>

                        <?php
                            if($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET["form"] == "login"){

                                //GET DATA FROM FORM IN VARS
                                $username = '@'.$_POST['username']; 
                                $password = $_POST['password']; 

                                //CHECK IF THE DATA EXISTS IN DATABASE
                                $stmt = $con->prepare("SELECT * FROM accounts WHERE username = ? AND password = ?");
                                $stmt->execute(array($username, $password));
                                $row = $stmt->fetch();

                                if($stmt->rowCount() > 0){
                                    $_SESSION['id'] = $row['id'];
                                    $_SESSION['username'] = $username; //REGISTER USERNAME SESSION
                                    header("Location: feed.php");
                                }else{
                                    echo "<div class='alert alert-danger col-sm-12' style='margin: 30px auto'><b>".$lang["usernotfound"]."</b></div>";
                                }

                            }
                        ?>  
                        
                        <br>
                        <div class="lang">
                            <a href="?lang=ar" class="btn btn-primary col-sm-5 col-md-3">Arabic</a>
                            <a href="?lang=en" class="btn btn-primary col-sm-5 col-md-3">English</a>
                        </div>
                        <!--<div class="social-login">
                            <h3>...or login with:</h3>
                            <div class="social-login-buttons">
                                <a class="btn btn-link-2" href="#">
                                    <i class="fab fa-facebook"></i> Facebook
                                </a>
                                <a class="btn btn-link-2" href="#">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                                <a class="btn btn-link-2" href="#">
                                    <i class="fab fa-google-plus"></i> Google Plus
                                </a>
                            </div>
                        </div>-->
                        
                    </div>
                    
                    <div class="col-lg-1 middle-border"></div>
                    <div class="col-lg-1"></div>
                        
                    <div class="col-sm-12 col-lg-5">
                        
                        <div class="form-box">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <h3><?php echo $lang["signup"] ?></h3>
                                    <p><?php echo $lang["signupquote"] ?></p>
                                </div>
                                <div class="form-top-right">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                            <div class="form-bottom">
                                <form role="form" action="?form=register" method="post" class="registration-form">
                                    <div class="form-group">
                                        <label class="sr-only" for="form-first-name"><?php echo $lang["fistname"] ?></label>
                                        <input type="text" name="firstname" placeholder="<?php echo $lang["firstname"] ?>" class="form-first-name form-control" id="form-first-name">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-last-name"><?php echo $lang["lastname"] ?></label>
                                        <input type="text" name="lastname" placeholder="<?php echo $lang["lastname"] ?>" class="form-last-name form-control" id="form-last-name">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-email"><?php echo $lang["email"] ?></label>
                                        <input type="text" name="email" placeholder="<?php echo $lang["email"] ?>" class="form-email form-control" id="form-email">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-email"><?php echo $lang["username"] ?></label>
                                        <input type="text" name="username" placeholder="<?php echo $lang["username"] ?>" class="form-email form-control" id="form-email">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-email"><?php echo $lang["password"] ?></label>
                                        <input type="password" name="password" placeholder="<?php echo $lang["password"] ?>" class="form-email form-control" id="form-email">
                                    </div>
                                    <button type="submit" class="btn"><?php echo $lang["signmeup"] ?></button>
                                </form>
                            </div>
                        </div>
                        <?php  ?>

                        <?php
                            if($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET["form"] == "register"){

                                //GET DATA FROM FORM IN VARS
                                $firstname  = $_POST['firstname']; 
                                $lastname   = $_POST['lastname']; 
                                $email      = $_POST['email'];
                                $username   = '@'.$_POST['username'];
                                $password   = $_POST['password'];

                                //CHECK IF THE DATA EXISTS IN DATABASE
                                register($firstname, $lastname, $email, $username, $password);
                                echo "<div class='alert alert-success col-sm-12' style='margin: 30px auto'>".$lang['loginnow']."</div>";

                            }
                        ?>
                        
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</div>

<?php
    include "inc/footer.php"; 
    ob_end_flush();
?>

<!--<img src="http://lh3.googleusercontent.com/7YsE9jLPs5cN1ReL5SPQHXYD149xUQKR_r-XiLwMhfJVLz0CsUjTg9kzh_glzN1Xuw=w300" />-->