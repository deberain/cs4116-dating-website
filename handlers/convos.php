<?php
session_start();
$_SESSION['last_match_id'] = 0;
$_SESSION['last_message_id'] = 0;
class ChatSummary{
    private $userId;
    private $userName;
    private $userProfilePicLocation;
    private $lastMessage;
    private $lastMessageSentByLoggedInUser;
    private $dateOfLastMessage;
    private $matchDate;
    private $matchId;
    private $lastMessageId;

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($property){
        return $this->$property;
    }
}


if (!isset($_SESSION['LoggedIn'])) {
    header('Location: index.php');
    exit();
}

if(isset($_POST['func']))
{
    if($_POST['func'] == "getChats"){
        getChats();
    }else if($_POST['func'] == "getChat" && $_POST['userId']){
        getChat();
    }else if($_POST['func'] == "sendChat" && $_POST['userId']){
        sendChat();
    }else if($_POST['func'] == "getProfile" && $_POST['userId']){
        getProfile();
    }else{
        http_response_code(400);
    }
}else{
    http_response_code(400);
    header('Location: index.php');
}
exit();

function getChats(){
    include('../config/connection.php'); 
    $listOfChatSummarys = [];
    $userId = $_SESSION['user_id'];
    $sql = "SELECT * FROM matches WHERE user_one_id = '$userId' OR user_two_id = '$userId' ORDER BY date DESC;";
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result);  
    if($count >= 1){
        while($row = mysqli_fetch_assoc($result)) {
            $chatSummary = new ChatSummary();
            $chatSummary->matchId = $row['match_id'];
            if($chatSummary->matchId > $_SESSION['last_match_id']){
                $_SESSION['last_match_id'] = $chatSummary->matchId;
            }
            if ($row['user_one_id']==$userId){
                $chatSummary->userId = $row['user_two_id'];
            }else{
                $chatSummary->userId = $row['user_one_id'];
            }
            $chatSummary->matchDate = $row['date'];

            $sql = "SELECT * FROM profiles WHERE user_id = '$chatSummary->userId'";
            $resultTwo = mysqli_query($con, $sql); 
            $count = mysqli_num_rows($resultTwo); 

            if ($count<1){
                continue;
            }else{

                $rowTwo = mysqli_fetch_assoc($resultTwo);
                $chatSummary->userName = $rowTwo['display_name'];
                if(file_exists('../' . $rowTwo['picture'])){
                    $chatSummary->userProfilePicLocation = './' . $rowTwo['picture'];
                }else{
                    $chatSummary->userProfilePicLocation = "./images/default_profile_image.png";
                }


                $sql = "SELECT * FROM messages WHERE (sender_id = '$chatSummary->userId' AND receiver_id = '$userId') OR (receiver_id = '$chatSummary->userId' AND sender_id = '$userId') ORDER BY date DESC LIMIT 1";
                $resultThree = mysqli_query($con, $sql); 
                $count = mysqli_num_rows($resultThree); 
                if ($count<1){
                    $chatSummary->lastMessage = 'No messages at present';
                    $chatSummary->lastMessageSentByLoggedInUser = null;
                    $chatSummary->dateOfLastMessage = 0;
                    $chatSummary->messageId = 0;
                    array_push($listOfChatSummarys, $chatSummary);
                    continue;
                }else{
                    $rowThree = mysqli_fetch_assoc($resultThree);
                    $chatSummary->lastMessage = $rowThree['message_content'];
                    $chatSummary->messageId = $rowThree['message_id'];
                    if ($rowThree['sender_id']==$userId){
                        $chatSummary->lastMessageSentByLoggedInUser = true;
                    }else{
                        $chatSummary->lastMessageSentByLoggedInUser = false;
                    }
                    $chatSummary->dateOfLastMessage = $rowThree['date'];
                    array_push($listOfChatSummarys, $chatSummary);
                }
            } 
        }
        
        if (count($listOfChatSummarys)>1){
            usort($listOfChatSummarys, function($chatSummaryOne, $chatSummaryTwo) {
                $timeForSummaryOne = 0;
                $timeForSummaryTwo = 0;
                if ($chatSummaryOne->dateOfLastMessage == 0){
                    $timeForSummaryOne = strtotime($chatSummaryOne->matchDate);
                }else{
                    $timeForSummaryOne = strtotime($chatSummaryOne->dateOfLastMessage) > strtotime($chatSummaryOne->matchDate) ? strtotime($chatSummaryOne->dateOfLastMessage) : strtotime($chatSummaryOne->matchDate);
                    
                }

                if ($chatSummaryTwo->dateOfLastMessage == 0){
                    $timeForSummaryTwo = strtotime($chatSummaryTwo->matchDate);
                }else{
                    $timeForSummaryTwo = strtotime($chatSummaryTwo->dateOfLastMessage) > strtotime($chatSummaryTwo->matchDate) ? strtotime($chatSummaryTwo->dateOfLastMessage) : strtotime($chatSummaryTwo->matchDate);                    
                }
                
                return $timeForSummaryTwo - $timeForSummaryOne;
            });
        }


        foreach ($listOfChatSummarys as $chatSumm) {
            echo '<div user_id="' . $chatSumm->userId . '" style="cursor: pointer;" class="chat-thumb">
                    <div class="chat-thumb-profile-pic-container">
                        <img class="chat-thumb-profile-pic" src="' . $chatSumm->userProfilePicLocation . '" alt="profile image">
                    </div>
                    <div class="chat-preview">
                        <div class="chat-preview-username">
                            ' . $chatSumm->userName . '
                        </div>
                    <div class="chat-preview-content">';
            if($chatSumm->lastMessageSentByLoggedInUser === null){
                echo 'No messages at present!';
                
            }else{
                if ($chatSumm->lastMessageSentByLoggedInUser){
                    echo 'You: ' . stripcslashes($chatSumm->lastMessage);
                }else{
                    echo $chatSumm->userName . ': ' . stripcslashes($chatSumm->lastMessage);
                }
                if($chatSumm->messageId > $_SESSION['last_message_id']){
                    $_SESSION['last_message_id'] = $chatSumm->messageId;
                }
            }
            echo '</div></div></div>';       
        }
        mysqli_close($con);
        return;
    }

    mysqli_close($con);
    echo '<div id="no-matches-text" class="py-5 px-5 display-4 text-light">You haven\'t matched with anyone yet :(</div>';
    return;
}

