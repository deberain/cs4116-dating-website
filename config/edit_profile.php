<?php
session_start();

include('connection.php');
$user_id = $_SESSION['user_id'];

$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt');
$path = '../images/'; // upload directory

if (!empty($_FILES['image'])) {
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
    //include database configuration file
    include_once 'connection.php';
    //Clear interests for user and then insert all new interests
    $sql = "DELETE FROM `user_interests` WHERE user_id = '$user_id'";

    if ($con->query($sql) === TRUE) {
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
        $sql = "UPDATE `profiles` SET `display_name` = '$display_name',`sex` = '$sex', `preferred_sex` = '$pref', `location` = '$location', `bio` = '$bio' WHERE `user_id` = '$user_id';";
        $sql .= $sqlInterests;
        if ($con->multi_query($sql) === TRUE) {
            $_SESSION['display_name'] = $display_name;
            $_SESSION['location'] = $location;
            $_SESSION['sex'] = $sex;
            $_SESSION['pref'] = $pref;
            $_SESSION['bio'] = $bio;
            $_SESSION['interests'] = $interests;
            exit('Success!');
        } else {
            die('Failure');
        }
    } else {
        die("error deleting previous user interests");
    }
} else {
    die("Invalid");
}
