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
        <title>Masinis žinučių siuntimas</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
    <table style="border-width: 2px; border-style: dotted;"><tr><td>
         Atgal į [<a href="..\index.php">Pradžia</a>]
      </td></tr>
	</table><br>
			
		<div style="text-align: center;color:green"> <br><br>
            <h1 style="margin-bottom: 50px;">Masinis žinučių siuntimas</h1>
            <form>
                <center>
                    <textarea id="message" rows="3" cols="50"></textarea>
                </center>
                <button onClick="submit()">Siųsti visiems vartotojams</button>
            </form>
        </div><br>
    </body>
</html>

<script>
    const textField = document.getElementById("message");
    
    function submit() {
        const text = JSON.stringify(textField.value);
        const sentFrom = "<?php Print($_SESSION['user']); ?>";
        let url = "http://vartvald/apis/massSendingApi.php";
        url+="?message="+text+"&sentFrom="+sentFrom;
        url = encodeURI(url);
        //console.log(url);
        
        fetch(url).then(response => {
            return response.json()
        }).then(data => {
            //console.log(data);
        })
        
        return false;
    }
</script>
