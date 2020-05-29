<?php
include("../include/nustatymai.php");
include("../include/functions.php");

$sentFrom = $_GET['sentFrom'];
$sentTo = $_GET['sentTo'];
$message = json_decode($_GET['message']);

$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$db)
	echo "DB error";
else
	echo "connected";
mysqli_set_charset($db,"utf8");
$sql = "INSERT INTO message (message, sentFrom, sentTo)
  		VALUES ('".$message."', '".$sentFrom."', '".$sentTo."')";

echo "\n".$sql."\n";
$result;
if ($result = mysqli_query($db, $sql)) {
	echo "Įrašyta";
} else {
	echo mysqli_error($db);
}
?>