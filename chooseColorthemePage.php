<?php
session_start();
include "include.php\colorthemeFunctions.inc.php";
if(!isset($_SESSION["Nutzername"])) //Nutzer ist nicht eingeloggt
{
	header("Location: index.php");
}
else
{
	$pdo = new PDO("mysql:host=localhost;dbname=Chatinga;charset=utf8", "root", "");
    $themes = retrieveAllThemes($pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="css/loginScript.css">
	<meta charset="UTF-8">
	<title>Chatinga - Farbthema auswählen</title>
</head>
<body>
<?php foreach($themes as $theme): ?>
	<a href="chooseColorthemeScript.php?themeID=<?= $theme["FarbthemaID"] ?>"><?= $theme["Name"] ?></a>
<?php endforeach; ?>
</body>
</html>