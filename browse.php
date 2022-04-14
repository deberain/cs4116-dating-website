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
                        <a class="nav-link" href="#">Browse </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="convos.php">Chats </a>
                    </li>
                    <?php
                    if ($_SESSION['user_type'] == 1) {
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
        <div class="modal fade" id="reportUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Why do you wish to report this user?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group dark-border">
                            <textarea maxlength="500" class="form-control" id="report-user-textarea" rows="8"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="reportUser">Report User</button>
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <div class="browse-grid-container">
        <div class="filterOptionsContainer">
            <h4 class="text-white">Filtering Options</h4>
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

            <br />

            <h5 class="text-white">Interests Options</h5>
            <div id="InterestsFilter">

            </div>

        </div>

        <div class="usersContainer">

        </div>
    </div>



    <script>
        var profiles = [];
        var profilesDisplayed = [];
        var excludeIDs = [];
        var userMatches = [];
        var selectedinterests = [];

        const currentUserID = <?php echo json_encode($_SESSION["user_id"]); ?>;

        const currentUserPref = <?php echo json_encode($_SESSION["pref"]); ?>;

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

                clearUsersContainer();

                filterProfiles();

                createProfileCards();
            });

            $("#maxAgeFilter").on('input', function() {
                $('#maxAgeVal').html($('#maxAgeFilter').val())

                $('#minAgeFilter').attr({
                    "max": $('#maxAgeFilter').val()
                });

                clearUsersContainer();

                filterProfiles();

                createProfileCards();
            });

            $.ajax({
                type: "GET",
                url: "config/get_interests.php",
                async: false
            }).done(function(res) {
                var interestsList = JSON.parse(res);

                const InterestsFilter = document.getElementById("InterestsFilter");

                for (var i = 0; i < interestsList.length; i++) {
                    var opt = interestsList[i];

                    var checkboxContainer = document.createElement("div");
                    checkboxContainer.className = "form-check";

                    var checkbox = document.createElement("input");
                    checkbox.className = "form-check-input interest-filter-option";
                    checkbox.type = "checkbox";
                    checkbox.value = opt["interest_name"];
                    checkbox.id = opt["interest_id"] + "CheckBox";
                    checkbox.onclick = function(event) {

                        if (this.checked) {
                            selectedinterests.push(this.value);
                        } else {
                            var index = selectedinterests.indexOf(this.value);
                            if (index !== -1) {
                                selectedinterests.splice(index, 1);
                            }
                        }

                        clearUsersContainer();

                        filterProfiles();

                        createProfileCards();
                    };

                    checkboxContainer.appendChild(checkbox);

                    var checkboxLabel = document.createElement("label");
                    checkboxLabel.className = "form-check-label";
                    checkboxLabel.for = opt["interest_id"] + "CheckBox";
                    checkboxLabel.innerText = opt["interest_name"];

                    checkboxContainer.appendChild(checkboxLabel);

                    InterestsFilter.appendChild(checkboxContainer);
                }
            });

            $.ajax({
                type: "GET",
                url: "config/get_profiles.php",
                async: false
            }).done(function(res) {
                profiles = JSON.parse(res);
            });

            console.log(profiles);


            $.ajax({
                type: "GET",
                url: "config/get_user_matches.php",
                async: false
            }).done(function(res) {
                userMatches = JSON.parse(res);

                console.log(userMatches);
            });

            userMatches.forEach(function(match) {
                if (match["user_one_id"] === currentUserID) {
                    excludeIDs.push(match["user_two_id"]);
                } else {
                    excludeIDs.push(match["user_one_id"]);
                }
            });

            filterProfiles();

            createProfileCards();

            console.log("Profiles being displayed: ");
            console.log(profilesDisplayed);


        });

        function showAgeOptions() {
            var checkbox = document.getElementById('filterByAge');
            var aOptions = document.getElementById('ageOptions');

            if (checkbox.checked) {
                aOptions.style.display = "block";
            } else {
                aOptions.style.display = "none";

                clearUsersContainer();

                filterProfiles();

                createProfileCards();
            }
        }

        function filterProfiles() {
            // FILTER PROFILES TO BE DISPLAYED
            profilesDisplayed = [];

            for (var i = 0; i < profiles.length; i++) {
                var profile = profiles[i];

                if (profile["age"] === undefined) {
                    var userDOB = new Date(profile["date_of_birth"]);

                    var ageDifMs = Date.now() - userDOB;
                    var ageDate = new Date(ageDifMs);
                    profile["age"] = Math.abs(ageDate.getUTCFullYear() - 1970);
                }

                if (excludeIDs.includes(profile["user_id"])) {
                    continue;
                }

                if (profile["banned"] === "1") {
                    continue;
                }

                if (profile["is_user_blocked"] === "true") {
                    continue;
                }

                if (currentUserPref !== "Both") {
                    if (currentUserPref === "Male" && profile["sex"] === "Female") {
                        continue;
                    } else if (currentUserPref === "Female" && profile["sex"] === "Male") {
                        continue;
                    }
                }

                var ageCheckbox = document.getElementById('filterByAge');


                if (ageCheckbox.checked) {
                    var minAge = $('#minAgeFilter').val()
                    var maxAge = $('#maxAgeFilter').val()

                    var currentAge = profile["age"];

                    if (currentAge < minAge || currentAge > maxAge) {
                        continue;
                    }
                }

                if (selectedinterests.length > 0) {
                    var user_interests = profile["interests"];
                    var interest_names = [];
                    var ignoreProfile = false;

                    for (let j = 0; j < user_interests.length; j++) {
                        var interest = user_interests[j]["interest_name"];

                        interest_names.push(interest);
                    }

                    for (let j = 0; j < selectedinterests.length; j++) {
                        var selectedinterest = selectedinterests[j];

                        if (!interest_names.includes(selectedinterest)) {
                            ignoreProfile = true;
                            break;
                        }
                    }

                    if (ignoreProfile) {
                        continue;
                    }
                }



                profilesDisplayed.push(profile);
            }
        }

        function clearUsersContainer() {
            const usersContainer = document.getElementsByClassName("usersContainer")[0];

            while (usersContainer.firstChild) {
                usersContainer.removeChild(usersContainer.lastChild);
            }
        }

        function createProfileCards() {
            const usersContainer = document.getElementsByClassName("usersContainer")[0];

            for (var i = 0; i < profilesDisplayed.length; i++) {
                var profile = profilesDisplayed[i];

                var card = document.createElement("div");
                card.className = "card card-block mx-2 profile-card";

                var image = document.createElement("img");
                image.className = "card-img-top";
                image.setAttribute("src", profile["picture"]);
                image.setAttribute("alt", "Profile Picture");
                image.setAttribute("onerror", "this.onerror=null;this.src='assets/default_profile_image.png';");

                card.appendChild(image);

                var cardbody = document.createElement("div");
                cardbody.className = "card-body";

                var username = document.createElement("h5");
                username.className = "card-title";
                username.innerHTML = profile["display_name"];
                cardbody.appendChild(username);

                var space = document.createElement("p");
                cardbody.appendChild(space);

                var gender = document.createElement("h6");
                gender.className = "card-title";
                gender.innerHTML = profile["sex"];
                gender.style = "font-size:15px";
                cardbody.appendChild(gender);

                var agelocation = document.createElement("p");
                agelocation.className = "card-text age-location";

                agelocation.innerHTML = profile["age"].toString() + " - " + profile["location"];
                cardbody.appendChild(agelocation);

                var line = document.createElement("hr");
                cardbody.appendChild(line);

                var bio = document.createElement("p");
                bio.className = "card-text";
                bio.innerHTML = profile["bio"];
                cardbody.appendChild(bio);

                card.append(cardbody);

                var cardbuttons = document.createElement("div");
                cardbuttons.className = "profile-card-btns";

                var likebutton = document.createElement("a");
                likebutton.setAttribute("href", "#");

                if (profile["is_user_liked"] === "true") {
                    likebutton.className = "btn btn-secondary profile-card-btns-decline";
                    likebutton.innerHTML = "Undo";
                } else {
                    likebutton.className = "btn btn-primary profile-card-btns-decline";
                    likebutton.innerHTML = "Like";
                }
                //Dropdown Menu Button Group
                var dropDownMenuButtonGroup = document.createElement("div");
                dropDownMenuButtonGroup.className = "btn-group profile-card-btns-dropdown";

                //Dropdown Menu Button
                var dropDownMenuButton = document.createElement("a");
                dropDownMenuButton.className = "btn dropdown-toggle btn-primary";
                dropDownMenuButton.setAttribute("data-toggle", "dropdown");
                dropDownMenuButton.setAttribute("href", "#");

                //Hamburger
                var hamburger = document.createElement("i");
                hamburger.className = "fa-solid fa-bars";
                dropDownMenuButton.appendChild(hamburger);

                //Dropdown Menu
                var dropDownMenu = document.createElement("ul");
                dropDownMenu.className = "dropdown-menu dropdown";


                //list items
                var listItemOne = document.createElement("li");
                var listItemTwo = document.createElement("li");;
                var listItemOneHref = document.createElement("a");
                var listItemTwoHref = document.createElement("a");

                listItemOneHref.setAttribute("user_id", profile['user_id']);
                listItemOneHref.setAttribute("href", "#");
                listItemTwoHref.setAttribute("user_id", profile['user_id']);
                listItemTwoHref.setAttribute("href", "#");
                listItemOneHref.className = "card-menu-btn";
                listItemTwoHref.className = "card-menu-btn";

                const userType = <?php echo json_encode($_SESSION['user_type']); ?>;
                if (userType == 1) {
                    listItemOneHref.innerHTML = "Ban User";
                    listItemOneHref.setAttribute("action", "ban");
                    listItemTwoHref.innerHTML = "Issue Warning";
                    listItemTwoHref.setAttribute("action", "warn");
                } else {
                    listItemOneHref.innerHTML = "Report User";
                    listItemOneHref.setAttribute("action", "report");
                    listItemTwoHref.innerHTML = "Block User";
                    listItemTwoHref.setAttribute("action", "block");
                }

                listItemOne.append(listItemOneHref);
                listItemTwo.append(listItemTwoHref);

                dropDownMenu.append(listItemOne);
                dropDownMenu.append(listItemTwo);

                dropDownMenuButtonGroup.append(dropDownMenuButton);
                dropDownMenuButtonGroup.append(dropDownMenu);

                likebutton.id = "like" + profile["user_id"];
                likebutton.onclick = function(event) {

                    var buttonPressed = document.getElementById(this.id);
                    var target = this.id.substring(4);

                    if (buttonPressed.innerHTML === "Undo") {
                        $.ajax({
                            type: "POST",
                            url: "config/unlike_user.php",
                            data: {
                                target_id: target
                            },
                            async: true
                        }).done(function(res) {
                            var result = String(res).trim();
                            if (result === "Success!") {
                                console.log("disliked user id " + target);
                                buttonPressed.className = "btn btn-primary profile-card-btns-decline";
                                buttonPressed.innerHTML = "Like";
                            } else {
                                alert("An error has occurred");
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "config/like_user.php",
                            data: {
                                target_id: target
                            },
                            async: true
                        }).done(function(res) {
                            var result = String(res).trim();
                            if (result === "Success!") {
                                console.log("Liked user id " + target);
                                buttonPressed.className = "btn btn-secondary profile-card-btns-decline";
                                buttonPressed.innerHTML = "Undo";
                            } else if (result === "Already Liked User") {
                                alert("You have already liked this user");
                            } else {
                                alert("An error has occurred");
                            }
                        });
                    }
                }
                cardbuttons.appendChild(likebutton);

                //Append Dropdown Menu
                cardbuttons.appendChild(dropDownMenuButtonGroup);

                card.appendChild(cardbuttons);


                usersContainer.appendChild(card);
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


        //On card menu item click
        $(document).on('click', 'a.card-menu-btn', function(e) {
            e.preventDefault();
            $action = $(this).attr('action');
            $userId = $(this).attr('user_id');

            const profileCard = this.parentElement.parentElement.parentElement.parentElement.parentElement;
            if ($action == "report") {
                $("#reportUserModal").modal('show');
            } else if ($action == "block") {
                $.ajax({
                    type: "POST",
                    url: "handlers/admin.php",
                    data: {
                        func: 'blockUser',
                        userId: $userId
                    },
                    async: true
                }).done(function(res) {
                    var result = String(res).trim();
                    if (result == "User Blocked Successfully") {
                        $('<div class="alert alert-success"><strong>' + result + '</strong></div>').css({
                            "position": "fixed",
                            "top": 15,
                            "left": 15,
                            "z-index": 10000,
                            "text-align": "center",
                            "font-weight": "bold"
                        }).hide().appendTo("body").fadeIn(1000);

                        profileCard.parentElement.removeChild(profileCard);

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
            } else if ($action == "ban") {
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

                        profileCard.parentElement.removeChild(profileCard);

                        $('.alert').fadeOut(1000);
                    }
                });
            } else if ($action == "warn") {
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
            }
        });

        $(document).on('click', '#reportUser', function(e) {
                    if ($('#report-user-textarea').val().length == 0) {
                        $('<div class="alert alert-danger"><strong>Textarea can\'t be empty</strong></div>').css({
                            "position": "fixed",
                            "top": 15,
                            "left": 15,
                            "z-index": 10000,
                            "text-align": "center",
                            "font-weight": "bold"
                        }).hide().appendTo("body").fadeIn(1000);
                        $('.alert').fadeOut(1000);
                    } else {
                        
                        $.ajax({
                            type: "POST",
                            url: "handlers/admin.php",
                            data: {
                                func: 'reportUser',
                                userId: $userId,
                                incidentDescription: $('#report-user-textarea').val()
                            },
                            async: true
                        }).done(function(res) {
                            var result = String(res).trim();
                            if (result == "User Reported Successfully") {
                                $('<div class="alert alert-success"><strong>' + result + '</strong></div>').css({
                                    "position": "fixed",
                                    "top": 15,
                                    "left": 15,
                                    "z-index": 10000,
                                    "text-align": "center",
                                    "font-weight": "bold"
                                }).hide().appendTo("body").fadeIn(1000);
                                $('.alert').fadeOut(1000);
                                
                                $('#report-user-textarea').val('');
                                $('.dropdown-btn').next('ul').hide();
                                $("#reportUserModal").modal('hide');
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
    </script>
</body>

</html>