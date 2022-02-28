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
  <title>Cupid.com</title>

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
            <a class="nav-link" href="#">Profile</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="pending-connections">
    <h2>Pending connections</h2>
    <div class="d-flex flex-row flex-nowrap overflow-auto">
      <div class="card card-block mx-2 profile-card" style="width: 300px; height: 400px;">
        <img class="card-img-top" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" alt="profile image">
        <div class="card-body">
          <h5 class="card-title">User Name</h5>
          <p class="card-text age-location">Age - Location</p>
          <p class="card-text">User's Bio</p>
          <div class="profile-card-btns">
            <a href="#" class="btn btn-primary">Accept</a>
            <a href="#" class="btn btn-secondary">Decline</a>
          </div>
        </div>
      </div>
      <div class="card card-block mx-2 profile-card" style="width: 300px; height: 400px;">
        <img class="card-img-top" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" alt="profile image">
        <div class="card-body">
          <h5 class="card-title">User Name</h5>
          <p class="card-text age-location">Age - Location</p>
          <p class="card-text">User's Bio</p>
          <div class="profile-card-btns">
            <a href="#" class="btn btn-primary">Accept</a>
            <a href="#" class="btn btn-secondary">Decline</a>
          </div>
        </div>
      </div>
      <div class="card card-block mx-2 profile-card" style="width: 300px; height: 400px;">
        <img class="card-img-top" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" alt="profile image">
        <div class="card-body">
          <h5 class="card-title">User Name</h5>
          <p class="card-text age-location">Age - Location</p>
          <p class="card-text">User's Bio</p>
          <div class="profile-card-btns">
            <a href="#" class="btn btn-primary">Accept</a>
            <a href="#" class="btn btn-secondary">Decline</a>
          </div>
        </div>
      </div>
    </div>

    <div class="welcome-wave">
      <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
      </svg>
    </div>
  </div>

  <div class="suggested-users">
    <h2>Suggested Users</h2>
    <div class="d-flex flex-row flex-nowrap overflow-auto">
      <div class="card card-block mx-2 profile-card" style="width: 300px; height: 400px;">
        <img class="card-img-top" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" alt="profile image">
        <div class="card-body">
          <h5 class="card-title">User Name</h5>
          <p class="card-text age-location">Age - Location</p>
          <p class="card-text">User's Bio</p>
          <div class="profile-card-btns">
            <a href="#" class="btn btn-primary">Like</a>
          </div>
        </div>
      </div>
      <div class="card card-block mx-2 profile-card" style="width: 300px; height: 400px;">
        <img class="card-img-top" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" alt="profile image">
        <div class="card-body">
          <h5 class="card-title">User Name</h5>
          <p class="card-text age-location">Age - Location</p>
          <p class="card-text">User's Bio</p>
          <div class="profile-card-btns">
            <a href="#" class="btn btn-primary">Like</a>
          </div>
        </div>
      </div>
      <div class="card card-block mx-2 profile-card" style="width: 300px; height: 400px;">
        <img class="card-img-top" src="https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png" alt="profile image">
        <div class="card-body">
          <h5 class="card-title">User Name</h5>
          <p class="card-text age-location">Age - Location</p>
          <p class="card-text">User's Bio</p>
          <div class="profile-card-btns">
            <a href="#" class="btn btn-primary">Like</a>
          </div>
        </div>
      </div>
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