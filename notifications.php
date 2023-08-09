<?php
	ob_start();
	include "init.php";
	  
    echo '<div class="container">';
    ?>
    <div class="navigation col-sm-12">
		<a href="?active=notifications"><?php echo $lang["notifications"] ?></a>
        <a href="?active=invites"><?php echo $lang["invites"] ?></a> 
		<a href="?active=friendrequests"><?php echo $lang["friendrequests"] ?></a>
    </div>
	<br>
    <?php
    //RECIEVE & ACCEPT INVITATION
    if(isset($_GET["active"]) && $_GET["active"] == "invites"){

		$stmt = $con->prepare("SELECT * FROM nozha");
		$stmt->execute();
		$nozhas = $stmt->fetchAll();

		foreach($nozhas as $nozha){
			$creatorid = $nozha["creatorid"];
			$ship = $nozha["ship"];

			if(in_array($creatorid, $friends)){
				$ship = explode(",", $ship);
				if(in_array($my_id, $ship)){
					$stmt = $con->prepare("SELECT * FROM accounts WHERE id=$creatorid");
					$stmt->execute();
					$creator = $stmt->fetch();
					$creatorname = $creator["firstname"]." ".$creator["lastname"];

					echo '<div class="request">';
						echo '<img src="uploads/avatar/'.$creator["avatar"].'" />';
						echo "<h3>$creatorname invited you to go out with him</h3>";
						
						echo '<div class="row">';
							echo '<h3 class="col-lg-12">Ship</h3>';
						echo '</div>';

						echo '<a href="nozha.php?id='.$nozha["id"].'" class="btn btn-primary">Details</a>';
					echo '</div>';

				}
			}
		}


    }
    if(isset($_GET["nozha"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
		$status = $_POST["status"];
		$id     = $_POST["id"];
		$stmt = $con->prepare('UPDATE nozha SET status = ? WHERE id = ?');
		$stmt->execute(array($status, $id));
		header("Location: notifications.php?active=invites");
    }

    //ACCEPT MESSAGE
    $stmt = $con->prepare("SELECT * FROM nozha WHERE creatorid = ? AND status = 1");
    $stmt->execute(array($_SESSION["id"]));
    $accnot = $stmt->fetchAll();

	//NOTIFICATIONS
    if(!isset($_GET["active"]) || $_GET["active"] == "notifications"){
		$stmt = $con->prepare("UPDATE accounts SET notnum=$count WHERE id=$my_id");
		$stmt->execute();

		$stmt = $con->prepare("SELECT * FROM notifications ORDER BY id DESC");
		$stmt->execute();
		$row = $stmt->fetchAll();
		
		foreach($row as $r){
			$receivers = explode(",", $r["receiver"]);
			if(in_array($my_id, $receivers)){
				if($account["lang"] == "AR"){
					$text = $r["text_ar"];
				}else{
					$text = $r["text"];
				}
				echo '<div class="alert alert-primary">'
					.'<div class="row">'
						.'<div class="col-sm-12 col-lg-10 pull-left">'.$text.'</div>'
						.'<div class="col-sm-12 col-lg-2 pull-right">'.$r["date"].'</div>'
					.'</div>
				</div>';
			}
		}
	}
	
	//FRIEND REQUESTS
	if(isset($_GET["active"]) && $_GET["active"] == "friendrequests"){
		$stmt = $con->prepare("SELECT * FROM friends WHERE receiver = ? AND status = 0");
		$stmt->execute(array($_SESSION["id"]));
		$requests = $stmt->fetchAll();

		foreach($requests as $request){
			$stmt2 = $con->prepare("SELECT * FROM accounts WHERE id = ?");
			$stmt2->execute(array($request["sender"]));
			$account = $stmt2->fetch();

			echo '<div class="request">';
						echo '<img src="uploads/avatar/'.$account["avatar"].'">';
						echo "<h3>".$account["firstname"].' '.$account["lastname"].' has sent you friend request</h3>';
						?>
						<form class="accept" method="POST" action="notifications.php?friend">
							<input type="hidden" name="status" value="1">
							<input type="hidden" name="sender" value="<?php echo $request["sender"]?>">
							<input type="hidden" name="receiver" value="<?php echo $request["receiver"]?>">
							<input type="submit" value="Accept" class="btn btn-primary">
						</form>
						<?php
			echo "</div>";
		}
	}

	//ACCEPT FRIENS REQUEST
	if(isset($_GET["friend"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
		$status 	= $_POST["status"];
		$sender     = $_POST["sender"];
		$receiver   = $_POST["receiver"];
		$stmt = $con->prepare('UPDATE friends SET status=? WHERE sender=? AND receiver=?');
		$stmt->execute(array($status, $sender, $receiver));

		$text = $account["firstname"]." ".$account['lastname']." accepted your friend request";
		$stmt = $con->prepare("INSERT INTO notifications(sender, receiver, text) VALUES(:sender, :receiver, :text)");
		$stmt->execute(array(
			'sender'	=> $receiver,
			'receiver'  => $sender,
			'text'		=> $text
		));

		header("Location: notifications.php?active=friendrequests");
    }

    echo '</div>'; // end container
?>


<?php
	include "inc/footer.php";
	ob_end_flush();
?>
