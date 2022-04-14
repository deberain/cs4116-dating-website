<?php
session_start();

//If Already Logged IN
if (!isset($_SESSION['LoggedIn'])|| $_SESSION['user_type'] != 1) {
  header('Location: index.php');
  exit();
}

if (isset($_POST['logout'])) {
  unset($_SESSION['LoggedIn']);
  session_destroy();
  exit();
}
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupid.com | Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f07dcc15fc.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="home.php">Cupid</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="browse.php">Browse </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="convos.php">Chats </a>
                    </li>
                    <?php
                        if($_SESSION['user_type']==1){
                        echo '<li class="nav-item active">
                        <a class="nav-link" href="admin.php">Admin</a>
                        </li>';
                        }
                    ?>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#" id="Logout">Logout</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#profileModal"><?php echo $_SESSION['user'] ?></a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $_SESSION['user'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-block profile-card">
                            <img class="card-img-top" id="currentUserPic" src="assets/default_profile_image.png" alt="profile image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $_SESSION['display_name'] ?></h5>
                                <p class="card-text age-location" id="age-location"></p>
                                <p class="card-text profile-card-bio"><?php echo $_SESSION['bio'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="EditProfile">Edit Profile</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div id="reported-users-section" class="container-fluid p-5">
        <br>
        <h1 class="text-light"><b>Reported Users</b></h1>
        <br>

        
    </div>
    <script>
        $(document).ready(function() {
            $("#Logout").on('click', function() {
                $.ajax({
                    method: 'POST',
                    url: 'home.php',
                    data: {
                        logout: 1
                    },
                    dataType: 'text'
                }).done(function() {
                    window.location = 'index.php';
                })
            });

            $("#EditProfile").on('click', function() {
                window.location = 'edit.php';
            })

            $.ajax({
                    method: 'POST',
                    url: 'handlers/admin.php',
                    data: {
                        func: 'getReportedUsersTable'
                    },
                    success: function(res){  
                        try {
                            let tableData = JSON.parse(res);
                            $('#reported-users-section').append(tableData.data);
                        } catch(e) {
                            $('<div class="alert alert-danger"><strong>An Error Occured Querying Database</strong></div>').css({
                            "position": "fixed",
                            "top": 15,
                            "left": 15,
                            "z-index": 10000,
                            "text-align": "center",
                            "font-weight": "bold"
                        }).hide().appendTo("body").fadeIn(1000);
                        $('.alert').fadeOut(1000);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        $('<div class="alert alert-danger"><strong>An Error Occured Querying Database: '+textStatus+'</strong></div>').css({
                            "position": "fixed",
                            "top": 15,
                            "left": 15,
                            "z-index": 10000,
                            "text-align": "center",
                            "font-weight": "bold"
                        }).hide().appendTo("body").fadeIn(1000);
                        $('.alert').fadeOut(1000);
                    } 
                });

                $(document).on("click",".admin-action",function() {
                    $action = $(this).attr('action');
                    if($action=='ban'){
                        $userId = $(this).attr('user-id');
                        $.ajax({
                            type: "POST",
                            url: "handlers/admin.php",
                            data: {
                                func: 'banUser',
                                userId: $userId,
                            },
                            async: true
                        }).done(function(res) {
                            var result = String(res).trim();
                            if (result == "User Banned Successfully") {
                                $('<div class="alert alert-success"><strong>' + result + '</strong></div>').css({
                                    "position": "fixed",
                                    "top": 15,
                                    "left": 15,
                                    "z-index": 10000,
                                    "text-align": "center",
                                    "font-weight": "bold"
                                }).hide().appendTo("body").fadeIn(1000);
                                $('.alert').fadeOut(1000);
                            } else {
                                $('<div class="alert alert-danger"><strong>' + result + '</strong></div>').css({
                                    "position": "fixed",
                                    "top": 15,
                                    "left": 15,
                                    "z-index": 10000,
                                    "text-align": "center",
                                    "font-weight": "bold"
                                }).hide().appendTo("body").fadeIn(1000);
                                $('.alert').fadeOut(1000);
                            }
                        });
                    }else if($action == 'warn'){
                        $userId = $(this).attr('user-id');
                        $.ajax({
                            type: "POST",
                            url: "handlers/admin.php",
                            data: {
                                func: 'warnUser',
                                userId: $userId,
                            },
                            async: true
                        }).done(function(res) {
                            var result = String(res).trim();
                            if (result == "User Warned Successfully") {
                                $('<div class="alert alert-success"><strong>' + result + '</strong></div>').css({
                                    "position": "fixed",
                                    "top": 15,
                                    "left": 15,
                                    "z-index": 10000,
                                    "text-align": "center",
                                    "font-weight": "bold"
                                }).hide().appendTo("body").fadeIn(1000);
                                $('.alert').fadeOut(1000);
                            } else {
                                $('<div class="alert alert-danger"><strong>' + result + '</strong></div>').css({
                                    "position": "fixed",
                                    "top": 15,
                                    "left": 15,
                                    "z-index": 10000,
                                    "text-align": "center",
                                    "font-weight": "bold"
                                }).hide().appendTo("body").fadeIn(1000);
                                $('.alert').fadeOut(1000);
                            }
                        });
                    }else if($action == 'remove'){
                        $reportId = $(this).attr('report-id');
                        $.ajax({
                            type: "POST",
                            url: "handlers/admin.php",
                            data: {
                                func: 'removeReportFromDatabase',
                                reportId: $reportId,
                            },
                            async: true
                        }).done(function(res) {
                            var result = String(res).trim();
                            if (result == "Report Removed From Database Successfully") {
                                location.reload();
                            } else {
                                $('<div class="alert alert-danger"><strong>' + result + '</strong></div>').css({
                                    "position": "fixed",
                                    "top": 15,
                                    "left": 15,
                                    "z-index": 10000,
                                    "text-align": "center",
                                    "font-weight": "bold"
                                }).hide().appendTo("body").fadeIn(1000);
                                $('.alert').fadeOut(1000);
                            }
                        });
                    }
                    
                });
        });
        var currentUserPic = <?php echo json_encode($_SESSION['photo']); ?>;

        if (currentUserPic !== null) {
            $("#currentUserPic").attr({
                "src": currentUserPic
            })
        }

        var userBirth = <?php echo json_encode($_SESSION['DOB']); ?>;

        var userLocation = <?php echo json_encode($_SESSION['location']); ?>;

        userBirth = new Date(userBirth);

        var ageDifMs = Date.now() - userBirth;
        var ageDate = new Date(ageDifMs);
        var age = Math.abs(ageDate.getUTCFullYear() - 1970);

        document.getElementById("age-location").innerHTML = age.toString() + " - " + userLocation.toString();
    </script>
</body>