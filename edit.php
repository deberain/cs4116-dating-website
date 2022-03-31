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
    <title>Cupid.com | Edit Profile</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">
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
                        <a class="nav-link" href="browse.php">Browse </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="convos.php">Chats </a>
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
                            <img class="card-img-top" id="currentUserPic" src="assets/default_profile_image.png" alt="profile image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $_SESSION['display_name'] ?></h5>
                                <p class="card-text age-location" id="age-location"></p>
                                <p class="card-text profile-card-bio"><?php echo $_SESSION['bio'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </header>


    <div class="edit-profile-form-container">
        <div class="edit-profile-form">
            <div class="edit-profile-options">
                <h2><u>Edit Profile</u></h2>

                <form name="editForm" id="editForm" method="post" action="config/edit_profile.php" enctype="multipart/form-data">
                    <div class="edit-profile-image">
                        <img class="profile-image" id="editCurrentUserPic" src="assets/default_profile_image.png" alt="profile image"> <br />
                        <input type="file" accept="image/*" id="uploadProfileImage" name="image" value="Upload Image">
                    </div>
                    <div class="edit-profile-display-name">
                        <label for="EditDisplayName" class="mb-0">Display Name:</label>
                        <input type="text" class="form-control text-small" id="EditDisplayName" aria-describedby="Name" placeholder="Display Name" name="display_name">
                    </div>
                    <div class="edit-profile-location">
                        <label for="EditLocation" class="mb-0">Location:</label>
                        <select class="form-control text-small" id="EditLocation" name="location">
                            <option>Antrim</option>
                            <option>Armagh</option>
                            <option>Carlow</option>
                            <option>Cavan</option>
                            <option>Clare</option>
                            <option>Cork</option>
                            <option>Derry</option>
                            <option>Donegal</option>
                            <option>Down</option>
                            <option>Dublin</option>
                            <option>Fermanagh</option>
                            <option>Galway</option>
                            <option>Kerry</option>
                            <option>Kildare</option>
                            <option>Kilkenny</option>
                            <option>Laois</option>
                            <option>Leitrim</option>
                            <option>Limerick</option>
                            <option>Longford</option>
                            <option>Louth</option>
                            <option>Mayo</option>
                            <option>Meath</option>
                            <option>Monaghan</option>
                            <option>Offaly</option>
                            <option>Roscommon</option>
                            <option>Sligo</option>
                            <option>Tipperary</option>
                            <option>Tyrone</option>
                            <option>Waterford</option>
                            <option>Westmeath</option>
                            <option>Wexford</option>
                            <option>Wicklow</option>
                        </select>
                    </div>
                    <div class="edit-profile-sex">
                        <label for="EditSex" class="mb-0">Sex:</label>
                        <select class="form-control text-small" id="EditSex" name="sex">
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="edit-profile-pref-sex">
                        <label for="EditPrefSex" class="mb-0">Preferred Sex:</label>
                        <select class="form-control text-small" id="EditPrefSex" name="pref">
                            <option>Male</option>
                            <option>Female</option>
                            <option>Both</option>
                        </select>
                    </div>
                    <div class="form-row justify-content-around mb-2">
                        <div class="col-10">
                            <label for="SelectInterests" class="mb-0"></label>Your Interests:</label>
                            <select class="selectpicker" id="SelectInterests" multiple data-live-search="true" name="interests">

                            </select>
                        </div>
                    </div>
                    <div class="edit-profile-bio">
                        <label for="EditBio">Bio:</label>
                        <textarea class="form-control" id="EditBio" rows="4" name="bio"><?php echo $_SESSION['bio'] ?></textarea>
                    </div>

                    <br>
                    <input type="submit" class="btn btn-purple px-3" id="reg" value="Save Changes" />
                </form>
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

            $.ajax({
                type: "GET",
                url: "config/get_interests.php",
                async: false
            }).done(function(res) {
                console.log(res);
                interestsList = JSON.parse(res);

                const select = document.getElementById("SelectInterests");

                for (var i = 0; i < interestsList.length; i++) {
                    var opt = interestsList[i];
                    var el = document.createElement("option");
                    el.textContent = opt["interest_name"];
                    el.value = opt["interest_name"];
                    select.appendChild(el);
                }
            });

            var currentUserPic = <?php echo json_encode($_SESSION['photo']); ?>;
            var currentDisplayName = <?php echo json_encode($_SESSION['display_name']); ?>;
            var currentLocation = <?php echo json_encode($_SESSION['location']); ?>;
            var currentSex = <?php echo json_encode($_SESSION['sex']); ?>;
            var currentPref = <?php echo json_encode($_SESSION['pref']); ?>;
            var currentBio = <?php echo json_encode($_SESSION['bio']); ?>;


            if (currentUserPic !== null) {
                $("#currentUserPic").attr({
                    "src": currentUserPic
                })

                $("#editCurrentUserPic").attr({
                    "src": currentUserPic
                })
            }

            if (currentDisplayName !== null) {
                $("#EditDisplayName").attr({
                    "value": currentDisplayName
                })
            }

            Array.from(document.querySelector("#EditLocation").options).forEach(function(option) {
                if (option.text === currentLocation) {
                    option.selected = true;
                }
            });

            Array.from(document.querySelector("#EditSex").options).forEach(function(option) {
                if (option.text === currentSex) {
                    option.selected = true;
                }
            });

            Array.from(document.querySelector("#EditPrefSex").options).forEach(function(option) {
                if (option.text === currentPref) {
                    option.selected = true;
                }
            });

            const uploadImage = document.querySelector("#uploadProfileImage");
            var uploadedImage = "";

            uploadImage.addEventListener("change", function() {
                const reader = new FileReader();
                reader.addEventListener("load", () => {
                    uploadedImage = reader.result;

                    console.log(uploadedImage);
                    $("#editCurrentUserPic").attr({
                        "src": uploadedImage
                    })
                });
                reader.readAsDataURL(this.files[0]);
            });

            var userBirth = <?php echo json_encode($_SESSION['DOB']); ?>;

            var userLocation = <?php echo json_encode($_SESSION['location']); ?>;

            userBirth = new Date(userBirth);

            var ageDifMs = Date.now() - userBirth;
            var ageDate = new Date(ageDifMs);
            var age = Math.abs(ageDate.getUTCFullYear() - 1970);

            document.getElementById("age-location").innerHTML = age.toString() + " - " + userLocation.toString();

            // submit profile details
            $("#editForm").on('submit', function(e) {
                e.preventDefault();

                var displayName = $("#InputFullName").val();
                var sex = $("#SelectSex").val();
                var pref = $("#SelectPrefSex").val();
                var location = $("#InputLocation").val();
                var bio = $("#InputBio").val();

                var chosenInterests = $('.selectpicker').val();
                console.log(chosenInterests);


                if (displayName === "") {
                    alert("Please enter your desired display name.");
                    return false;
                }

                if (location === "Choose...") {
                    alert("Please enter your location.");
                    return false;
                }

                if (sex === "Choose...") {
                    alert("Please enter your sex.");
                    return false;
                }

                if (pref === "Choose...") {
                    alert("Please enter your sexual preference.");
                    return false;
                }

                
                if(chosenInterests.length === 0) {
                    alert("Please select your interests");
                    return false;
                } 

                $.ajax({
                    type: "POST",
                    url: "config/edit_profile.php",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false
                }).done(function(res) {
                    var result = String(res).trim();
                    console.log(result);
                    if (result === "Success!") {
                        document.location.href = "edit.php";
                    } else {
                        alert("An error ahs occurred");
                    }

                });

            });

        });
    </script>
</body>