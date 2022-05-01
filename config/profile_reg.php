<?php
session_start();

include('connection.php');
$user_id = $_SESSION['user_id'];

$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt', 'jfif');
$path = '../images/'; // upload directory

if ($_FILES['image']['size'] != 0) {
    $img = $_FILES["image"]["name"];
    $tmp = $_FILES["image"]["tmp_name"];

    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

    $final_image = rand(1000, 1000000) . $img;

    if (in_array($ext, $valid_extensions)) {
        $path = $path . strtolower($final_image);

        $dbpath = 'images/' . strtolower($final_image);

        if (move_uploaded_file($tmp, $path)) {
            $sql = "UPDATE `profiles` SET `picture` = '$dbpath' WHERE `user_id` = '$user_id'";
            $con->query($sql);
            $_SESSION['photo'] = $dbpath;
        }
    }
}

if (!empty($_POST['display_name']) || !empty($_POST['location']) || !empty($_POST['sex']) || !empty($_POST['pref']) || !empty($_POST['bio']) || !empty($_POST['interests'])) {

    $display_name = $_POST['display_name'];
    $location = $_POST['location'];
    $sex = $_POST['sex'];
    $pref = $_POST['pref'];
    $bio = $_POST['bio'];
    $interests = $_POST['interests'];

    $display_name = mysqli_real_escape_string($con, $display_name);
    $bio = mysqli_real_escape_string($con, $bio);

    //include database configuration file
    include_once 'connection.php';
    //insert form data in the database (user_interests table)
    $valuesToInsert = "";
    for ($i = 0; $i < count($interests); $i++) {
        $appendSeparator = ",";
        if (count($interests) - 1 === $i) {
            $appendSeparator = ";";
        }
        $valuesToInsert .= "('$user_id', '" . strval($interests[$i]) . "')" . $appendSeparator;
    }
    $sqlInterests = "INSERT INTO `user_interests` (`user_id`, `interest_id`) VALUES " . $valuesToInsert;


    //insert form data in the database (profiles table)
    $sql = "INSERT INTO `profiles` (`user_id`, `display_name`,`sex`, `preferred_sex`, `location`, `bio`) VALUES ('$user_id', '$display_name', '$sex', '$pref', '$location', '$bio');";
    $sql .= $sqlInterests;
    if ($con->multi_query($sql) === TRUE) {
        $_SESSION['LoggedIn'] = '1';
        $_SESSION['display_name'] = stripcslashes($display_name);
        $_SESSION['location'] = $location;
        $_SESSION['sex'] = $sex;
        $_SESSION['pref'] = $pref;
        $_SESSION['bio'] = stripcslashes($bio);
        $_SESSION['interests'] = $interests;
        $_SESSION['photo'] = '';
        exit('Success!');
    } else {
        die('Failure');
    }
} else {
    die("Invalid");
}
