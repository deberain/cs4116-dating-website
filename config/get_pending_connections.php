<?php
session_start();

$user_id = $_SESSION['user_id'];

//include database configuration file
include_once 'connection.php';
//insert form data in the database
$sql = "select user_id from `interactions` where target_user = '$user_id' and interaction_type = '1'";
$result = mysqli_query($con, $sql);

$data = array();

while ($row = mysqli_fetch_assoc($result))
{
    $data[] = $row['user_id'];
}

echo json_encode($data);
?>
