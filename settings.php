<?php

session_start();

include "db.php";
include "inc/functions.php";
include "inc/lang.php";

echo "<br><br>";

if(!isset($_SESSION["id"])){
    header("Location: index.php");
}

?>
<?php

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

//CHECK IF USER EXISTED IN DATABASE
$stmt = $con->prepare('SELECT * FROM accounts WHERE id = ? LIMIT 1');
$stmt->execute(array($id));
$account = $stmt->fetch();

account();
include "inc/header.php"; 
include "inc/navbar.php";

if($stmt->rowCount() > 0){
    ?>
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $error = array();

        if(empty($_POST["firstname"])){
            $error[] = '<div class="alert alert-danger">Firstname connot be empty</div>';
        }else{
            $firstname = $_POST["firstname"];
        }

        if(empty($_POST["lastname"])){
            $error[] = '<div class="alert alert-danger">Lastname connot be empty</div>';
        }else{
            $lastname = $_POST["lastname"];
        }

        if(empty($_POST["email"])){
            $error[] = '<div class="alert alert-danger">Email connot be empty</div>';
        }else{
            $email = $_POST["email"];
        }

        if(empty($_POST["city"])){
            $error[] = '<div class="alert alert-danger">City connot be empty</div>';
        }else{
            $city = $_POST["city"];
        }

        if(empty($_POST["province"])){
            $error[] = '<div class="alert alert-danger">Province connot be empty</div>';
        }else{
            $province = $_POST["province"];
        }

        if(empty($_POST["country"])){
            $error[] = '<div class="alert alert-danger">Country cannot be empty</div>';
        }else{
            $country = $_POST["country"];
        }

        //OPTIONAL
        if(empty($_POST["fathername"])){
            $fathername = '';
        }
        if(empty($_POST["bio"])){
            $bio = '';
        }
        $bio = $_POST["bio"];
        $phone = $_POST["phone"];

        //PASSWORD
        if(empty($_POST["password"])){
            $password = $_POST["oldpassword"];
        }
        elseif(strlen($_POST["password"]) < 8){
            $error[] = '<div class="alert alert-danger">Password must be longer than 8 letters</div>';
        }
        elseif($_POST["password"] !== $_POST["confirmpassword"]){
            $error[] = '<div class="alert alert-danger">Your password does not match</div>';
        }
        else{
            $password = $_POST['password'];
        }

        //EXTENSIONS
        $ext = array("jpg", "jpeg", "png", "gif");

        //AVATAR PICTURE
        if(empty($_FILES["avatar"]["name"])){
            $avatarname = $_POST["oldavatar"];
        }else{
            //FILES
            $avatar     = $_FILES["avatar"];
            $avatarname = $_FILES["avatar"]["name"];
            $avatartmp  = $_FILES["avatar"]["tmp_name"]; 
            $avatarsize = $_FILES["avatar"]["size"];

            //CHECK EXTENSION
            $avatarext = explode('.', $avatarname);
            $avatarext = end($avatarext);
            $avatarext = strtolower($avatarext);

            if(!in_array($avatarext, $ext)){
                $error[] = "<div class='alert alert-danger'>File extension is not allowed</div>";
            }

            //CHECK SIZE
            if($avatarsize > 4*1024*1024){
                $error[] = "<div class='alert alert-danger'>Max size allowed is 4MB</div>";
            }

            //UNIQUE NAME
            $avatarname = rand(0, 1000000).'_'.$avatarname;

            //echo '<div class="alert alert-success">Profile picture has been uploaded successfully</div>';
        }

        //COVER PICTURE
        if(empty($_FILES["cover"]["name"])){
            $covername = $_POST["oldcover"];
        }else{
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
            
            //echo '<div class="alert alert-success">Cover picture has been uploaded successfully</div>';
        }
        
        if(empty($error)){
            //UPLOAD PROCESS
            if(!empty($_FILES["avatar"]["name"])){
                move_uploaded_file($avatartmp, "uploads\avatar\\".$avatarname);
            }
            if(!empty($_FILES["cover"]["name"])){
                move_uploaded_file($covertmp, "uploads\cover\\".$covername);
            }
            
            //UPDATE DATABASE
            $stmt = $con->prepare("UPDATE accounts SET firstname=?, fathername=?, lastname=?, email=?, city=?, province=?, country=?, phone=?, bio=?, avatar=?, cover=? WHERE id=?");
            $stmt->execute(array($firstname, $fathername, $lastname, $email, $city, $province, $country, $phone, $bio, $avatarname, $covername, $id));
            echo "<div class='container'><div class='alert alert-success'>Your info has been edited successfully</div></div>";
        }else{
            foreach($error as $err){
                echo '<div class="container">';
                    echo $err;
                echo '</div>';
            }
        }
        
    }
    ?>
    </div>
    <?php
}

