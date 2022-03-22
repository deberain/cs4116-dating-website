<?php
    session_start();

    include("connection.php");

    $user_id = $_SESSION["user_id"];

    $sql = "select * from `users` where user_id != '$user_id'";
    $result = mysqli_query($con, $sql);

    $data = array();

    while ($row = mysqli_fetch_assoc($result))
    {
        $data[] = $row["date_of_birth"];
    }

    echo json_encode($data);

    $con->close();
?>