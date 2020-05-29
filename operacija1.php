<?php
// operacija1.php
// skirtapakeisti savo sudaryta operacija pratybose

session_start();
// cia sesijos kontrole
if (!isset($_SESSION['prev']) || ($_SESSION['prev'] != "index"))
{ header("Location:logout.php");exit;}

?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Operacija 1</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
    <table style="border-width: 2px; border-style: dotted;"><tr><td>
         Atgal į [<a href="index.php">Pradžia</a>]
      </td></tr>
	</table><br>
			
		<div style="text-align: center;color:green"> <br><br>
            <h1>Operacija 1.</h1>
			Čia turi būti jūsų operacija.<br> 
			Savo programą įkelkite į /home/stud/pratybos/demo/vartvald/<br>
			ir pakeiskite meniu.php šią eilutę:<br>
			<?php highlight_string('echo "[<a href=\"operacija1.php\">Demo operacija1</a>] &nbsp;&nbsp;";');  ?><br>
			Į meniu iš programos grįžtama html eilute:<br>
			<?php highlight_string('Atgal į [<a href="index.php">Pradžia</a>]');  ?>
			
			
        </div><br>
