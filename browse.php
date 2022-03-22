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
    <title>Cupid.com | Browse Users</title>

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
                        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Browse </a>
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
                            <img class="card-img-top" id="currentUserPic" src="images/default_profile_image.png" alt="profile image">
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

    <div class="browse-grid-container">
        <div class="filterOptionsContainer">
            <h5 class="text-white">Age and Distance options</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="filterByAge" onclick="showAgeOptions()">
                <label class="form-check-label" for="filterByAge">
                    Filter By Age
                </label>
            </div>
            <div id="ageOptions">
                <br />
                <div class="mb-3">
                    <label for="minAgeFilter" class="form-label">Minimum Age</label>
                    <input type="range" class="form-control" id="minAgeFilter" value="18" min="18" max="100">
                    <span class="rangeval" id="minAgeVal">18</span>
                </div>
                <div class="mb-3">
                    <label for="maxAgeFilter" class="form-label">Maximum Age</label>
                    <input type="range" class="form-control" id="maxAgeFilter" value="100" min="18" max="100">
                    <span class="rangeval" id="maxAgeVal">100</span>
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="filterByDistance" onclick="showDistanceOptions()">
                <label class="form-check-label" for="filterByDistance">
                    Filter By Distance
                </label>
            </div>
            <div class="mb-3" id="distanceOptions">
                <br />
                <label for="maxDistance" class="form-label">Maximum Distance</label>
                <input type="range" class="form-control" id="maxDistance" value="1000" min="0" step="5" max="1000">
                <span class="rangeval" id="maxDistanceVal">1000 km</span>
            </div>

            <br />

            <h5 class="text-white">Interests Options</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="SportCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="SportCheckbox">Sport</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="MusicCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="MusicCheckbox">Music</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="TVCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="TVCheckbox">TV</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="MoviesCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="MoviesCheckbox">Movies</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="DancingCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="DancingCheckbox">Dancing</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="VideoGamesCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="VideogamesCheckbox">Video Games</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="CookingCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="CookingCheckbox">Cooking</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="PhotographyCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="PhotographyCheckbox">Photography</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="AnimalsCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="AnimalsCheckbox">Animals</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="AnimeCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="AnimeCheckbox">Anime</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="FashionCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="FashionCheckbox">Fashion</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="ScienceCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="ScienceCheckbox">Science</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="BoardGamesCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="BoardGamesCheckbox">Board Games</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="ArtCheckbox" onclick="updateFilter()">
                <label class="form-check-label" for="ArtCheckbox">Art</label>
            </div>

        </div>

        <div class="usersContainer">

            <div class="card card-block mx-2 profile-card">
                <img class="card-img-top" src="images/default_profile_image.png" alt="profile image">
                <div class="card-body">
                    <h5 class="card-title">User Name</h5>
                    <p class="card-text age-location">Age - Location</p>
                    <p class="card-text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quod quo veritatis fuga nisi illo est aliquid doloremque repellat ex nihil.</p>
                </div>
                <div class="profile-card-btns">
                    <a href="#" class="btn btn-primary profile-card-btns-decline">Like</a>
                </div>
            </div>
            <div class="card card-block mx-2 profile-card">
                <img class="card-img-top" src="images/default_profile_image.png" alt="profile image">
                <div class="card-body">
                    <h5 class="card-title">User Name</h5>
                    <p class="card-text age-location">Age - Location</p>
                    <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam odio praesentium laborum magni, eaque earum minima numquam vero esse deleniti.</p>
                </div>
                <div class="profile-card-btns">
                    <a href="#" class="btn btn-primary profile-card-btns-decline">Like</a>
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
            });

            $("#EditProfile").on('click', function() {
                window.location = 'edit.php';
            })

            $("#minAgeFilter").on('input', function() {
                $('#minAgeVal').html($('#minAgeFilter').val())

                $('#maxAgeFilter').attr({
                    "min": $('#minAgeFilter').val()
                });
            });

            $("#maxAgeFilter").on('input', function() {
                $('#maxAgeVal').html($('#maxAgeFilter').val())

                $('#minAgeFilter').attr({
                    "max": $('#maxAgeFilter').val()
                });
            });

            $("#maxDistance").on('input', function() {
                $('#maxDistanceVal').html($('#maxDistance').val() + ' km')
            });


        });

        function showAgeOptions() {
            var checkbox = document.getElementById('filterByAge');
            var aOptions = document.getElementById('ageOptions');

            if (checkbox.checked) {
                aOptions.style.display = "block";
            } else {
                aOptions.style.display = "none";
            }
        }

        function showDistanceOptions() {
            var checkbox = document.getElementById('filterByDistance');
            var dOptions = document.getElementById('distanceOptions');

            if (checkbox.checked) {
                dOptions.style.display = "block";
            } else {
                dOptions.style.display = "none";
            }
        }

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

</html>