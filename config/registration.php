<?php     
    session_start();

    include('connection.php');
    $username = $_POST['username'];  
    $password = $_POST['password'];
    $DOB = $_POST['DOB']; 
        
        //to prevent from mysqli injection  
        $username = stripcslashes($username);  
        $password = stripcslashes($password);    
        $username = mysqli_real_escape_string($con, $username);  
        $password = mysqli_real_escape_string($con, $password); 

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
        $sql = "INSERT INTO `users` (`user_id`, `username`, `password`, `date_of_birth`, `user_type`, `banned`, `email`) VALUES (NULL, '$username', '$encryptedPassword', '$DOB', 2, 0, NULL)";  
        $result = mysqli_query($con, $sql);  

        // Get New User id
        $sql = "select user_id from `users` where username = '$username'";  
        $result = mysqli_query($con, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $user_id = $row["user_id"];

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user'] = $username;
        $_SESSION['DOB'] = $DOB;
        
        $_SESSION['userReg'] = '1';

        exit('Success!');
?>  