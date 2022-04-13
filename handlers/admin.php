<?php
session_start();

if (!isset($_SESSION['LoggedIn'])) {
    header('Location: index.php');
    exit('User Is Not Logged In');
}

if(isset($_POST['func']))
{
    if($_POST['func'] == "reportUser" && $_POST['userId'] && $_POST['incidentDescription']){
        reportUser();
    }else if($_POST['func'] == "blockUser" && $_POST['userId']){
        blockUser();
    }else if($_POST['func'] == "banUser" && $_POST['userId']){
        banUser();
    }else if($_POST['func'] == "warnUser" && $_POST['userId']){
        warnUser();
    }else{
        http_response_code(400);
    }
}else{
    http_response_code(400);
    header('Location: index.php');
}
exit('Client Passed Values Are Missing/Not Recognized');

function reportUser(){
    //Make entry into reported users table
    include('../config/connection.php'); 
    $userToReport = $_POST['userId'];
    $incidentDescription = mysqli_real_escape_string($con, stripcslashes($_POST['incidentDescription']));
    $loggedInUser = $_SESSION['user_id'];
    $sql = "INSERT INTO `reported_users` (`report_id`, `reported_user`, `reporting_user`, `incident_description`, `date`) VALUES ('NULL', '$userToReport', '$loggedInUser', '$incidentDescription', date('Y-m-d H:i:s'));";
    
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    if($result){
      exit('User Reported Successfully');
    }
    exit('An Error Occurred Inserting Data Into The Database');
}

function blockUser(){
    //Add to blocked_user_relationships table.
    include('../config/connection.php'); 
    $userIdToBlock = $_POST['userId'];
    $loggedInUser = $_SESSION['user_id'];
    $sql = "INSERT INTO `blocked_user_relationships` (`block_id`, `user_id`, `target_user_id`, `date`) VALUES ('NULL', '$loggedInUser', '$userIdToBlock', date('Y-m-d H:i:s'));";
    
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    if($result){
      exit('User Blocked Successfully');
    }
    exit('An Error Occurred Inserting Data Into The Database');
}

function banUser(){
    //Alter banned column in users table
    include('../config/connection.php'); 
    $userIdToBan = $_POST['userId'];
    $sql = "UPDATE users SET banned = 1 WHERE user_id=$userIdToBan;";
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    if ($result) {
        exit('User Banned Successfully');
    } else {
        exit('An Error Occurred Updating The Database');
    }
    

}

function warnUser(){
    include('../config/connection.php'); 
    $userToWarn = $_POST['userId'];
    $messageContent = "It has come to our attention that you have not been in compliance with Cupid.com\'s rules.\nIf the rules are not respected we may be forced to ban your account.\n";
    $loggedInUserId = $_SESSION['user_id'];

    $sql = "SELECT * FROM matches WHERE (user_one_id = '$userToWarn' AND user_two_id = '$loggedInUserId') OR (user_two_id = '$userToWarn' AND user_one_id = '$loggedInUserId');";
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result); 
    if($count<1){
        $sqlTwo = "INSERT INTO `matches` (`match_id`, `user_one_id`, `user_two_id`, `date`) VALUES (NULL, '$loggedInUserId', '$userToWarn', date('Y-m-d H:i:s'))";
        mysqli_query($con, $sqlTwo);
    }

    $sqlThree = "INSERT INTO messages (message_id, sender_id, receiver_id, message_content, date) VALUES ('NULL', '$loggedInUserId', '$userToWarn', '$messageContent', date('Y-m-d H:i:s'))";
    $resultThree = mysqli_query($con, $sqlThree);
    mysqli_close($con);
    if ($resultThree) {
        exit('User Warned Successfully');
    } else {
        exit('An Error Occurred Updating The Database');
    }
}
?>