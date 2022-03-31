<?php
session_start();

//If Already Logged IN
if (!isset($_SESSION['userReg'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupid.com | Register</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="./css/register.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">
</head>

<body>
    <div class="register">
        <div class="profile-form text-center rounded">

            <form class="registerForm" id="registerForm" name="registerForm" action="config/profile_reg.php" method="post" enctype="multipart/form-data">

                <div class="form-row justify-content-around mb-2">
                    <div class="edit-profile-image">
                        <img class="profile-image" id="editCurrentUserPic" src="assets/default_profile_image.png" alt="profile image"> <br />
                        <input type="file" accept="image/*" id="uploadProfileImage" name="image" value="Upload Image">
                    </div>
                </div>


                <div class="form-row justify-content-around mb-2">
                    <div class="col-5">
                        <label for="InputFullName" class="mb-0">Enter Display Name:</label>
                        <input type="text" class="form-control text-small" id="InputFullName" aria-describedby="Name" placeholder="Display Name" name="display_name" >
                    </div>
                    <div class="col-5">
                        <label for="InputLocation" class="mb-0"></label>Where do you live?</label>
                        <select class="form-control text-small" id="InputLocation" name="location" required>
                            <option selected>Choose...</option>
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
                </div>

                <div class="form-row justify-content-around mb-2">
                    <div class="col-5">
                        <label for="SelectSex" class="mb-0"></label>Your sex:</label>
                        <select class="form-control text-small" id="SelectSex" name="sex">
                            <option selected>Choose...</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <label for="SelectPrefSex" class="mb-0">Preferred sex:</label>
                        <select class="form-control text-small" id="SelectPrefSex" name="pref">
                            <option selected>Choose...</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Both</option>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-around mb-2">
                    <div class="col-10">
                        <label for="SelectInterests" class="mb-0"></label>Your Interests:</label>
                        <select class="selectpicker" id="SelectInterests" multiple data-live-search="true" name="interests">

                        </select>
                    </div>
                </div>


                <div class="form-group mt-4 mb-2">
                    <label for="InputBio">Tell us about yourself!</label>
                    <textarea class="form-control" id="InputBio" rows="4" name="bio" maxlength="100"></textarea>
                </div>
                <br>
                <input type="submit" class="btn btn-purple px-3" id="reg" value="Submit"/>
            </form>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            console.log('Page Ready');

            // Obtain Interests list
            var interestsList = [];

            $.ajax({
                type: "GET",
                url: "config/get_interests.php",
                async: false
            }).done(function(res) {
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

            // Profile picture upload
            const uploadImage = document.querySelector("#uploadProfileImage");
            var uploadedImage = "";

            uploadImage.addEventListener("change", function() {
                const reader = new FileReader();
                reader.addEventListener("load", () => {
                    uploadedImage = reader.result;

                    $("#editCurrentUserPic").attr({
                        "src": uploadedImage
                    })
                });
                reader.readAsDataURL(this.files[0]);
            })

            // submit profile details
            $("#registerForm").on('submit', function(e) {
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

                $.ajax({
                    type: "POST",
                    url: "config/profile_reg.php",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false
                }).done(function(res) {
                      var result = String(res).trim();
                      console.log(result);
                      if(result === "Success!") {
                        document.location.href="home.php";
                      } else {
                        alert("An error ahs occurred");
                      }
                      
                  });

            });
        });
    </script>
</body>

</html>