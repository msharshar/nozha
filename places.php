<?php

session_start();

include "db.php";
include "inc/functions.php";
include "inc/lang.php";

echo "<br><br>";

if(!isset($_SESSION["id"])){
    header("Location: index.php");
}

$stmt = $con->prepare("SELECT * FROM accounts WHERE id=?");
$stmt->execute(array($_SESSION["id"]));
$me = $stmt->fetch();

account();

include "inc/header.php"; 
include "inc/navbar.php";
?>

<div class="container">
    <div class="single-place">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="title"><?php echo $lang["places"] ?></h2>
                <br>
            </div>
            <div class="col-sm-12 navigation">
                <a href="?active=all"><?php echo $lang["allplaces"] ?></a>
                <a href="?active=you"><?php echo $lang["yourplaces"] ?></a>
                <a href="addplace.php"><?php echo $lang["addplace"] ?></a>
                <br><br>
            </div>
            <?php
            if(!isset($_GET["active"]) || $_GET["active"] == "all"){
            ?>
                <form method="GET" class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-2">
                            <h3 class="wh"><?php echo $lang["whereareyou"] ?></h3>
                            <br>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <input type="text" name="city" value="" class="form-control" placeholder="<?php echo $lang["city"] ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                        <div class="form-group">
                                <input type="text" name="province" value="" class="form-control" placeholder="<?php echo $lang["province"] ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                        <div class="form-group">
                                <input type="text" name="country" value="" class="form-control" placeholder="<?php echo $lang["country"] ?>">
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <input class="btn btn-primary" type="submit" value="<?php echo $lang["go"] ?>"/>
                        </div>
                    </div>
                </form>
                
                <?php
                if(!isset($_GET["country"]) || !isset($_GET["province"]) || !isset($_GET["city"])){
                    $city = $account["city"];
                    $province = $account["province"];
                    $country = $account["country"];
                    $stmt = $con->prepare("SELECT * FROM places WHERE city LIKE '%$city%' AND province LIKE '%$province%' AND country LIKE '%$country%'");
                    $stmt->execute();
                    $places = $stmt->fetchAll();
                    foreach($places as $place){
                        echo '<div class="col-lg-4">';
                            echo '<div class="place">';
                                echo '<img src="https://media-cdn.tripadvisor.com/media/photo-s/03/1b/e7/1a/castle-street-cafe.jpg" />';
                                echo '<h2>'.$place["name"].' <span class="badge badge-secondary">';
                                    if($place["type"] == "Cafe"){
                                        echo $lang["cafe"];
                                    } 
                                echo '</span></h2>';
                                //echo '<span>'.$place["type"].'</span>';
                                echo '<p>'.$place["description"].'<p/>';
                                echo '<p>'.$place["budget"].' EGP<p/>';
                                //echo '<p>'.$place["plan"].'<p/>';
                                echo '<a class="more" href="place.php?ID='.$place["id"].'">'.$lang["more"].'</a>';
                            echo '</div>';
                        echo '</div>';
                    }
                }

                if(isset($_GET["country"]) || isset($_GET["province"]) || isset($_GET["city"])){
                    $country    = $_GET["country"];
                    $province   = $_GET["province"];
                    $city       = $_GET["city"];

                    $stmt = $con->prepare("SELECT * FROM places WHERE country LIKE '%$country%' AND province LIKE '%$province%' AND city LIKE '%$city%'");
                    $stmt->execute();


                    foreach($stmt as $place){
                        echo '<div class="col-lg-4">';
                            echo '<div class="place">';
                                echo '<img src="https://media-cdn.tripadvisor.com/media/photo-s/03/1b/e7/1a/castle-street-cafe.jpg" />';
                                echo '<h2>'.$place["name"].' <span class="badge badge-secondary">'.$place["type"].'</span></h2>';
                                //echo '<span>'.$place["type"].'</span>';
                                echo '<p>'.$place["description"].'<p/>';
                                echo '<p>'.$place["budget"].' EGP<p/>';
                                //echo '<p>'.$place["plan"].'<p/>';
                                echo '<a class="more" href="place.php?ID='.$place["id"].'">More</a>';
                            echo '</div>';
                        echo '</div>';
                    }
                }

            }
            if(isset($_GET["active"]) && $_GET["active"] == "you"){
                $stmt = $con->prepare("SELECT * FROM places WHERE creatorid = ?");
                $stmt->execute(array($_SESSION["id"]));
                $row = $stmt->fetchAll();
                foreach($row as $place){
                    echo '<div class="col-lg-4">';
                        echo '<div class="place">';
                            echo '<img src="https://media-cdn.tripadvisor.com/media/photo-s/03/1b/e7/1a/castle-street-cafe.jpg" />';
                            echo '<h2>'.$place["name"].' <span class="badge badge-secondary">'.$place["type"].'</span></h2>';
                            //echo '<span>'.$place["type"].'</span>';
                            echo '<p>'.$place["description"].'<p/>';
                            echo '<p>'.$place["budget"].' EGP<p/>';
                            //echo '<p>'.$place["plan"].'<p/>';
                            echo '<a class="more" href="place.php?ID='.$place["id"].'">More</a>';
                            echo '<a class="over" href="placeoverview.php?id='.$place["id"].'">Overview</a>';
                        echo '</div>';
                    echo '</div>';
                }
            }
            ?>
            
        </div>
    </div>  
</div>

<?php
include "inc/footer.php";