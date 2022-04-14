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
    }else if($_POST['func'] == "getReportedUsersTable"){
        getReportedUserTable();
    }else if($_POST['func'] == "removeReportFromDatabase" && $_POST['reportId']){
        removeReportFromDatabase();
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
    $sql = "INSERT INTO `interactions` (`interaction_id`, `user_id`, `target_user`, `interaction_type`, `date`) VALUES ('NULL', '$loggedInUser', '$userIdToBlock', '2', date('Y-m-d H:i:s'));";
    $result = mysqli_query($con, $sql);
    if($result){
      $sqlTwo = "DELETE from matches WHERE (user_one_id = '$userIdToBlock' AND user_two_id = '$loggedInUser') OR (user_two_id = '$userIdToBlock' AND user_one_id = '$loggedInUser');";
      $resultTwo = mysqli_query($con, $sqlTwo);
      if($resultTwo){
        mysqli_close($con);
        exit('User Blocked Successfully');
      }
    }
    mysqli_close($con);
    exit('An Error Occurred Inserting Data Into The Database');
}

function banUser(){
    //Alter banned column in users table
    include('../config/connection.php'); 
    $userIdToBan = $_POST['userId'];
    $sql = "UPDATE users SET banned = 1 WHERE user_id=$userIdToBan;";
    $result = mysqli_query($con, $sql);
    if ($result) {
      $sqlTwo = "DELETE from matches WHERE (user_one_id = '$userIdToBan') OR (user_two_id = '$userIdToBan');";
      $resultTwo = mysqli_query($con, $sqlTwo);
      if($resultTwo){
        mysqli_close($con);
        exit('User Banned Successfully');
      }
    } else {
        mysqli_close($con);
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

function getReportedUserTable(){
    //Get Data From DB
    include ('../config/connection.php');
    $sql = 'SELECT * FROM reported_users;';
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result);
    if($count<1){
        $noReportedUsers = '<h1 style="border-radius:15px;border:3px solid #FFF;color:#FFF;padding:20px;display:inline-block">There are currently no reported users!</h1>';
        echo json_encode(array('data' => $noReportedUsers));
        exit();
    }

    //Create HTML Table Using DB Data
    $tableData = '<table class="table table-striped table-light">
    <thead>
        <tr>
        <th scope="col">Reported User Id</th>
        <th scope="col">Reporting User Id</th>
        <th scope="col">Incident Description</th>
        <th scope="col">Date</th>
        <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>';

    while($row = mysqli_fetch_assoc($result)) {
        $tableData .= '<tr>
        <th scope="row">'. $row['reported_user'] .'</th>
        <td>'. $row['reporting_user'] .'</td>
        <td>'. $row['incident_description'] .'</td>
        <td>'.$row['date'].'</td>
        <td><button type="button" action="ban" user-id="'.$row['reported_user'].'" class="admin-action btn btn-outline-danger m-1">Ban</button><button type="button" action="warn" user-id="'.$row['reported_user'].'" class="admin-action btn btn-outline-warning m-1">Warn</button><button type="button" action="remove" report-id="'.$row['report_id'].'" class="admin-action btn btn-outline-success m-1">Remove</button></td>
        </tr>';
    }

    $tableData .='</tbody></table>';

    echo json_encode(array('data' => $tableData));
    exit();
}

function removeReportFromDatabase(){
    include ('../config/connection.php');
    $reportId = $_POST['reportId'];
    $sql = "DELETE FROM reported_users WHERE report_id=".$reportId.";";
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    if ($result) {
        exit("Report Removed From Database Successfully");
    } else {
        exit('Error Deleting Data From Database');
    }
}

?>