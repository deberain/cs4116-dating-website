<?php
session_start();

include('connection.php');
$user_id = $_SESSION['user_id'];

$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt');
$path = '../images/'; // upload directory

if (!empty($_POST['display_name']) || !empty($_POST['location']) || !empty($_POST['sex']) || !empty($_POST['pref']) || !empty($_POST['bio']) || !empty($_POST['interests']) || $_FILES['image']) {
    $img = $_FILES["image"]["name"];
    $tmp = $_FILES["image"]["tmp_name"];

    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

    $final_image = rand(1000, 1000000) . $img;

    // check's valid format
    if (in_array($ext, $valid_extensions)) {
        $path = $path . strtolower($final_image);

        $dbpath = 'images/' . strtolower($final_image);

        if (move_uploaded_file($tmp, $path)) {
            
            $display_name = $_POST['display_name'];
            $location = $_POST['location'];
            $sex = $_POST['sex'];
            $pref = $_POST['pref'];
            $bio = $_POST['bio'];
            $interests = $_POST['interests'];
            //include database configuration file
            include_once 'connection.php';
            //insert form data in the database
            $sql = "UPDATE `profiles` SET `display_name` = '$display_name',`sex` = '$sex', `preferred_sex` = '$pref', `location` = '$location', `bio` = '$bio', `picture` = '$dbpath' WHERE `user_id` = '$user_id'";

            if ($con->query($sql) === TRUE) {
                $_SESSION['LoggedIn'] = '1';
                $_SESSION['display_name'] = $display_name;
                $_SESSION['location'] = $location;
                $_SESSION['sex'] = $sex;
                $_SESSION['pref'] = $pref;
                $_SESSION['bio'] = $bio;
                $_SESSION['photo'] = $dbpath;
                exit('Success!');
            } else {
                die('Failure');
            }
        }
    }
} else {
    die("Invalid");
}