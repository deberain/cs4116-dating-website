<?php
session_start();

//If Already Logged IN
if (!isset($_SESSION['LoggedIn'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['logout'])) {
    unset($_SESSION['LoggedIn']);
    session_destroy();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupid.com | Chats</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">

    <script defer src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Cupid</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="browse.php">Browse </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="chats.php">Chats </a>
                    </li>
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
                            <img class="card-img-top" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" alt="profile image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $_SESSION['display_name'] ?></h5>
                                <p class="card-text age-location"><?php echo $_SESSION['age'] ?> - <?php echo $_SESSION['location'] ?></p>
                                <p class="card-text profile-card-bio"><?php echo $_SESSION['bio'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Edit Profile</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <div class="chats-grid-container">
        <div class="chats-list">
            <div class="chat-thumb">
                <div class="chat-thumb-profile-pic-container">
                    <img class="chat-thumb-profile-pic" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" width="60" height="60" alt="profile image">
                </div>
                <div class="chat-preview">
                    <div class="chat-preview-username">
                        User Name
                    </div>
                    <div class="chat-preview-content">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos rerum reprehenderit maiores voluptatum consectetur optio in nulla quisquam ut accusantium. Ullam molestiae nulla distinctio possimus? Voluptas beatae asperiores quos quaerat.
                    </div>
                </div>
            </div>
            <div class="chat-thumb">
                <div class="chat-thumb-profile-pic-container">
                    <img class="chat-thumb-profile-pic" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" width="60" height="60" alt="profile image">
                </div>
                <div class="chat-preview">
                    <div class="chat-preview-username">
                        User Name
                    </div>
                    <div class="chat-preview-content">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos rerum reprehenderit maiores voluptatum consectetur optio in nulla quisquam ut accusantium. Ullam molestiae nulla distinctio possimus? Voluptas beatae asperiores quos quaerat.
                    </div>
                </div>
            </div>
            <div class="chat-thumb">
                <div class="chat-thumb-profile-pic-container">
                    <img class="chat-thumb-profile-pic" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" width="60" height="60" alt="profile image">
                </div>
                <div class="chat-preview">
                    <div class="chat-preview-username">
                        User Name
                    </div>
                    <div class="chat-preview-content">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos rerum reprehenderit maiores voluptatum consectetur optio in nulla quisquam ut accusantium. Ullam molestiae nulla distinctio possimus? Voluptas beatae asperiores quos quaerat.
                    </div>
                </div>
            </div>
        </div>

        <div class="chat-body">

        </div>

        <div class="chat-profile">

        </div>
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
            })
        });
    </script>
</body>

</html>