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
        
    $nozha_id = $_GET["id"];
    $stmt = $con->prepare("SELECT * FROM nozha WHERE id = ?");
	$stmt->execute(array($nozha_id));
	$nozha = $stmt->fetch();

    $my_id = $_SESSION["id"];
	$stmt3 = $con->prepare("SELECT * FROM accounts WHERE id = ?");
	$stmt3->execute(array($my_id));
	$me = $stmt3->fetch();
	//FRIENDS
	$stmt2 = $con->prepare("SELECT * from friends WHERE sender = ? || receiver = ? && status = 1");
	$stmt2->execute(array($my_id, $my_id));

	$friends = array();

	while($row = $stmt2->fetch()){
		$friends[] = $row["sender"];
		$friends[] = $row["receiver"];
	}

	$friends = array_diff($friends, ["$my_id"]);

    echo '<div class="container">';
    ?>
        <div class="invite">
            <div class="row">
                <div class="col-lg-4">
                    <h2 class="">Place</h2>
                    <br>
                    <?php
                        $place_id = $nozha["placeid"];
                        $stmt = $con->prepare("SELECT * FROM places WHERE id=$place_id");
                        $stmt->execute();
                        $place = $stmt->fetch();

                        echo '<img src="layout/img/no-thumbnail.jpg" />';
                        echo '<h3>'.$place["name"].'</h3>';
                        echo '<a href="place.php?id='.$place_id.'" class="btn btn-primary">More</a>';
                    ?>
                </div>
                <div class="col-lg-4">
                    <h2 class="">Ship</h2>
                    <br>
                    <?php
                        $ship = $nozha["ship"];
                        $ship = explode(",", $ship);
                        foreach($ship as $s){
                            $stmt = $con->prepare("SELECT * FROM accounts WHERE id=$s");
                            $stmt->execute();
                            $account = $stmt->fetch();

                            echo '<div class="ship">';
                                echo '<img class="avatar" src="uploads/avatar/'.$account["avatar"].'" />';
                                echo "<h3><a href='profile.php?id=".$account["id"]."'>".$account["firstname"]." ".$account["lastname"]."</a></h3>";
                            echo '</div>';
                        }
                    ?>
                </div>
                <div class="col-lg-4">
                    <h2 class="">Info</h2>
                    <br>
                    <?php
                        echo "<h3>Time: ".$nozha["hour"].":00 ".$nozha["period"]."</h3>";
                        echo "<hr>";
                        echo "<h3>Month: ".$nozha["month"]."</h3>";
                        echo "<hr>";
                        echo "<h3>Day: ".$nozha["day"]."</h3>";
                        echo "<hr>";
                        ?>
                        <form action="nozha.php?do=accept" method="POST">
                            <input type="submit" class="btn btn-success" value="Accept">
                        </form>
                        <br>
                        <form action="nozha.php?do=refuse" method="POST">
                            <input type="submit" class="btn btn-danger" value="Refuse">
                        </form>
                        <?php
                    ?>
                </div>
            </div>
        </div>
    <?php
    echo '</div>'; // end container

    //ACCEPT NOZHA
    if(isset($_GET["do"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
        if($_GET["do"] == "accept"){
            $status = $_POST["status"];
            $id     = $_POST["id"];
            $stmt = $con->prepare('UPDATE nozha SET status = ? WHERE id = ?');
            $stmt->execute(array($status, $id));
            header("Location: notifications.php?active=invites");
        }
        
        if($_GET["do"] == "refuse"){
            $status = $_POST["status"];
            $id     = $_POST["id"];
            $stmt = $con->prepare('UPDATE nozha SET status = ? WHERE id = ?');
            $stmt->execute(array($status, $id));
            header("Location: notifications.php?active=invites");
        }
    }
?>


<?php
    include "inc/footer.php";
?>