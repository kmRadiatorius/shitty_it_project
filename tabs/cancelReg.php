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
<table style="border-width: 2px; border-style: dotted;"><tr><td>
             Atgal į [<a href="schedule.php">tvarkaraštį</a>]
          </td></tr>
        </table><br>

<?php
if (isset($_GET['groupId'])){
	$groupId = $_GET['groupId'];
	$totalReg = $_GET['totalReg'];
}

if ($groupId != null) {
	$totalReg = $totalReg - 1;
	$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	mysqli_set_charset($db,"utf8");
	$sql = "UPDATE users 
			SET danceGroupId = 0
			WHERE username='".$_SESSION['user']."'";
	$result = mysqli_query($db, $sql);
	if (!$result) {
		echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
		exit;
	}

	$sql = "UPDATE danceGroup
			SET totalRegistrations = ".$totalReg."
			WHERE id = ".$groupId;
	$result = mysqli_query($db, $sql);
	if (!$result) {
		echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
		exit;
	} else {
	 echo '<center><h1>Registracija sėkmingai atšaukta</h1></center>';   
	}
}

header("Location: schedule.php");
?>