<?php
    friends();
    account();
    if($account["lang"] == "AR"){
        $lang = $arabic;
    }
    else{
        $lang = $english;
    }
?>
<nav>
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-1">
            <h3><a href="index.php" class="nozha-title"><?php echo $lang["nozha"] ?></a></h3>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-5 ">
            <form method="POST" action="search.php">
                <input type="text" name="search" placeholder="<?php echo $lang["search"] ?>" />
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4 links">
            <a href="notifications.php"><i class="far fa-bell"></i> <?php echo $lang["notifications"] ?></a>
            <span id="nnum"></span>
            
            <?php 
                $stmt = $con->prepare("SELECT * FROM notifications ORDER BY id DESC");
                $stmt->execute();
                $row = $stmt->fetchAll();
                $count = 0;
                foreach($row as $r){
                    if(in_array($r["sender"], $friends)){
                        $receivers = explode(",", $r["receiver"]);
                        if(in_array($my_id, $receivers)){
                            $count++;
                        }
                    }
                }
                $old_count = $account["notnum"];
                if($count > $old_count){
                    $new_count = $count - $old_count;
                }
            ?>
            <a href="places.php"><i class="far fa-paper-plane"></i> <?php echo $lang["places"] ?></a>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-2 links">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> <span><?php echo $lang["account"] ?></span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="profile.php?id=<?php echo $_SESSION["id"] ?>"><i class="fas fa-user"></i> <?php echo $lang["profile"] ?></a>
                    <a class="dropdown-item" href="settings.php?id=<?php echo $_SESSION['id'] ?>"><i class="fas fa-cog"></i> <?php echo $lang["settings"] ?></a>
                    <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> <?php echo $lang["logout"] ?></a>
                </div>
            </div>
        </div>
    </div>
</nav>
<br>
