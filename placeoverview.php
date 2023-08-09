<?php

session_start();
include "inc/header.php"; 
include "db.php";
include "inc/functions.php";
include "inc/navbar.php";
echo "<br><br>";

if(!isset($_SESSION["id"])){
    header("Location: index.php");
}

$placeid = $_GET["id"];

//Visits
$stmt = $con->prepare("SELECT * FROM nozha WHERE placeid = $placeid");
$stmt->execute();
$totvisits = $stmt->rowCount();
$visits = $stmt->fetchAll();

//Announcements
$stmt2 = $con->prepare("SELECT * FROM posts WHERE place = $placeid");
$stmt2->execute();
$anouns = $stmt2->fetchAll();
$totanouns = $stmt2->rowCount();
?>

<div class="container overview">
    <div class="row">
        <div class="col-lg-6">
            <div class="sign sign-1">
                Total Visits
                <?php
                    echo "<p>$totvisits</p>";
                ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="sign sign-2">
                Total Announcements
                <?php
                    echo "<p>$totanouns</p>";
                ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="visits">
                <h2>Visits</h2>
                <hr>
                <div class="alert alert-secondary">
                    <?php
                        foreach($visits as $visit){
                            $stmt = $con->prepare("SELECT * FROM accounts WHERE id = ?");
                            $stmt->execute(array($visit["id"]));
                            $user = $stmt->fetch();

                            echo "<p>".$user["firstname"].' '.$user["lastname"]." is coming to your place with company maybe!</p>";
                            echo "<span>Time: ".$visit["hour"].' '.$visit["period"]."</span>";
                            echo "<br>";
                            echo "<span>Date: ".$visit["day"].' '.$visit["month"]."</span>";
                            
                        }
                    ?>
                </div>
                <hr>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="announcements">
                <h2>Announcements</h2>
                <hr>
                <div class="wpost">
                    <div class="content">
                        <textarea placeholder="Make an announcement now!"></textarea>
                        <hr>
                        <button>Publish</button>
                    </div>
                </div>
                <?php
                    foreach($anouns as $anoun){
                        echo "<div class='post'>";
                            $stmt = $con->prepare("SELECT * FROM places WHERE id = $placeid");
                            $stmt->execute();
                            $place = $stmt->fetch();
                            echo '<img class="avatar" src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
                            echo '<h3>'.$place["name"].'</h3>';
                            //echo '<span>'.$post["date"].'</span>';
                            echo '<p>'.$anoun["content"].'</p>';
                            echo '<img src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
                        echo "</div>";
                    }
                ?>
                <hr>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="link">
                <hr>
                Do you want to edit your place? Do it now.
                <a href="editplace.php?id=<?php echo $placeid ?>" class="btn btn-warning">Edit</a>
                <hr>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="link">
                <hr>
                Remove the place from nozha.
                <a href="#" class="btn btn-danger">Delete</a>
                <hr>
            </div>
        </div>
    </div>
</div>

<?php
include "inc/footer.php";     
?>