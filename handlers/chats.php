<?php
session_start();

class ChatSummary{
    private $userId;
    private $userName;
    private $userProfilePicLocation;
    private $lastMessage;
    private $lastMessageSentByLoggedInUser;
    private $dateOfLastMessage;
    private $matchDate;

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
    if($_POST['func'] == "getChats")
    {
        getChats();
    }else if($_POST['func'] == "getChat")
    {
        if($_POST['userId']){
            getChat();
        }else{
            //userId not passed in the request
            http_response_code(404);
        }
        
    }else if($_POST['func'] == "sendChat")
    {
        if($_POST['userId'] && $_POST['userId']){
            sendChat();
        }else{
            //userId not passed in the request
            http_response_code(404);
        }
    }else if($_POST['func'] == "listenToChat")
    {
        listenToChat();
    }
}else{
    header('Location: index.php');
    exit();
}



function getChats(){
    include('../config/connection.php'); 
    $listOfChatSummarys = [];
    $userId = $_SESSION['user_id'];
    $sql = "SELECT user_one_id, user_two_id, date FROM matches WHERE user_one_id = '$userId' OR user_two_id = '$userId' ORDER BY date DESC;";
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result);  
    if($count >= 1){
        while($row = mysqli_fetch_assoc($result)) {
            $chatSummary = new ChatSummary();
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
                if(file_exists('../images/' . $chatSummary->userId . '_profile_image.jpg')){
                    $chatSummary->userProfilePicLocation = './images/' . $chatSummary->userId . '_profile_image.jpg';
                }else{
                    $chatSummary->userProfilePicLocation = "./images/default_profile_image.png";
                }


                $sql = "SELECT * FROM messages WHERE sender_id = '$chatSummary->userId' AND receiver_id = '$userId' OR receiver_id = '$chatSummary->userId' AND sender_id = '$userId' ORDER BY date DESC LIMIT 1";
                $resultThree = mysqli_query($con, $sql); 
                $count = mysqli_num_rows($resultThree); 
                if ($count<1){
                    $chatSummary->lastMessage = 'No messages at present';
                    $chatSummary->lastMessageSentByLoggedInUser = null;
                    $chatSummary->dateOfLastMessage = 0;
                    array_push($listOfChatSummarys, $chatSummary);
                    continue;
                }else{
                    $rowThree = mysqli_fetch_assoc($resultThree);
                    $chatSummary->lastMessage = $rowThree['message_content'];
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
                        <img class="chat-thumb-profile-pic" src="' . $chatSumm->userProfilePicLocation . '" width="60" height="60" alt="profile image">
                    </div>
                    <div class="chat-preview">
                        <div class="chat-preview-username">
                            ' . $chatSumm->userName . '
                        </div>
                    <div class="chat-preview-content">';
            if($chatSumm === null){
                echo 'No messages at present!';
            }else{
                if ($chatSumm->lastMessageSentByLoggedInUser){
                    echo 'You: ' . $chatSumm->lastMessage;
                }else{
                    echo $chatSumm->userName . ': ' . $chatSumm->lastMessage;
                }
            }
            echo '</div></div></div>';
        }
        mysqli_close($con);
        return;
    }

    mysqli_close($con);
    echo '<div class="py-5 px-5 display-4 text-light">You haven\'t matched with anyone yet :(</div>';
    return;
}

function getChat(){
    include('../config/connection.php'); 
    $userId = $_POST['userId'];
    $loggedInUserId = $_SESSION['user_id'];
    $sql = "SELECT * FROM messages WHERE sender_id = '$userId' AND receiver_id = '$loggedInUserId' OR sender_id = '$loggedInUserId'AND receiver_id = '$userId' ORDER BY date ASC;";
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result);
    if ($count < 1){
        return;
    }else{
        $otherUserProfileImage;
        $loggedInUserProfileImage;
        if(file_exists('../images/' . $userId . '_profile_image.jpg')){
            $otherUserProfileImage = './images/' . $userId . '_profile_image.jpg';
        }else{
            $otherUserProfileImage = "./images/default_profile_image.png";
        }

        if(file_exists('../images/' . $loggedInUserId . '_profile_image.jpg')){
            $loggedInUserProfileImage = './images/' . $loggedInUserId . '_profile_image.jpg';
        }else{
            $loggedInUserProfileImage = "./images/default_profile_image.png";
        }

        while($row = mysqli_fetch_assoc($result)) {
            if ($row['sender_id']==$loggedInUserId){
                echo '<div class="message-sent">
                        <img src="' . $loggedInUserProfileImage .'" alt="Avatar" class="right">
                        <p>' . $row['message_content'] .'</p>
                        <span class="time-left">' . $row['date'] .'</span>
                    </div>';
            }else{
                echo '<div class="message-received">
                        <img src="' . $otherUserProfileImage .'" alt="Avatar">
                        <p>' . $row['message_content'] .'</p>
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
    $messageContent = $_POST['messageContent'];
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

function listenToChat(){
    //websocket
}
?>