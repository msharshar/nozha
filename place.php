<?php

include "init.php";

if(isset($_GET["ID"])){
    $id = $_GET["ID"];
}else{
    $id = 0;
}

$my_id = $_SESSION["id"];

$stmt = $con->prepare("SELECT * FROM places WHERE id = ?");
$stmt->execute(array($id));
$place = $stmt->fetch();

$stmt3 = $con->prepare("SELECT * FROM accounts WHERE id = ?");
$stmt3->execute(array($my_id));
$me = $stmt3->fetch();

?>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $creatorid = $_SESSION["id"];
        $placeid = $id;
        $day = $_POST["day"];
        $month = $_POST["month"];
        $hour = $_POST["hour"];
        $period = $_POST["period"];
        $ship = array();
        foreach($friends as $friend){
            if(isset($_POST["ship-$friend"])){
                $ship[] = $_POST["ship-$friend"];
            }
        }
        $ship = implode(",", $ship);


        $stmt = $con->prepare('INSERT INTO
                                    nozha
                                    (
                                        creatorid, placeid, day, month, hour, period, ship
                                    ) 
                                    VALUES
                                    (
                                        :creatorid, :placeid, :day, :month, :hour, :period, :ship
                                    )
                        ');
                                        
        $stmt->execute(array(
            'creatorid'     => $creatorid,
            'placeid'       => $placeid,
            'day'           => $day,
            'month'         => $month,
            'hour'          => $hour,
            'period'        => $period,
            'ship'          => $ship,    
        ));

        //NOTIFICATION
        $text = $me["firstname"]." ".$me['lastname']." invited you to go to ".$place['name'];
        $stmt = $con->prepare('INSERT INTO notifications(sender, receiver, text) VALUES(:sender, :receiver, :text)');
                                        
        $stmt->execute(array(
            'sender'     => $my_id,
            'receiver'   => $ship,
            'text'       => $text
        ));

        echo "<div class='container'><div class='alert alert-success'>Nice choice!</div></div>";
        echo "<div class='container'><div class='alert alert-primary'>You can wait until they accept your invite</div></div>";
    }
?>

<div class="container">
    <div class="single-place">
        <div class="row">
            <div class="col-lg-4" style="background: #FFF; padding: 20px">
                <img class="single-place-photo" src="https://media-cdn.tripadvisor.com/media/photo-s/03/1b/e7/1a/castle-street-cafe.jpg" />
            </div>
            <div class="col-lg-8 placeheader" style="background: #FFF; padding: 20px">
                <h2><?php echo $place["name"] ?></h2>
                <span style="margin-top: -13px; display: block;"><?php echo $place["type"] ?></span>
                <b>9/10</b>
                <button id="placego" class="go"><?php echo $lang["go"] ?></button>
                <div class="overlay-back"></div>
                <div class="go-active">
                    <form method="POST" action="<?php $_SERVER['PHP_SELF']?>">
                        <h2>Please choose .... </h2>
                        <p>When will you go?</p>
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="day">
                                <?php
                                    $num = 1;
                                    while($num <= 31){
                                        echo "<option value='$num'>$num</option>";
                                        $num++;
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <select name="month">
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="Augest">Augest</option>
                                    <option value="September ">September </option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December ">December</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <select name="hour">
                                <?php
                                    $num = 1;
                                    while($num <= 12){
                                        echo "<option value='$num'>$num</option>";
                                        $num++;
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <select name="period">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <p>Who will you go with?</p>
                        <div class="form-check">
                            <div class="row">
                            <?php
                                foreach($friends as $friend){
                                    $stmt3 = $con->prepare("SELECT * FROM accounts WHERE id=?");
                                    $stmt3->execute(array($friend));
                                    $row = $stmt3->fetch();
                                    echo '<div class="col-lg-6">';
                                        echo '<div class="select-friend">';
                                            echo '<input class="form-check-input" type="checkbox" value="'.$friend.'" name="ship-'.$friend.'" id="defaultCheck1">';
                                            echo '<img src="uploads/avatar/'.$row["avatar"].'"/>';
                                            echo '<span>'.$row["firstname"].$row["lastname"].'</span>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                            ?>
                            </div>
                        </div>
                        <br>
                        <input type="submit" class="btn btn-primary" />
                    </form>
                </div>
            </div>
            <div class="col-lg-12" style="background: #FFF; padding: 20px; margin-top: 20px">
                <h2 class="title"><?php echo $lang["description"] ?></h2>
                <p><?php echo $place["description"] ?></p>
            </div>
            <div class="col-lg-12" style="background: #FFF; padding: 20px; margin-top: 20px">
                <h2 class="title"><?php echo $lang["gallery"] ?></h2>
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        <img class="d-block w-100" src="http://www.hardrock.com/cafes/koh-samui/files/3214/HRC_Koh_Samui_061.jpg" alt="First slide">
                        </div>
                        <div class="carousel-item">
                        <img class="d-block w-100" src="http://www.hardrock.com/cafes/amsterdam/files/2308/LegendsRoom.jpg" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                        <img class="d-block w-100" src="http://www.cafemonico.com/system/files/042016/5702a180f7c88b353000073b/full_bleed/Cafe_Monico_XX.jpg?1459790229" alt="Third slide">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12" style="background: #FFF; padding: 20px; margin-top: 20px">
                <h2 class="title"><?php echo $lang["whattodo"] ?></h2>
                <?php
                $plan = null;
                $plan = explode(",", $place["plan"]);
                echo "<ul>";
                    echo "<div class='row'>";
                    foreach($plan as $p){
                        echo "<li class='col-lg-6'>$p</li>";
                    } 
                    echo "</div>";
                echo "</ul>";
                ?>
            </div>
        </div>
    </div>
</div>

<?php
include "inc/footer.php";