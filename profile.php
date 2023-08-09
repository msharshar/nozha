<?php
	ob_start();
	include "init.php";
	//POSTS
	$profile_id = $_GET["id"];
	$my_name = $account["firstname"].' '.$account["lastname"];
	$stmt = $con->prepare("SELECT * FROM accounts WHERE id = $profile_id");
	$stmt->execute();
	$profile_data = $stmt->fetch();

	$stmt2 = $con->prepare("SELECT * FROM posts WHERE id = $profile_id");
	$stmt2->execute();
	$posts = $stmt2->fetchAll();

?>
<?php
	if($_SESSION["id"] == $profile_id){
?>
		<div class="cover" style="background-image: url('uploads/cover/<?php echo $account["cover"] ?>')">
			
		</div>
		<div class="container">
			<div class="profile-header">
				<div class="row">
					<div class="col-xs-12 col-lg-4">
						<div class="avatarcard">
							<div class="pic">
								<img class="pp" src="uploads/avatar/<?php echo $account["avatar"] ?>" />
							</div>
							<h2 class="name"><?php echo $account["firstname"].' '.$account["lastname"] ?></h2>
							<h2 class="username"><?php echo $account["username"] ?></h2>
						</div>
						<div class="bio">
							<h2><?php echo $lang["bio"] ?></h2>
							<p><?php echo $account["bio"] ?></p>
						</div>
					</div>
					<div class="col-xs-12 col-lg-8">
						<div class="profile-nav">
							<a href="profile.php?id=<?php echo $_SESSION['id'] ?>"><?php echo $lang["activity"] ?></a>
							<a href="profile.php?id=<?php echo $_SESSION['id'] ?>&active=about"><?php echo $lang["about"] ?></a>
							<a href="profile.php?id=<?php echo $_SESSION['id'] ?>&active=friends"><?php echo $lang["friends"] ?></a>
							<a href="profile.php?id=<?php echo $_SESSION['id'] ?>&active=places"><?php echo $lang["nozhaplaces"] ?></a>
						</div>
						<?php
							if(!isset($_GET["active"])){
								foreach($posts as $post){
									echo "<div class='post'>";
										echo '<img class="avatar" src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
										echo '<h3>'.$account["firstname"].' '.$account["lastname"].'</h3>';
										//echo '<span>'.$post["date"].'</span>';
										echo '<p>'.$post["content"].'</p>';
										echo '<img src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
									echo "</div>";
								}
							}elseif(isset($_GET["active"]) && $_GET["active"] == "about"){
								echo '<div class="row">';
									echo '<div class="col-lg-6">';
										echo '<div class="info">';
											echo '<h3>'.$lang["from"].': '.$account["city"].', '.$account["province"].', '.$account["country"].'</h3>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}elseif(isset($_GET["active"]) && $_GET["active"] == "friends"){
								echo '<div class="row">';
								foreach($friends as $friendid){
									echo '<div class="col-lg-6">';
										$stmt = $con->prepare("SELECT * FROM accounts WHERE id = $friendid");
										$stmt->execute();
										$friend = $stmt->fetch();
										echo '<div class="friend">';
											echo '<img src="uploads/avatar/'.$friend["avatar"].'" >';
											echo '<h3><a href="profile.php?id='.$friend["id"].'">'.$friend["firstname"].' '.$friend["lastname"].'</a></h3>';
										echo '</div>';
									echo '</div>';
								}
								echo '</div>';
							}elseif(isset($_GET["active"]) && $_GET["active"] == "nozha"){
								$stmt = $con->prepare("SELECT * FROM nozha");
								$stmt->execute();
								$nozhas = $stmt->fetchAll();
								foreach($nozhas as $nozha){
									$creatorid = $nozha["creatorid"];
									$ship = $nozha["ship"];
						
									if($creatorid == $my_id){
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
						?>
					</div>
				</div>
			</div>
		</div>
<?php
	}
?>

<?php
	if($_SESSION["id"] !== $profile_id){
?>
		<div class="container">
			<div class="profile-header">
				<div class="row">
					<div class="col-lg-12">
						<img class="cover" src="uploads/cover/<?php echo $profile_data["cover"] ?>" />
					</div>
					<div class="col-lg-4">
						<div class="avatarcard">
							<div class="pic">
								<img class="pp" src="uploads/avatar/<?php echo $profile_data["avatar"] ?>" />
							</div>
							<h2  style="margin-top: -110px; margin-left: 20px; font-size: 45px"><?php echo $profile_data["firstname"].' '.$profile_data["lastname"] ?></h2>
							<h2  style="margin-top: -10px; margin-left: 20px; font-size: 26px"><?php echo $profile_data["username"] ?></h2>
						</div>
						<div class="bio">
							<h2><?php echo $lang["bio"] ?></h2>
							<p><?php echo $profile_data["bio"] ?></p>
						</div>
					</div>
					<div class="col-lg-8">
						<div class="profile-nav">
							<a href="profile.php?id=<?php echo $_GET['id'] ?>"><?php echo $lang["activity"] ?></a>
							<a href="profile.php?id=<?php echo $_GET['id'] ?>&active=about"><?php echo $lang["about"] ?></a>
							<a href="profile.php?id=<?php echo $_GET['id'] ?>&active=friends"><?php echo $lang["friends"] ?></a>
							<a href="profile.php?id=<?php echo $_GET['id'] ?>&active=places"><?php echo $lang["nozhaplaces"] ?></a>
							<?php
								$stmt = $con->prepare("SELECT * FROM friends WHERE sender=? AND receiver=? AND status=?");
								$stmt->execute(array($_SESSION["id"], $profile_id, 0));
								$count = $stmt->rowCount();

								$stmt2 = $con->prepare("SELECT * FROM friends WHERE sender=? AND receiver=? AND status=?");
								$stmt2->execute(array($profile_id, $_SESSION["id"], 0));
								$count2 = $stmt2->rowCount();

								$stmt3 = $con->prepare("SELECT * FROM friends WHERE sender=? AND receiver=? AND status=?");
								$stmt3->execute(array($_SESSION["id"], $profile_id, 1));
								$count3 = $stmt3->rowCount();

								$stmt4 = $con->prepare("SELECT * FROM friends WHERE sender=? AND receiver=? AND status=?");
								$stmt4->execute(array($profile_id, $_SESSION["id"], 1));
								$count4 = $stmt4->rowCount();

								if($count3 == 1 || $count4 == 1){
									echo '<a class="btn btn-primary pull-right" href="profile.php?id='.$profile_id.'&delete">'.$lang["deletefriend"].'</a>';
								}
								elseif($count == 0){
									if($count2 == 1){
										echo '<a class="btn btn-primary" href="notifications.php?active=friendrequests">'.$lang["acceptfriend"].'</a>';
									}
									if($count2 == 0){
										echo '<a class="btn btn-primary" href="profile.php?id='.$profile_id.'&add">'.$lang["addfriend"].'</a>';
									}									
								}
								elseif($count == 1){
									echo '<a class="btn btn-primary" href="profile.php?id='.$profile_id.'&cancel">'.$lang["cancelrequest"].'</a>';
								}		
							?>
						<?php
							$sender   = $_SESSION["id"];
							$receiver = $profile_id;
							$status	  = 0;
							if(isset($_GET["add"])){
								$stmt = $con->prepare("INSERT INTO friends(sender, receiver, status) VALUES(:sender, :receiver, :status)");
								$stmt->execute(array(
									'sender'	=> $sender,
									'receiver'  => $receiver,
									'status'	=> $status
								));
								$stmt = $con->prepare("INSERT INTO notifications(sender, receiver, text) VALUES(:sender, :receiver, :text)");
								$stmt->execute(array(
									"sender"    => $my_id,
									"receiver"  => $profile_id,
									"text"      => "$my_name sent you friend request",
								));
								header("Location: profile.php?id=$profile_id");
								//echo "<div class='alert alert-success' style='width: 38%; margin: 30px auto'>Friend request has been sent</div>";
							}
							if(isset($_GET["cancel"])){
								$stmt= $con->prepare('DELETE FROM friends WHERE sender = :sender AND receiver = :receiver AND status = :status');
								$stmt->bindparam(':sender', $sender);
								$stmt->bindparam(':receiver', $receiver);
								$stmt->bindparam(':status', $status);
								$stmt->execute();

								$not_text = "$my_name sent you friend request";
								$stmt = $con->prepare("SELECT id FROM notifications WHERE sender=? AND receiver=? AND text=?");
								$stmt->execute(array($my_id, $profile_id, $not_text));
								$not = $stmt->fetch();
								$not_id = $not["id"];
								
								$not_text = "";
								$stmt = $con->prepare("UPDATE notifications SET text=? WHERE id=?");
								$stmt->execute(array($not_text, $not_id));
								   
								header("Location: profile.php?id=$profile_id");
							}
							if(isset($_GET["delete"])){
								$status = 1;
								$stmt= $con->prepare('DELETE FROM friends WHERE sender = :sender AND receiver = :receiver AND status = :status');
								$stmt->bindparam(':sender', $sender);
								$stmt->bindparam(':receiver', $receiver);
								$stmt->bindparam(':status', $status);
								$stmt->execute();

								$stmt= $con->prepare('DELETE FROM friends WHERE sender = :sender AND receiver = :receiver AND status = :status');
								$stmt->bindparam(':sender', $receiver);
								$stmt->bindparam(':receiver', $sender);
								$stmt->bindparam(':status', $status);
								$stmt->execute();
								header("Location: profile.php?id=$profile_id");
							}
							?>
						</div>
						<?php
							if(!isset($_GET["active"])){
								foreach($posts as $post){
									echo "<div class='post'>";
										echo '<img class="avatar" src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
										echo '<h3>'.$profile_data["firstname"].' '.$profile_data["lastname"].'</h3>';
										//echo '<span>'.$post["date"].'</span>';
										echo '<p>'.$post["content"].'</p>';
										echo '<img src="http://media.comicbook.com/2018/01/thor-movie-trilogy-fan-poster-mcu-by-rico-jr-crea-1074104.jpeg">';
									echo "</div>";
								}
							}elseif(isset($_GET["active"]) && $_GET["active"] == "about"){
								echo '<div class="row">';
									echo '<div class="col-lg-6">';
										echo '<div class="info">';
											echo '<h3>From: '.$profile_data["city"].', '.$profile_data["province"].', '.$profile_data["country"].'</h3>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}elseif(isset($_GET["active"]) && $_GET["active"] == "friends"){
								echo '<div class="row">';
								foreach($friends as $friendid){
									echo '<div class="col-lg-6">';
										$stmt = $con->prepare("SELECT * FROM accounts WHERE id = $friendid");
										$stmt->execute();
										$friend = $stmt->fetch();
										echo '<div class="friend">';
											echo '<img src="uploads/avatar/'.$friend["avatar"].'" >';
											echo '<h3><a href="profile.php?id='.$friend["id"].'">'.$friend["firstname"].' '.$friend["lastname"].'</a></h3>';
										echo '</div>';
									echo '</div>';
								}
								echo '</div>';
							}elseif(isset($_GET["active"]) && $_GET["active"] == "nozha"){
								echo "wel4";
							}
						?>
					</div>
				</div>
			</div>
		</div>
<?php
	}
?>
<?php
	include "inc/footer.php"; 
	ob_end_flush();
	/*
		<?php
								$stmt = $con->prepare("SELECT * FROM friends WHERE sender=? AND receiver=? AND status=?");
								$stmt->execute(array($_SESSION["id"], $id, 0));
								$row = $stmt->fetchAll();
								$count = $stmt->rowCount();
								if($count == 0){
									echo '<a href="profile.php?id='.$id.'&add">Add friend</a>';
								}
								else{
									echo '<a href="profile.php?id='.$id.'&cancel">Cancel request</a>';
								}		
							?>
						</div>
						<?php
							$sender   = $_SESSION["id"];
							$receiver = $account["id"];
							$status	  = 0;
							if(isset($_GET["add"])){
								$stmt = $con->prepare("INSERT INTO friends(sender, receiver, status) VALUES(:sender, :receiver, :status)");
								$stmt->execute(array(
									'sender'	=> $sender,
									'receiver'  => $receiver,
									'status'	=> $status
								));
								header("Location: profile.php?id=$id");
								//echo "<div class='alert alert-success' style='width: 38%; margin: 30px auto'>Friend request has been sent</div>";
							}
							if(isset($_GET["cancel"])){
								$stmt= $con->prepare('DELETE FROM friends WHERE sender = :sender AND receiver = :receiver AND status = :status');
								$stmt->bindparam(':sender', $sender);
								$stmt->bindparam(':receiver', $receiver);
								$stmt->bindparam(':status', $status);
								$stmt->execute();
								header("Location: profile.php?id=$id");
							}

	*/
?>