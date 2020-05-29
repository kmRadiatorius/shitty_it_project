<?php
// operacija2.php
// tiesiog rodomas  tekstas ir nuoroda atgal

session_start();

if (!isset($_SESSION['prev']) || ($_SESSION['prev'] != "index"))
{ header("Location: logout.php");exit;}

?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Operacija 2</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
        <table class="center" ><tr><td> <center><img src="../include/top.png"></center> </td></tr><tr><td>

      <table style="border-width: 2px; border-style: dotted;"><tr><td>
         Atgal į [<a href="index.php">Pradžia</a>]
      </td></tr></table><br>
			
		<div style="text-align: center;color:green"> <br><br>
            <h1>Operacija 2.</h1>
			Tuščias puslapis, tik nuoroda į pradžią. 
        </div><br>
