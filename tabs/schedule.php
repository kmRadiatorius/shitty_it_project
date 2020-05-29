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
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<style>

</style>
</head>
  
<?php
  class Time {
    public $regId;
    public $timeFrom;
    public $timeTo;
    public $weekDay;
    public $groupId;
  }

  class Group {
    public $groupId;
    public $groupName;
    public $maxReg;
    public $totalReg;
  }
  
  $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
  mysqli_set_charset($db,"utf8");
  $sql = "SELECT danceGroupId FROM users WHERE username='".$_SESSION['user']."'";
  $result = mysqli_query($db, $sql);
  if (!$result) {
    echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
    exit;
  }
  $row = mysqli_fetch_assoc($result);
  $userGroupId = $row['danceGroupId'];

  $sql = "SELECT danceGroup.id as danceGroupId, groupName, maxRegistrations, totalRegistrations
          FROM danceGroup
          ORDER BY groupName ASC;";
  $result = mysqli_query($db, $sql);
  if (!$result) {
    echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
    exit;
  }

  $groups = array();
  $userGroup = new Group();
  {while ($row = mysqli_fetch_assoc($result)) {
    $t = new Group();
    $t->groupId = $row['danceGroupId'];
    $t->groupName = $row['groupName'];
    $t->maxReg = $row['maxRegistrations'];
    $t->totalReg = $row['totalRegistrations'];

    if ($t->groupId == $userGroupId){
      $userGroup = $t;
    }
    array_push($groups, $t);
  }};

  $sql = "SELECT registration.id as registrationId, timeFrom, timeTo, weekDay, danceGroupId as groupId
          FROM registration
          ORDER BY weekDay ASC";
  $result = mysqli_query($db, $sql);
  if (!$result) {
    echo " DB klaida įrašant timestamp: " . $sql . "<br>" . mysqli_error($db);
    exit;
  }

  $times = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $t = new Time();
    $t->regId = $row['registrationId'];
    $t->timeFrom = $row['timeFrom'];
    $t->timeTo = $row['timeTo'];
    $t->weekDay = $row['weekDay'];
    $t->groupId = $row['groupId'];
    
    array_push($times, $t);
  }
?>
  
<body>
  <table style="border-width: 2px; border-style: dotted;"><tr><td>
         Atgal į [<a href="..\index.php">Pradžia</a>]
      </td></tr>
	</table><br>
  
  <div style="text-align: center;color:green"> <br><br>
      <h1>Tvarkaraštis</h1>	
  </div><br>
  
  <div>
    <?php
    if ($userGroupId != 0) {
		echo '<p>Jūs užsiregistravote į grupę "'.$userGroup->groupName.'" 
		<a href="cancelReg.php?groupId='.$userGroupId.'&totalReg='.$userGroup->totalReg.'">Atšaukti registraciją</a></p>';
		                
    } else {
		echo '<p>Jūs nesate užsiregistravę į užsiėmimą. Registruokitės žemiau</p>';
    }
    ?>
  </div>

  <table class="table table-bordered">
    <tr>
      <th style="width: 50px"></th>
      <th>Grupė</th>
      <th>Pirmadienis</th>
      <th>Antradienis</th>
      <th>Trečiadienis</th>
      <th>Ketvirtadienis</th>
      <th>Penktadienis</th>
      <th>Šeštadienis</th>
      <th>Sekmadienis</th>
      <th>Užsiregistravo</th>
      <th>Liko vietų</th>
    </tr>
    <?php
    foreach ($groups as $group) {
      if ($group->groupId == $userGroupId) {
        echo "<tr style='background-color: yellow;'>";
        echo "<td></td>";
      }
      else {
        echo "<tr>";
        if ($group->maxReg - $group->totalReg > 0) {
        echo "<td style='background-color: white'>
                <a href='timetable.php?groupId=$group->groupId&totalReg=$group->totalReg&prevGroupId=$userGroupId&prevGroupTotalReg=$userGroup->totalReg'>Registruotis</a>
              </td>";
        } else {
         echo '<td></td>'; 
        }
      }
      echo "<td>".$group->groupName."</td>";
      
      $previ = 1;
      foreach ($times as $time) {
        if ($time->groupId == $group->groupId) {
           for ($i = $previ; $i < $time->weekDay; $i++) {
             echo '<td></td>';
           }
          echo "<td>".$time->timeFrom."-".$time->timeTo."</td>";
          $previ = $time->weekDay + 1;
        }
      }
      
      for ($i = $previ; $i <= 7; $i++) {
             echo '<td></td>';
      }
      
      echo '<td>'.$group->totalReg.'</td>';
      $regLeft = $group->maxReg - $group->totalReg;
      echo '<td>'.$regLeft.'</td>';
      echo "</tr>";
    }
    ?>
  </table>
</body>
</html>
