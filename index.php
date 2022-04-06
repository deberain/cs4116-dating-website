<?php
session_start();
//If Already Logged IN
if (isset($_SESSION['LoggedIn'])) {
    header('Location: home.php');
    exit();
}
if (isset($_POST['login'])) {
    include('config/connection.php');
    $username = $_POST['username'];
    $password = $_POST['password'];


    //to prevent from mysqli injection  
    $username = stripcslashes($username);
    $password = stripcslashes($password);
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);

    $encryptedPassword = md5($password);

    $sql = "select * from `users` where username = '$username' and password = '$encryptedPassword'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $userId = $row['user_id'];
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $_SESSION['LoggedIn'] = '1';
        $_SESSION['user'] = $username;
        $_SESSION['user_id'] = $userId;
        $_SESSION['DOB'] = $row['date_of_birth'];
        $_SESSION['user_type'] = $row['user_type'];

        $sql = "select * from `profiles` where user_id = '$userId'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $_SESSION['display_name'] = stripcslashes($row['display_name']);
        $_SESSION['location'] = $row['location'];
        $_SESSION['sex'] = $row['sex'];
        $_SESSION['pref'] = $row['preferred_sex'];
        $_SESSION['bio'] = stripcslashes($row['bio']);
        $_SESSION['photo'] = $row['picture'];

        $sql = "select interest_id from `user_interests` where user_id = '$userId'";
        $result = mysqli_query($con, $sql);
        $data = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row['interest_id'];
        }

        $_SESSION['interests'] = $data;

        exit("Login Successful");
    } else {
        exit("Login Failed.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupid.com | Login</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">

    <script defer src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="welcome-container">
        <div class="welcome-text text-center">
            <h1>Welcome to Cupid.com</h1>
            <img defer class="rounded" src="assets/Pink_Heart.png" alt="pink heart" width="275" height="250" />
            <p>
            <h3>Looking for Love? You came to the right place!</h3>
        </div>

        <div id="login">
            <div class="login-form">
                <p>Login or register from here to access.</p>
                <form name="loginForm" autocomplete="on">
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" class="form-control" placeholder="User Name" id="InputUsername" name="username">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" id="InputPassword" name="password">
                        <input type="checkbox" onclick="showPassword()"> Show Password
                    </div>
                    <p id="FailedLogin"></p>
                    <button type="button" class="btn btn-purple" id="Login">Login</button>
                    <button type="button" onclick="document.location.href='register.html';" class="btn btn-secondary">Register</button>
                </form>
            </div>
        </div>
    </div>
    <section class="wv">
        <div class="welcome-wave">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            console.log('Page Ready');
            $("#Login").on('click', function() {
                var username = $("#InputUsername").val();
                var password = $("#InputPassword").val();


                if (username == "" || password == "") {
                    $("#FailedLogin").html("<font color='red'>Please enter both your Username and Password</font>");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "index.php",
                        data: {
                            login: 1,
                            username: username,
                            password: password,
                        },
                        dataType: "text"
                    }).done(function(res) {
                        var result = String(res).trim();
                        console.log(result);
                        if (result == "Login Failed.") {
                            $("#FailedLogin").html("<font color='red'>Invalid Username or Password</font>");
                            return false;
                        } else {
                            document.location.href = "home.php";
                        }

                    });
                }
            });
        });

        function showPassword() {
            var pWord = document.getElementById("InputPassword");
            if (pWord.type === "password") {
                pWord.type = "text";
            } else {
                pWord.type = "password";
            }
        }
    </script>
</body>

</html>