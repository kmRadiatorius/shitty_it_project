<?php
include("../include/nustatymai.php");
include("../include/functions.php");

$sentFrom = $_GET['sentFrom'];
$message = json_decode($_GET['message']);

$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$db)
	echo "DB error";
else
	echo "connected";
mysqli_set_charset($db,"utf8");

$users = Array();
$sql = "SELECT username FROM users";
$result = mysqli_query($db, $sql);
if (!$result) {
    echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
    exit;
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($users, $row['username']);
	}
}

$sql = "INSERT INTO message (message, sentFrom, sentTo) 
		VALUES ('".$message."', '".$sentFrom."', '".$sentFrom."')";

foreach ($users as $user) {
	$sql = $sql.", ('".$message."', '".$sentFrom."', '".$user."')";
}

echo "\n".$sql."\n";
$result;
if ($result = mysqli_query($db, $sql)) {
	echo "Įrašyta";
} else {
	echo mysqli_error($db);
}
?>