function getChat(){
    include('../config/connection.php'); 
    $userId = $_POST['userId'];//not logged in user.
    $loggedInUserId = $_SESSION['user_id'];
    $sql = "SELECT * FROM messages WHERE sender_id = '$userId' AND receiver_id = '$loggedInUserId' OR sender_id = '$loggedInUserId'AND receiver_id = '$userId' ORDER BY date ASC;";
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result);
    if ($count < 1){
        return;
    }else{
        //sql query to get profile image locations
        $sqlTwo = "SELECT * FROM profiles WHERE user_id = '$userId';";
        $resultTwo = mysqli_query($con, $sqlTwo);  
        $rowTwo = mysqli_fetch_assoc($resultTwo);
        $otherUserProfileImage = $rowTwo['picture'];

        $sqlThree = "SELECT * FROM profiles WHERE user_id = '$loggedInUserId';";
        $resultThree = mysqli_query($con, $sqlThree);  
        $rowThree = mysqli_fetch_assoc($resultThree);
        $loggedInUserProfileImage = $rowThree['picture'];
        
        
        if(file_exists('../' . $otherUserProfileImage)){
            $otherUserProfileImage = './' . $otherUserProfileImage;
        }else{
            $otherUserProfileImage = "./images/default_profile_image.png";
        }

        if(file_exists('../' . $loggedInUserProfileImage)){
            $loggedInUserProfileImage = './' . $loggedInUserProfileImage;
        }else{
            $loggedInUserProfileImage = "./images/default_profile_image.png";
        }

        while($row = mysqli_fetch_assoc($result)) {
            if ($row['sender_id']==$loggedInUserId){
                echo '<div class="message-sent pb-5">
                        <img src="' . $loggedInUserProfileImage .'" alt="Avatar" class="right">
                        <p style="word-break:normal;">' . stripcslashes($row['message_content']) .'</p>
                        <span class="time-left">' . $row['date'] .'</span>
                    </div>';
            }else{
                echo '<div class="message-received pb-5">
                        <img src="' . $otherUserProfileImage .'" alt="Avatar">
                        <p style="word-break:normal;">' . stripcslashes($row['message_content']) .'</p>
                        <span class="time-right">' . $row['date'] .'</span>
                    </div>';
            }
        }
        mysqli_close($con);
        return;
    }
}

