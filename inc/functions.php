<?php

function login($username, $password){

	global $con;
	
	$stmt = $con->prepare("SELECT * FROM accounts WHERE username = ? AND password = ?");
	$stmt->execute(array($username, $password));
	$row = $stmt->fetch();

    if($stmt->rowCount() > 0){
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $username; //REGISTER USERNAME SESSION
        header("Location: feed.php");
    }else{
        echo "<div class='alert alert-danger col-sm-12' style='margin: 30px auto'><b>User Not Found</b></div>";
    }

}


function register($firstname, $lastname, $email, $username, $password){
    global $con;
	$stmt = $con->prepare('INSERT INTO
                                    accounts
                                    (
                                        firstname, lastname, email, username, password
                                    ) 
                                    VALUES
                                    (
                                        :firstname, :lastname, :email, :username, :password
                                    )
                        ');
                                        
    $stmt->execute(array(
        'firstname'  => $firstname,
        'lastname'   => $lastname, 
        'email'      => $email,
        'username'   => $username,
        'password'   => $password      
    ));
}


function addplace($name, $description, $budget, $plan, $type){
    global $con;
	$stmt = $con->prepare('INSERT INTO
                                    Places
                                    (
                                        name, description, budget, plan, type
                                    ) 
                                    VALUES
                                    (
                                        :name, :description, :budget, :plan, :type
                                    )
                        ');
                                        
    $stmt->execute(array(
        'name'          => $name,
        'description'   => $description, 
        'budget'        => $budget,
        'plan'          => $plan,
        'type'          => $type      
    ));

    echo "<div class='alert alert-success' style='width: 38%; margin: 30px auto'>Nice Place!</div>";
}

function getdata($table){
    global $con;
    global $row;
	
    $stmt = $con->prepare("SELECT * FROM $table");
    $stmt->execute();
	$row = $stmt->fetchAll();
}


function friends(){
    global $my_id;
    global $con;
    global $friends;
    global $row;
    //FRIENDS
    $my_id = $_SESSION["id"];
    $stmt = $con->prepare("SELECT * from friends WHERE sender = ? || receiver = ? && status = 1");
    $stmt->execute(array($my_id, $my_id));
    $friends = array();

    while($row = $stmt->fetch()){
        $friends[] = $row["sender"];
        $friends[] = $row["receiver"];
    }

    $friends = array_diff($friends, ["$my_id"]);

    return $friends;
}

function account(){
    global $my_id;
    global $con;
    global $account;

    $my_id = $_SESSION["id"];
	$stmt = $con->prepare("SELECT * FROM accounts WHERE id = ?");
	$stmt->execute(array($my_id));
    $account = $stmt->fetch();

    return $account;
}