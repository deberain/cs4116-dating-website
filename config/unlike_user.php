<?php
session_start();

$user_id = $_SESSION['user_id'];

if (!empty($_POST['target_id'])) {
    $target_id = $_POST['target_id'];

    //include database configuration file
    include_once 'connection.php';
    //insert form data in the database
    $sql = "select * from `interactions` where user_id = '$user_id' and target_user = '$target_id' and interaction_type = '1'";  
    $result = mysqli_query($con, $sql);  
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
    $count = mysqli_num_rows($result);  
        
    if($count == 1){  
        $sql = "DELETE FROM `interactions` where user_id = '$user_id' and target_user = '$target_id' and interaction_type = '1'";

        if ($con->query($sql) === TRUE) {
            exit('Success!');
        } else {
            die('Failure');
        }
    } else { 
        die("No such user to dislike");
    }



    if ($con->query($sql) === TRUE) {
        exit('Success!');
    } else {
        die('Failure');
    }
} else {
    die("Invalid");
}
?>