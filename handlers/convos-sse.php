<?php
ignore_user_abort(true);
set_time_limit(1800);
session_start();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header("Access-Control-Allow-Origin: *");
header("Connection: keep-alive");
$userNotLoggedIn = !isset($_SESSION['user_id']);
$userId = $_SESSION['user_id'];
session_write_close();

// Check user session validity
if($userNotLoggedIn) {
	$sseResponse = "data: " . json_encode(array('error' => 1, 'error_message' => 'User Authentication Failed')) . PHP_EOL . PHP_EOL;
	echo $sseResponse;
	exit();
}



$heartbeatCountdown = 5;
while(1) {
	
	// Get the last post_id that was shown to the user
	session_start();
	$lastMatchId = $_SESSION['last_match_id'];
    $lastMessageId = $_SESSION['last_message_id'];
	session_write_close();

	// Check every 2 seconds
	sleep(2);
	
	$sseResponse = checkForUpdates($userId, $lastMatchId, $lastMessageId);
	
	session_start();
	$isUpdates = $lastMatchId < $_SESSION['last_match_id'] || $lastMessageId < $_SESSION['last_message_id'];
	session_write_close();

	if ($isUpdates){
		echo $sseResponse;
		$heartbeatCountdown = 5;
	}else if ($heartbeatCountdown === 0){
		echo "data: " . json_encode('ping') . PHP_EOL . PHP_EOL;
		$heartbeatCountdown = 5;
	}
	
	ob_flush();	// Sends output data from PHP to Apache. 
	flush();	// Sends output from Apache to browser.
	
	if(connection_aborted()){
		exit();
	}
	$heartbeatCountdown--;
}

function checkForUpdates($userId, $lastMatchId, $lastMessageId){
	include('../config/connection.php'); 
	
	$newMatches = array();
	$newMessages = array();
	
    //check database for a match with logged in users id and a match id > last match id
	$sql = "SELECT * FROM `matches` WHERE (user_one_id = '$userId' OR user_two_id = '$userId') AND (match_id > '$lastMatchId') ORDER BY date ASC;";
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result);
	
	
	if ($count>=1){
		
		while($row = mysqli_fetch_assoc($result)) {
			//get match details and append.
			$otherUserId = 0;
			if ($row['user_one_id']===$userId){
                $otherUserId = $row['user_two_id'];
            }else{
                $otherUserId = $row['user_one_id'];
            }
			$sql = "SELECT * FROM profiles WHERE user_id = '$otherUserId'";
            $resultTwo = mysqli_query($con, $sql); 
            $count = mysqli_num_rows($resultTwo);
			if($count<1){
				continue;
			}else{
				$rowTwo = mysqli_fetch_assoc($resultTwo);
				$otherUserProfileImgLocation = "./images/default_profile_image.png";
				if(file_exists('../images/' . $rowTwo['user_id'] . '_profile_image.jpg')){
                    $otherUserProfileImgLocation = './images/' . $rowTwo['user_id'] . '_profile_image.jpg';
                }

				$newMatches[] = array('match_id' => $row['match_id'], 'chatSummary' => '<div user_id="' . $otherUserId . '" style="cursor: pointer;" class="chat-thumb">
				<div class="chat-thumb-profile-pic-container">
					<img class="chat-thumb-profile-pic" src="' . $otherUserProfileImgLocation . '" width="60" height="60" alt="profile image">
				</div>
				<div class="chat-preview">
					<div class="chat-preview-username">
						' . $rowTwo['display_name'] . '
					</div>
				<div class="chat-preview-content">No messages at present!</div></div></div>');

				session_start();
				$_SESSION['last_match_id'] = $row['match_id'];
				session_write_close();
			}
		}
	}
	
    //check database for a message with logged in users id and a message id > last message id
	$sql = "SELECT * FROM messages WHERE (sender_id = '$userId' OR receiver_id = '$userId') AND (message_id > '$lastMessageId') ORDER BY date ASC";
    $result = mysqli_query($con, $sql);  
    $count = mysqli_num_rows($result);
	if ($count>=1){
		while($row = mysqli_fetch_assoc($result)) {
			$otherUserId = 0;
			$messageContent = '';
			if ($row['sender_id']===$userId){
                $otherUserId = $row['receiver_id'];
            }else{
                $otherUserId = $row['sender_id'];
            }
			$otherUserProfileImage = "./images/default_profile_image.png";
			$loggedInUserProfileImage = "./images/default_profile_image.png";
			if(file_exists('../images/' . $otherUserId . '_profile_image.jpg')){
				$otherUserProfileImage = './images/' . $userId . '_profile_image.jpg';
			}
			if(file_exists('../images/' . $userId . '_profile_image.jpg')){
				$loggedInUserProfileImage = './images/' . $userId . '_profile_image.jpg';
			}
			$matchContentLastMessageTxt = null;
			if ($row['sender_id']===$userId){
                $messageContent = '<div class="message-sent">
                        <img src="' . $loggedInUserProfileImage .'" alt="Avatar" class="right">
                        <p>' . $row['message_content'] .'</p>
                        <span class="time-left">' . $row['date'] .'</span>
                    </div>';
					$matchContentLastMessageTxt = 'You: ' . $row['message_content'];
            }else{
                $messageContent = '<div class="message-received">
                        <img src="' . $otherUserProfileImage .'" alt="Avatar">
                        <p>' . $row['message_content'] .'</p>
                        <span class="time-right">' . $row['date'] .'</span>
                    </div>';
					
            }
						
			
			$matchContent = '';
			$sql = "SELECT * FROM profiles WHERE user_id = '$otherUserId'";
            $resultTwo = mysqli_query($con, $sql); 
            $count = mysqli_num_rows($resultTwo);
			if($count<1){
				continue;
			}else{
				$rowTwo = mysqli_fetch_assoc($resultTwo);
				if ($matchContentLastMessageTxt === null){
					$matchContentLastMessageTxt = $rowTwo['display_name'] . ": " . $row['message_content'];
				}

				$otherUserImg = "./images/default_profile_image.png";
				if(file_exists('../images/' . $rowTwo['user_id'] . '_profile_image.jpg')){
					$otherUserImg = './images/' . $rowTwo['user_id'] . '_profile_image.jpg';
				}

				$matchContent = '<div user_id="' . $rowTwo['user_id'] . '" style="cursor: pointer;" class="chat-thumb">
				<div class="chat-thumb-profile-pic-container">
					<img class="chat-thumb-profile-pic" src="' . $otherUserImg . '" width="60" height="60" alt="profile image">
				</div>
				<div class="chat-preview">
					<div class="chat-preview-username">
						' . $rowTwo['display_name'] . '
					</div>
				<div class="chat-preview-content">' . $matchContentLastMessageTxt . '</div></div></div>';


				$newMessages[] = array('message' => $row['message_id'], 'message_content' => $messageContent, 'match_content' => $matchContent, 'other_user_id' => $otherUserId);
				session_start();
				$_SESSION['last_message_id'] = $row['message_id'];
				session_write_close();
			}
	
		}
	}
	mysqli_close($con);
	return "data: " . json_encode(array('new_matches' => $newMatches, 'new_messages' => $newMessages, 'error' => 0)) . PHP_EOL . PHP_EOL;
}

?>