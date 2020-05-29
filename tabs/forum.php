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
    include("../include/login.php");                    // prisijungimo forma
          echo "</td></tr></table></div><br>";
}
?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Forumas</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    </head>
    <body>
        <table style="border-width: 2px; border-style: dotted;"><tr><td>
             Atgal į [<a href="..\index.php">Pradžia</a>]
          </td></tr>
        </table><br>	

        <div style="text-align: center;color:green"> <br><br>
            <h1>Forumas</h1>	
        </div><br>
        
        <div style="margin-left: 30px; margin-right: 30px; margin-top: 30px;">
            <?php
                $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
                mysqli_set_charset($db,"utf8");
                $sql = "SELECT id, username, age, experience, height, info, date
                        FROM forum
                        ORDER BY date DESC";
                $result = mysqli_query($db, $sql);
                if (!$result) {
                  echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
                  exit;
                } else {
                     while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div style="padding: 20px 20px 20px 20px; border-style: solid; border-width: 2px; margin-bottom: 5px;">
                                 <h3><a href="thread.php?username='.$row['username'].'">'.$row['info'].'</a></h3>';
                        echo '<h5>Naudotojas: '.$row['username'].' amžius: '.$row['age'].'m. patirtis: '
                            .$row['experience'].'m. ūgis: '.$row['height'].'m</h5>';
                        echo '</div>';
                     }
                }
            ?>
        </div>
        
    </body>
</html>