function sendChat(){
    include('../config/connection.php'); 
    $userId = $_POST['userId'];
    $messageContent = mysqli_real_escape_string($con, $_POST['messageContent']);
    $loggedInUserId = $_SESSION['user_id'];
    $sql = "INSERT INTO messages (message_id, sender_id, receiver_id, message_content, date) VALUES ('NULL', '$loggedInUserId', '$userId', '$messageContent', date('Y-m-d H:i:s'))";

    if (!mysqli_query($con, $sql)) {
        http_response_code(500);
        echo "Error: " . $sql . mysqli_error($con);
    }else{
        echo 'message sent';
    }
    mysqli_close($con);
    return;
}


function getProfile(){
    include('../config/connection.php'); 
    $otherUserId = $_POST['userId'];

    //get users name, location, bio, sex, picture & return in formatted HTML
    $sql = "SELECT * FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE users.user_id = '$otherUserId'";
    $result = mysqli_query($con, $sql); 
    $row = mysqli_fetch_assoc($result);
    $otherUserProfileImage = '';
    if(file_exists('../' . $row['picture'])){
        $otherUserProfileImage = './' . $row['picture'];
    }else{
        $otherUserProfileImage = "./images/default_profile_image.png";
    }

    $dateOfBirth = $row['date_of_birth'];
    $today = date("Y-m-d");
    $diff = date_diff(date_create($dateOfBirth), date_create($today));
    $age = $diff->format('%y');

    echo '<div class="card matched-user-card" ><img class="profile-img" src="' . $otherUserProfileImage . '">';
    echo '<div class="row justify-content-start m-2" style="padding-top: 10px; padding-bottom: 10px;"><div class="col-6">';
    if($_SESSION['user_type']==1){
        echo '<button type="button" action="warn" user-id="'.$row['user_id'].'" style="color:black; font-weight:bold;" class="admin-action btn btn-warning">Warn User</button></div><div class="col-6"><button type="button" action="ban" user-id="'.$row['user_id'].'" style="color:black; font-weight:bold;" class="admin-action btn btn-danger">Ban User</button>';
    }else{
        echo '<button type="button" action="block" user-id="'.$row['user_id'].'" style="color:black; font-weight:bold;" class="admin-action btn btn-warning">Block User</button></div><div class="col-6"><button type="button" action="report" user-id="'.$row['user_id'].'" style="color:black; font-weight:bold;" class="admin-action btn btn-danger">Report User</button>';
    }
    echo '</div></div>';
    echo '<h3 class="p-2">' . stripcslashes($row['display_name']) . ' (' . stripcslashes($age) .')</h1>';
    echo '<p class="p-2">' . stripcslashes($row['display_name']). ' is a ' . stripcslashes($age) . ' year old ' . strtolower(stripcslashes($row['sex'])) . ' from '. stripcslashes($row['location']) . '.</p>';
    echo '<p class="p-2">' . stripcslashes($row['bio']). '</p>';

    $sqlTwo = "SELECT * FROM user_interests INNER JOIN interests ON user_interests.interest_id = interests.interest_id WHERE user_id = '$otherUserId'";
    $resultTwo = mysqli_query($con, $sqlTwo);
    if(mysqli_num_rows($resultTwo)>0){
        echo '<h4 class="p-2">Interests</h3><ul>';
        while($rowTwo = mysqli_fetch_assoc($resultTwo)) {
            echo '<li>' . $rowTwo['interest_name'] . '</li>';
        }
        echo '</ul></div>';
    }
    
    return;
}


?>