?>
    <div class="container edit">
        <h2 class="edit-title"><?php echo $lang["edityourinfo"] ?></h2>
        <p><?php echo $lang["editquote"] ?></p>
        <br>
        <form method="POST" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <h2 data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <?php echo $lang["basic"] ?>
                            </h2>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label><?php echo $lang["firstname"] ?> <b style="color: red">*</b></label>
                                        <input type="text" name="firstname" value="<?php echo $account['firstname'] ?>" class="form-control" placeholder="Enter firstname">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label><?php echo $lang["fathername"] ?></label>
                                        <input type="text" name="fathername" value="<?php echo $account['fathername'] ?>" class="form-control" placeholder="Enter fathername">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label><?php echo $lang["lastname"] ?> <b style="color: red">*</b></label>
                                        <input type="text" name="lastname" value="<?php echo $account['lastname'] ?>" class="form-control" placeholder="Enter lastname">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $lang["email"] ?> <b style="color: red">*</b></label>
                                        <input type="email" name="email" value="<?php echo $account['email'] ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <h2 data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <?php echo $lang["additional"] ?>
                            </h2>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label><?php echo $lang["city"] ?> <b style="color: red">*</b></label>
                                        <input type="text" name="city" value="<?php echo $account['city'] ?>" class="form-control" placeholder="Enter city name">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label><?php echo $lang["province"] ?> <b style="color: red">*</b></label>
                                        <input type="text" name="province" value="<?php echo $account['province'] ?>" class="form-control" placeholder="Enter province name">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label><?php echo $lang["country"] ?> <b style="color: red">*</b></label>
                                        <input type="text" name="country" value="<?php echo $account['country'] ?>" class="form-control" placeholder="Enter country name">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label><?php echo $lang["phone"] ?></label>
                                        <input type="number" name="phone" value="<?php echo '0'.$account['phone'] ?>" class="form-control" placeholder="Enter phone number">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label><?php echo $lang["bio"] ?></label>
                                        <textarea name="bio"><?php echo $account["bio"] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <h2 data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <?php echo $lang["pictures"] ?>
                            </h2>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label><?php echo $lang["profilepicture"] ?></label>
                                    <div class="file-upload">
                                        <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add Image</button>

                                        <div class="image-upload-wrap">
                                            <input class="file-upload-input" type="file" name="avatar" onchange="readURL(this);" accept="image/*" />
                                            <input type="hidden" name="oldavatar" value="<?php echo $account["avatar"] ?>" />
                                            <div class="drag-text">
                                                <h3>Drag and drop a file or select add Image</h3>
                                            </div>
                                        </div>
                                        <div class="file-upload-content">
                                            <img class="file-upload-image" src="#" alt="your image" />
                                            <div class="image-title-wrap">
                                                <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label><?php echo $lang["coverpicture"] ?></label>
                                    <div class="file-upload">
                                        <button class="file-upload-btn-2" type="button" onclick="$('.file-upload-input-2').trigger( 'click' )">Add Image</button>

                                        <div class="image-upload-wrap-2">
                                            <input class="file-upload-input-2" type='file' name="cover" onchange="readURLz(this);" accept="image/*" />
                                            <input type="hidden" name="oldcover" value="<?php echo $account["cover"] ?>" />
                                            <div class="drag-text-2">
                                                <h3>Drag and drop a file or select add Image</h3>
                                            </div>
                                        </div>
                                        <div class="file-upload-content-2">
                                            <img class="file-upload-image-2" src="#" alt="your image" />
                                            <div class="image-title-wrap-2">
                                                <button type="button" onclick="removeUploadz()" class="remove-image-2">Remove <span class="image-title-2">Uploaded Image</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h5 class="mb-0">
                            <h2 data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                <?php echo $lang["password"] ?>
                            </h2>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label><?php echo $lang["password"] ?></label>
                                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                                        <input type="hidden" name="oldpassword" value="<?php echo $account["password"] ?>">
                                        <small class="form-text text-muted">Choose strong one.</small>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $lang["confirmpassword"] ?></label>
                                        <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <br><br>
                </div>

                <div class="col-lg-2">
                    <button type="submit" class="btn btn-primary"><span><?php echo $lang["edit"] ?></span></button>
                </div>

                <div class="col-lg-12">
                    <br><br>
                </div>
            </div>
        </form>
        
<?php
include "inc/footer.php";