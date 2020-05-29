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
                    Atgal į [<a href="../index.php">Pradžią</a>]
                </td>
            </tr>
        </table><br>
		<div style="text-align: center;color:green"> <br><br>
            <h1>Diskusija</h1>
        </div><br>
        <center>
            <p>Pasirinkite įkelti paveikslėlį:</p>
            <form action="../apis/uploadImage.php" method="post" enctype="multipart/form-data">
                <div>
                    <input type="file" name="file">
                    <input type="submit" name="submit" value="Upload">
                </div>
            </form>
        </center>
    </body>
</html>