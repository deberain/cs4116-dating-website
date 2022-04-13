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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/f07dcc15fc.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
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

  <div class="pending-connections">
    <h2>Pending connections</h2>
    <div class="d-flex flex-row flex-nowrap overflow-auto" id="pending-container">
      <div class="card card-block mx-2 profile-card" id="no-pending">
        <img class="card-img-top" src="assets/default_profile_image.png" alt="profile image">
        <div class="card-body">
          <p class="card-text profile-card-bio no-pending">No Pending Connections</p>
        </div>
        <div class="profile-card-btns">
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
    <div class="d-flex flex-row flex-nowrap overflow-auto" id="suggestions-container">
      <div class="card card-block mx-2 profile-card" id="no-suggestions">
        <img class="card-img-top" src="assets/default_profile_image.png" alt="profile image">
        <div class="card-body">
          <p class="card-text profile-card-bio no-pending">No Suggestions For Now</p>
        </div>
        <div class="profile-card-btns">

        </div>
      </div>
    </div>
  </div>

  <script>
    var profiles = [];
    var pendingConnections = [];
    var pendingProfiles = [];
    var suggestedUsers = [];
    var userMatches = [];
    var excludeIDs = [];
    var candidates = [];

    const currentUserID = <?php echo json_encode($_SESSION["user_id"]); ?>;

    const currentUserPref = <?php echo json_encode($_SESSION["pref"]); ?>;

    const currentUserInterests = <?php echo json_encode($_SESSION["interests"]); ?>;

    var currentUserAge;

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
        type: "GET",
        url: "config/get_profiles.php",
        async: false
      }).done(function(res) {
        profiles = JSON.parse(res);

        console.log(profiles);
      });

      $.ajax({
        type: "GET",
        url: "config/get_pending_connections.php",
        async: false
      }).done(function(res) {
        pendingConnections = JSON.parse(res);
      });

      for (let i = 0; i < pendingConnections.length; i++) {

        var profileID = pendingConnections[i];

        for (let j = 0; j < profiles.length; j++) {
          var profile = profiles[j];

          if (profile["user_id"] === profileID) {
            pendingProfiles.push(profile);
          }
        }
      }

      $.ajax({
        type: "GET",
        url: "config/get_user_matches.php",
        async: false
      }).done(function(res) {
        userMatches = JSON.parse(res);
      });

      userMatches.forEach(function(match) {
        if (match["user_one_id"] === currentUserID) {
          excludeIDs.push(match["user_two_id"]);
        } else {
          excludeIDs.push(match["user_one_id"]);
        }
      });

      console.log(pendingProfiles);

      // CREATE PROFILE CARDS FOR PENDING CONNECTIONS AND ADD THEM TO CONTAINER
      const pendingContainer = document.getElementById("pending-container");

      if (pendingProfiles.length > 0) {
        const noPending = document.getElementById("no-pending");
        noPending.style.display = "none";

        for (let i = 0; i < pendingProfiles.length; i++) {
          var profile = pendingProfiles[i];

          var card = document.createElement("div");
          card.className = "card card-block mx-2 profile-card";

          var image = document.createElement("img");
          image.className = "card-img-top";
          image.setAttribute("src", profile["picture"]);
          image.setAttribute("alt", "profile image");
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

          var userDOB = new Date(profile["date_of_birth"]);

          var ageDifMs = Date.now() - userDOB;
          var ageDate = new Date(ageDifMs);
          var age = Math.abs(ageDate.getUTCFullYear() - 1970);

          agelocation.innerHTML = age.toString() + " - " + profile["location"];
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

          var acceptbutton = document.createElement("a");
          acceptbutton.setAttribute("href", "#");
          acceptbutton.className = "btn btn-primary profile-card-btns-accept button-margin";
          acceptbutton.innerHTML = "Accept";

          acceptbutton.id = "accept" + profile["user_id"];
          acceptbutton.onclick = function(event) {

            var buttonPressed = document.getElementById(this.id);
            var target = this.id.substring(6);

            $.ajax({
              type: "POST",
              url: "config/accept_connection.php",
              data: {
                target_id: target
              },
              async: true
            }).done(function(res) {
              var result = String(res).trim();
              if (result === "Success!") {
                console.log("Connected with user id " + target);
                pendingContainer.removeChild(card);

                for (let i = 0; i < pendingProfiles.length; i++) {
                  var profile = pendingProfiles[i];

                  if (profile["user_id"] === target) {
                    pendingProfiles.splice(i, 1);
                  }
                }
                console.log(pendingProfiles);

                if (pendingProfiles.length === 0) {
                  noPending.style.display = "block";
                }
              } else {
                alert("An error has occurred");
              }
            });
          }

          cardbuttons.appendChild(acceptbutton);

          var declinebutton = document.createElement("a");
          declinebutton.setAttribute("href", "#");
          declinebutton.className = "btn btn-secondary profile-card-btns-decline";
          declinebutton.innerHTML = "Decline";

          declinebutton.id = "decline" + profile["user_id"];
          declinebutton.onclick = function(event) {

            var buttonPressed = document.getElementById(this.id);
            var target = this.id.substring(7);

            $.ajax({
              type: "POST",
              url: "config/decline_connection.php",
              data: {
                target_id: target
              },
              async: true
            }).done(function(res) {
              var result = String(res).trim();
              if (result === "Success!") {
                console.log("Declined connection with user id " + target);
                pendingContainer.removeChild(card);

                for (let i = 0; i < pendingProfiles.length; i++) {
                  var profile = pendingProfiles[i];

                  if (profile["user_id"] === target) {
                    pendingProfiles.splice(i, 1);
                  }
                }
                console.log(pendingProfiles);

                if (pendingProfiles.length === 0) {
                  noPending.style.display = "block";
                }
              } else {
                alert("An error has occurred");
              }
            });
          };

          cardbuttons.appendChild(declinebutton);

          //Dropdown Menu
          //Dropdown Menu Button Group
          var dropDownMenuButtonGroup = document.createElement("div");
          dropDownMenuButtonGroup.className = "btn-group profile-card-btns-dropdown";

          //Dropdown Menu Button
          var dropDownMenuButton = document.createElement("a");
          dropDownMenuButton.className = "btn dropdown-toggle btn-primary dropdown-btn";
          dropDownMenuButton.setAttribute("data-toggle", "dropdown");
          dropDownMenuButton.setAttribute("href", "#");
          dropDownMenuButton.setAttribute("data-target", "dropdown-pending-"+profile['user_id']);

          //Hamburger
          var hamburger = document.createElement("i");
          hamburger.className = "fa-solid fa-bars";
          dropDownMenuButton.appendChild(hamburger);

          //Dropdown Menu
          var dropDownMenu = document.createElement("ul");
          dropDownMenu.className = "dropdown-menu dropdown";
          dropDownMenu.id = "dropdown-pending-"+profile['user_id'];

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
          cardbuttons.appendChild(dropDownMenuButtonGroup);

          //Append CardButtons To The Card
          card.appendChild(cardbuttons);

          pendingContainer.appendChild(card);
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

      currentUserAge = age;

      suggestUsers();
    });

    function suggestUsers() {
      const suggestionsContainer = document.getElementById("suggestions-container");
      suggestUsers = [];

      getCandidates();

      if (candidates.length > 0) {
        for (let i = 0; i < candidates.length; i++) {
          var profile = candidates[i];

          profile.score = 0;

          var age = profile["age"];

          var ageDiff = Math.abs(currentUserAge - parseInt(age));

          var ageScore = 0;

          // CLOSER AGE = HIGHER SCORE OUT OF 30
          if (ageDiff < 2) {
            ageScore += 30;
          } else if (ageDiff < 4) {
            ageScore += 25;
          } else if (ageDiff < 6) {
            ageScore += 15;
          } else if (ageDiff < 11) {
            ageScore += 5;
          } else {
            ageScore = 0;
          }

          // MORE INTERESTS IN COMMON = HIGHER SCORE OUT OF 70
          var interests = profile["interests"];
          var interestsInCommon = 0;

          for (let j = 0; j < interests.length; j++) {
            var interest = interests[j]["interest_id"];

            if (currentUserInterests.indexOf(interest) !== -1) {
              interestsInCommon += 1;
            }
          }

          var interestsScore = ((interestsInCommon * 1.0 / currentUserInterests.length) * 70);

          profile.score += (ageScore + interestsScore);
        }

        candidates.sort((a, b) => b.score - a.score);
        console.log(candidates);

        createSuggestedCards();
      }


    }

    function getCandidates() {
      // FILTER PROFILES TO BE DISPLAYED
      candidates = [];

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

        if (profile["is_user_blocked"] === "true") {
          continue;
        }

        if (profile["is_user_liked"] === "true") {
          continue;
        }

        if (currentUserPref !== "Both") {
          if (currentUserPref === "Male" && profile["sex"] === "Female") {
            continue;
          } else if (currentUserPref === "Female" && profile["sex"] === "Male") {
            continue;
          }
        }

        candidates.push(profile);
      }
    }

    function createSuggestedCards() {
      const noSuggest = document.getElementById("no-suggestions");

      const suggestionsContainer = document.getElementById("suggestions-container");

      if (candidates.length > 0) {
        noSuggest.style.display = "none";

        var numOfSuggestions = Math.min(candidates.length, 4);

        for (let i = 0; i < numOfSuggestions; i++) {
          var profile = candidates[i];

          var card = document.createElement("div");
          card.className = "card card-block mx-2 profile-card";

          var image = document.createElement("img");
          image.className = "card-img-top";
          image.setAttribute("src", profile["picture"]);
          image.setAttribute("alt", "profile image");
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

          var userDOB = new Date(profile["date_of_birth"]);

          var ageDifMs = Date.now() - userDOB;
          var ageDate = new Date(ageDifMs);
          var age = Math.abs(ageDate.getUTCFullYear() - 1970);

          agelocation.innerHTML = age.toString() + " - " + profile["location"];
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
          likebutton.className = "btn btn-primary profile-card-btns-decline";
          likebutton.innerHTML = "Like";

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


          //Dropdown Menu
          //Dropdown Menu Button Group
          var dropDownMenuButtonGroup = document.createElement("div");
          dropDownMenuButtonGroup.className = "btn-group profile-card-btns-dropdown";

          //Dropdown Menu Button
          var dropDownMenuButton = document.createElement("a");
          dropDownMenuButton.className = "btn dropdown-toggle btn-primary dropdown-btn";
          dropDownMenuButton.setAttribute("data-toggle", "dropdown");
          dropDownMenuButton.setAttribute("href", "#");
          dropDownMenuButton.setAttribute("data-target", "dropdown-pending-"+profile['user_id']);

          //Hamburger
          var hamburger = document.createElement("i");
          hamburger.className = "fa-solid fa-bars";
          dropDownMenuButton.appendChild(hamburger);

          //Dropdown Menu
          var dropDownMenu = document.createElement("ul");
          dropDownMenu.className = "dropdown-menu dropdown";
          dropDownMenu.id = "dropdown-pending-"+profile['user_id'];

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
          cardbuttons.appendChild(dropDownMenuButtonGroup);
          

          //Append CardButtons To The Card
          card.appendChild(cardbuttons);

          suggestionsContainer.appendChild(card);
        }
      }
      $('.dropdown-btn').on("click", function(e){
        $(this).next('ul').toggle();
        
        e.stopPropagation();
        e.preventDefault();
      });

      
      //On card menu item click
      $(document).on('click', 'a.card-menu-btn', function(e) {
            e.preventDefault(); 
            $action = $(this).attr('action');
            $userId = $(this).attr('user_id');
            if($action == "report"){
                $("#reportUserModal").modal('show');
                
                $(document).on('click', '#reportUser', function(e) {
                    if($('#report-user-textarea').val().length == 0){
                        $('<div class="alert alert-danger"><strong>Textarea can\'t be empty</strong></div>').css({
                            "position": "fixed",
                            "top": 15,
                            "left": 15,
                            "z-index": 10000,
                            "text-align": "center",
                            "font-weight": "bold"
                        }).hide().appendTo("body").fadeIn(1000);
                        $('.alert').fadeOut(1000);
                    }else{
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
                                $('<div class="alert alert-success"><strong>'+result+'</strong></div>').css({
                                    "position": "fixed",
                                    "top": 15,
                                    "left": 15,
                                    "z-index": 10000,
                                    "text-align": "center",
                                    "font-weight": "bold"
                                }).hide().appendTo("body").fadeIn(1000);
                                $('.alert').fadeOut(1000);
                                $("#reportUserModal").modal('hide');
                            }else{
                                $('<div class="alert alert-danger"><strong>'+result+'</strong></div>').css({
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
            }else if($action == "block"){
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
                        $('<div class="alert alert-success"><strong>'+result+'</strong></div>').css({
                            "position": "fixed",
                            "top": 15,
                            "left": 15,
                            "z-index": 10000,
                            "text-align": "center",
                            "font-weight": "bold"
                        }).hide().appendTo("body").fadeIn(1000);
                        $('.alert').fadeOut(1000);
                    }else{
                        $('<div class="alert alert-danger"><strong>'+result+'</strong></div>').css({
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
            }else if($action == "ban"){
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
                        $('<div class="alert alert-success"><strong>'+ result + '</strong></div>').css({
                            "position": "fixed",
                            "top": 15,
                            "left": 15,
                            "z-index": 10000,
                            "text-align": "center",
                            "font-weight": "bold"
                        }).hide().appendTo("body").fadeIn(1000);
                        $('.alert').fadeOut(1000);
                    }else{
                        $('<div class="alert alert-danger"><strong>'+result+'</strong></div>').css({
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
            }else if($action == "warn"){
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
                        $('<div class="alert alert-success"><strong>'+result+'</strong></div>').css({
                            "position": "fixed",
                            "top": 15,
                            "left": 15,
                            "z-index": 10000,
                            "text-align": "center",
                            "font-weight": "bold"
                        }).hide().appendTo("body").fadeIn(1000);
                        $('.alert').fadeOut(1000);
                    }else{
                        $('<div class="alert alert-danger"><strong>'+result+'</strong></div>').css({
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
    }
  </script>
</body>

</html>