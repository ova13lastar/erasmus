<?php
$host = 'localhost';
$user = 'appuser';
$passwd = 'appuser624';
$dbname = 'ficheu';
		
$conn = new mysqli($host, $user, $passwd, $dbname);
echo "<pre>";
if ($conn->connect_error) {
    die('Erreur de connexion (' . $conn->connect_errno . ') ' . $conn->connect_error);
}
$resultat = $conn->query("SELECT * FROM migration WHERE active = 1") or die ('Erreur '.$requete.' '.$conn->error);
$ligne = $resultat->fetch_assoc();
if ($ligne['num_version'] != "") {
    echo "CONNECTION OK !";
} else {
    echo "CONNECTION IMPOSSIBLE !";
}
echo "<br>---------------------------------------<br>";
var_dump($_SERVER);
echo "</pre>";
mysqli_close($conn);