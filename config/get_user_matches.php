<?php
    session_start();
    
    include("connection.php");

    $user_id = $_SESSION["user_id"];

    $sql = "select * from `matches` where user_one_id = '$user_id'";
    $result = mysqli_query($con, $sql);

    $data = array();

    while ($row = mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }

    $sql = "select * from `matches` where user_two_id = '$user_id'";
    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }

    echo json_encode($data);

    $con->close();
?>