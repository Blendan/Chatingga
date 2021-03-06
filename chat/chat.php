<?php session_start(); ?>
<?php include "../include.php/colorthemeFunctions.inc.php"; ?>
<?php include "post.php";  ?>
<?php include "../include.php/parsedown-1.7.1/Parsedown.php" ?>

<?php
  $server ="mysql:dbname=chatinga;host=localhost";
  $user="root";
  $password = "";
  $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
  $pdo = new PDO($server,$user,$password,$options);

  function auslesen($pdo) //läd die posts und gibt den Post array zurück
  {
    $post = array();

    try
    {
      $rows = $pdo->query("SELECT * FROM nachricht WHERE Chatraum = ".$_GET["chatid"]);

      foreach ($rows as $key => $row)
      {
        $post[]  = new Post($row);
      }
    }
    catch (\Exception $e)
    {
      die("Insert ERROR:".$e->getMesage());
    }

    return $post;
  }
?>

<!DOCTYPE html>
<html lang="de" dir="ltr">
  <head>
  	<link rel="stylesheet" type="text/css" href="../css/<?= retrieveThemeFilename($_SESSION["gewaehltesThema"], $pdo)?>/chat.css">
    <meta charset="utf-8">
    <title></title>
    <script src="../js/jquery.js"></script>

  </head>
  <body>
    <button type="button" id="aktScrollToNew">Neue Nachrichten</button>
    <div id="messages">

      <?php
        $Parsedown = new Parsedown(); //für Markdown
        $Parsedown->setSafeMode(true); //damit werden HTML-Tags automatisch umgewandelt
        $last = -1;
        foreach (auslesen($pdo) as $key => $value)
        {
          // überprüft ob der Post vom angemeldeten nutzer kommt oder nicht und weist passende klasse für CSS zu
          if($value->getVerfasser()==$_SESSION["NutzerID"])
          {
            echo "<div class='postOwn'>";
          }
          else
          {
            echo "<div class='postOther'>";
          }

          // gibt die eingentliche Nachricht aus
          echo "<p class='user'>";
          echo $value->getVerfasserName();
          echo "</p>";
          echo "<p class='message'>";
          echo $Parsedown->text($value->getNachricht());
          echo "</p>";
		      echo "<p class='timestamp'>";
          echo $value->getZeitpunkt();
          echo "</p>";
          echo "</div>";

          $last = $value->getNachrichtID();
        }
       ?>
       <div id="newMessages"></div>
       <div id="scroolTo"></div>
    </div>
     <iframe id="scannForNew" src="scannForNew.php?chatid=<?php echo $_GET["chatid"]; ?>&last=<?php echo $last ?>" style="display: none;"></iframe>
     <script language="javascript" type="text/javascript">
       location.href = "#scroolTo"; // scrollt zum ende des chats

       window.setInterval('addNew();',1000);

       var autoToNew = true;
       var scrollLast = $("#messages").scrollTop();

       $(document).ready(
         function()
         {
           $("#messages").scroll(
             function()
             {
               if(scrollLast>$("#messages").scrollTop())
               {
                autoToNew = false; // wenn man selbst scrollt soll man nicht bei jenden neuladen weider ans ende geworfen werden
                $("#aktScrollToNew").show();
              }
             }
           );

           $("#aktScrollToNew").click(
             function()
             {
                autoToNew = true; // reaktiwirt das scrollen wieder
                location.href = "#scroolTo";
                $("#aktScrollToNew").hide();
                scrollLast = $("#messages").scrollTop();
             }
           );
         }
       );


       function addNew() // Zeigt die neu geladenene Posts an
       {
           document.getElementById("newMessages").innerHTML= document.getElementById('scannForNew').contentWindow.getMessages();
           if(autoToNew)
           {
             location.href = "#scroolTo";
             scrollLast = $("#messages").scrollTop();
           }
       }
     </script>
  </body>
</html>
