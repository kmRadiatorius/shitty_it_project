<?php
// meniu.php  rodomas meniu pagal vartotojo rolę

if (!isset($_SESSION)) { header("Location: logout.php");exit;}
include("include/nustatymai.php");
$user=$_SESSION['user'];
$userlevel=$_SESSION['ulevel'];
$role="";
{foreach($user_roles as $x=>$x_value)
			      {if ($x_value == $userlevel) $role=$x;}
} 
	if ($_SESSION['user'] != "administratorius" && $_SESSION['user'] != 'guest') 
		echo "<div style='text-align: right;'><a href=\"tabs\\thread.php\">Žinutės</a></div>";
     echo "<table width=100% border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"meniu\">";
        echo "<tr><td>";
        echo "Prisijungęs vartotojas: <b>".$user."</b>";
        echo "</td></tr><tr><td>";
        if ($_SESSION['user'] != "guest") echo "[<a href=\"useredit.php\">Redaguoti paskyrą</a>] &nbsp;&nbsp;";
        if ($_SESSION['user'] != "administratorius" && $_SESSION['user'] != 'guest') echo "[<a href=\"tabs\\schedule.php\">Tvarkaraštis</a>] &nbsp;&nbsp;";
        if ($_SESSION['user'] != "administratorius" && $_SESSION['user'] != 'guest') echo "[<a href=\"tabs\\forum.php\">Forumas</a>] &nbsp;&nbsp;";
		if ($_SESSION['user'] == "administratorius") echo "[<a href=\"tabs\\photos.php\">Nuotraukos</a>] &nbsp;&nbsp;";
		if ($_SESSION['user'] == "administratorius") echo "[<a href=\"tabs\\emailSending.php\">E-mail siuntimas</a>] &nbsp;&nbsp;";
     //Trečia operacija tik rodoma pasirinktu kategoriju vartotojams, pvz.:
        if (($userlevel == $user_roles["Dėstytojas"]) || ($userlevel == $user_roles[ADMIN_LEVEL] )) {
            echo "[<a href=\"operacija3.php\">Vartototojai</a>] &nbsp;&nbsp;";
       		}   
        //Administratoriaus sąsaja rodoma tik administratoriui
        if ($userlevel == $user_roles[ADMIN_LEVEL] ) {
            echo "[<a href=\"admin.php\">Administratoriaus sąsaja</a>] &nbsp;&nbsp;";
        }
       if ($_SESSION['user'] != "guest")  echo "[<a href=\"logout.php\">Atsijungti</a>]";
      echo "</td></tr></table>";
?>       
    
 