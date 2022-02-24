<?php     
    session_start();

    include('connection.php');  
    $username = $_POST['username'];  
    $password = $_POST['password'];
    
    $fullname = $_POST['Name'];  
    $age = $_POST['Age']; 
    $sex = $_POST['Sex'];
    $pref = $_POST['Pref'];
    $location = $_POST['Location'];
    $bio = $_POST['Bio'];
        
        //to prevent from mysqli injection  
        $username = stripcslashes($username);  
        $password = stripcslashes($password);
        $fullname = stripcslashes($fullname);  
        $age = stripcslashes($age);   
        $location = stripcslashes($location);    
        $bio = stripcslashes($bio);    
        $username = mysqli_real_escape_string($con, $username);  
        $password = mysqli_real_escape_string($con, $password);  
        $fullname = mysqli_real_escape_string($con, $fullname);  
        $age = mysqli_real_escape_string($con, $age);  
        $location = mysqli_real_escape_string($con, $location);  
        $bio = mysqli_real_escape_string($con, $bio);  

        // Encrypt password to be stored in database
        $encryptedPassword = md5($password);

        // Validate Username doesn't exist
        $sql = "select * from `users` where username = '$username'";  
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);  
            
        if($count == 1){  
            exit("Username already exists.");  
        }  

        // Create User
        $sql = "INSERT INTO `users` (`user_id`, `username`, `password`) VALUES (NULL, '$username', '$encryptedPassword')";  
        $result = mysqli_query($con, $sql);  

        // Get New User id
        $sql = "select user_id from `users` where username = '$username'";  
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $user_id = $row["user_id"];

        // Insert data into profile
        $sql = "INSERT INTO `profiles` (`profileID`, `user_id`, `display_name`, `age`, `sex`, `preferred_sex`, `Location`, `Bio`, `picture`) VALUES (NULL, '$user_id', '$fullname', '$age', '$sex', '$pref', '$location', '$bio', NULL)";
            
        if($con->query($sql) === TRUE){  
            $_SESSION['LoggedIn'] = '1';
            $_SESSION['user'] = $username;
            exit('Success!');  
        }  
        else{  
            exit('Failure');  
        }     
?>  