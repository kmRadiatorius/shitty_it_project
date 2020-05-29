<?php
header("Content-Type: application/json; charset=UTF-8");
include("../include/nustatymai.php");
include("../include/functions.php");

class Message {
	public $id;
	public $sentFrom;
	public $sentTo;
	public $message;
	public $unread;
}



$sentTo = $_GET['sentTo'];
$sentFrom = $_GET['sentFrom'];
$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($db,"utf8");
$chat = "";
$sql = "SELECT message, sentFrom
        FROM message 
        WHERE (sentTo = '".$sentTo."' AND sentFrom = '".$sentFrom."')
        OR (sentTo = '".$sentFrom."' AND sentFrom = '".$sentTo."')";
$result = mysqli_query($db, $sql);
if (!$result) {
    echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
    exit;
}
    
$result =  $db->query($sql);
$outp = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($outp);
?>