<?php
    session_start();
    
    include("connection.php");

    $user_id = $_SESSION["user_id"];

    $sql = "SELECT profiles.user_id, profiles.display_name, profiles.sex, profiles.preferred_sex, profiles.location, profiles.bio, profiles.picture, users.date_of_birth, users.user_type, users.banned FROM profiles INNER JOIN users where profiles.user_id = users.user_id and profiles.user_id != '$user_id'";
    $profiles = mysqli_query($con, $sql);

    $data = array();

    while ($row = mysqli_fetch_assoc($profiles))
    {
        $profile_id = $row['user_id'];

        $sql = "SELECT user_interests.interest_id, interests.interest_name FROM user_interests INNER JOIN interests where user_interests.interest_id = interests.interest_id AND user_interests.user_id = '$profile_id'";
        $user_interests = mysqli_query($con, $sql);
        
        $interests = array();

        while($interest_row = mysqli_fetch_assoc($user_interests)) {
            $interests[] = $interest_row;
        }

        $target_id = $row['user_id'];

        $sql = "select * from `interactions` where user_id = '$user_id' and target_user = '$target_id' and interaction_type = '1'";  
        $user_liked = mysqli_query($con, $sql);  
        $count = mysqli_num_rows($user_liked);  
            
        if($count == 1){  
            $row["is_user_liked"] = "true";
        } else { 
            $row["is_user_liked"] = "false";
        }

        $row["display_name"] = stripcslashes($row["display_name"]);
        $row["bio"] = stripcslashes($row["bio"]);
        $row["interests"] = $interests;
        $data[] = $row;
    }

    echo json_encode($data);

    $con->close();
?>