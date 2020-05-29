<?php
// index.php
// jei vartotojas prisijungęs rodomas demonstracinis meniu pagal jo rolę
// jei neprisijungęs - prisijungimo forma per include("login.php");
// toje formoje daugiau galimybių...

session_start();
setlocale(LC_ALL, 'lt_LT');
include("../include/nustatymai.php");
include("../include/functions.php");

           
if (!empty($_SESSION['user']))     //Jei vartotojas prisijungęs, valom logino kintamuosius ir rodom meniu
{                                  // Sesijoje nustatyti kintamieji su reiksmemis is DB
                                   // $_SESSION['user'],$_SESSION['ulevel'],$_SESSION['userid'],$_SESSION['umail']
inisession("part");   //   pavalom prisijungimo etapo kintamuosius
$_SESSION['prev']="index"; 
}                
else {   			 

  if (!isset($_SESSION['prev'])) inisession("full");             // nustatom sesijos kintamuju pradines reiksmes 
  else {if ($_SESSION['prev'] != "proclogin") inisession("part"); // nustatom pradines reiksmes formoms
       }  
    // jei ankstesnis puslapis perdavė $_SESSION['message']
  echo "<div align=\"center\">";echo "<font size=\"4\" color=\"#ff0000\">".$_SESSION['message'] . "<br></font>";          

          echo "<table class=\"center\"><tr><td>";
    include("include/login.php");                    // prisijungimo forma
          echo "</td></tr></table></div><br>";
}
?>

<?php
  class Message {
    public $id;
    public $sentFrom;
    public $sentTo;
    public $message;
    public $unread;
  }

$username = '';
if (isset($_GET['username'])) {
    $username = $_GET['username'];  
}
    
$users = Array();
if ($username != $_SESSION['user'] && $username != ''){
    $users[$username] = $username;
}
$messages = Array();
$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($db,"utf8");
$sql = "SELECT id, sentFrom, sentTo, message, unread 
        FROM message 
        WHERE sentTo = '".$_SESSION['user']."'
        OR sentFrom = '".$_SESSION['user']."'";
$result = mysqli_query($db, $sql);
if (!$result) {
    echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
    exit;
} else {
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $t = new Message();
        $t->id = $row['id'];
        $t->sentFrom = $row['sentFrom'];
        $t->sentTo = $row['sentTo'];
        $t->message = $row['message'];
        $t->unread = $row['unread'];
        
        if ($t->sentTo != $_SESSION['user']){
            $users[$t->sentTo] = $t->sentTo;
        }
        if ($t->sentFrom != $_SESSION['user']){
            $users[$t->sentFrom] = $t->sentFrom;
        }
        array_push($messages, $t);
    }
}
?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Diskusija</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <table style="border-width: 2px; border-style: dotted;">
            <tr>
                <td>
                     Atgal į [<a href="forum.php">Forumą</a>]  &nbsp;
                </td>
                <td>
                    Atgal į [<a href="../index.php">Pradžią</a>]
                </td>
            </tr>
        </table><br>
		<div style="text-align: center;color:green"> <br><br>
            <h1>Diskusija</h1>
        </div><br>
        <div class="row">
            <div class="col-sm-3">
                <?php
                    foreach ($users as $user) {
                        echo '<div><input type="button" name="usersButtons" value="'.$user.'" onclick="chooseUser(this)" 
                        style="font-size: 20px; text-align: left;padding: 10px 480px 10px 5px; 
                        width: 200px; background-color: #F5FFFA"></button></div>';
                    }
                ?>
            </div>
            <div class="col-sm-3">
                <textarea id="chat" rows="15" cols="100" readonly></textarea>
                <form id="messageTextForm">
                    <input id="messageText" type="textarea" name="text" style="width: 825px;">
                </form>
            </div>
        </div>
	</body>
</html>

<script>
    const textField = document.getElementById('messageText');
    const buttons = document.getElementsByName('usersButtons');
    const chat = document.getElementById('chat');
    chat.value = "";
    let username = "<?php Print(reset($users)); ?>";
    if ("<?php Print($username); ?>" != ''){
        username = "<?php Print($username); ?>";
    }
    if (username != '') {
        for (var i = 0; i < buttons.length; i++) {
            if (buttons[i].value === username) {
                buttons[i].style.background = "#87CEFA";
            }
        }
    }
    
    runFunction();
    
    function chooseUser(e) {
        username = e.value;
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].style.background = "#F5FFFA";
        }
        e.style.backgroundColor = "#87CEFA";
        runFunction();
        return false;
    }
      
    $('#messageTextForm').submit(function () {
        const text = JSON.stringify(textField.value);
        const sentFrom = "<?php Print($_SESSION['user']); ?>";
        const sentTo = username;
        console.log("set3 " + username);
        let url = "http://vartvald/apis/sendMessage.php";
        url+="?message="+text+"&sentFrom="+sentFrom+"&sentTo="+sentTo;
        url = encodeURI(url);
        console.log(url);
        textField.value = "";
        chat.value = chat.value + sentFrom + ": " + JSON.parse(text) + "\n";
        
        fetch(url).then(response => {
            return response.json()
        }).then(data => {
            console.log(data);
        })
        
        runFunction();
        return false;
    });
    
    var t = setInterval(runFunction, 300);
    
    function runFunction() {
        const sentFrom = "<?php Print($_SESSION['user']); ?>";
        const sentTo = username;
        let url = "http://vartvald/apis/loadChat.php";
        let string_ats,x,txt;
        url+="?sentFrom="+sentFrom+"&sentTo="+sentTo;
        //console.log(url);
        
        fetch(url).then(response => {
            return response.json()
        }).then(data => {
            //console.log(data);
            let s = "";
            //console.log (data);
            for (i in data) {
                s += data[i].sentFrom + ": " + data[i].message + "\n";
            }
            chat.value = s;
            chat.scrollTop = chat.scrollHeight;
        })
    }
</script>

