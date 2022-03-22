<?php
$servername = "sql110.epizy.com";
$username = "epiz_31239018";
$password = "L6y9vvtuDF35";
$dbname = "epiz_31239018_cupid_db";

// Create and check the connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$query = mysqli_query($conn, "SELECT * FROM profiles ");
$profileArray = array();
while($a = mysqli_fetch_assoc($query)) {
    $profileArray[] = $a;
}

$x = json_encode($profileArray);
echo $x;

$conn->close();
?>