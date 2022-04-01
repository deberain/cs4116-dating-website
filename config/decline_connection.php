<?php
session_start();

$user_id = $_SESSION['user_id'];

if (!empty($_POST['target_id'])) {
    $target_id = $_POST['target_id'];

    //include database configuration file
    include_once 'connection.php';
    //insert form data in the database
    $sql = "DELETE FROM `interactions` where user_id = '$target_id' and target_user = '$user_id' and interaction_type = '1'";  

    if ($con->query($sql) === TRUE) {
        exit('Success!');
    } else {
        die('Failure');
    }
} else {
    die("Invalid");
}
?>