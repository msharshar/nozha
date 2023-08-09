<?php

include "init.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Users search terms is saved in $_POST['q']
    $q = $_POST['search'];
    // Prepare statement
    $search = $con->prepare("SELECT * FROM `accounts` WHERE `firstname` LIKE ?");
    // Execute with wildcards
    $search->execute(array("%$q%"));
    // Echo results
    if($search->rowCount() > 0){
        echo '<div class="container">';
            echo '<br><h3 style="text-align: center;">Search results for: '.$q.'</h3><br>';
        foreach($search as $s) {
            echo '<div class="result">';
                echo '<div class="row">';
                    echo '<div class="col-lg-4">';
                        echo '<img src="https://i.ytimg.com/vi/6y-OAsRZbas/maxresdefault.jpg" />';
                    echo '</div>';
                    echo '<div class="col-lg-8">';
                        echo '<a href="profile.php?id='.$s['id'].'">'.$s['firstname'].' '.$s['lastname'].'</a>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }else{
        echo "<br><br>No results";
    }
}