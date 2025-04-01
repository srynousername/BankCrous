<?php
$host = "localhost";
$user = "root";
$pwd = "";
$dbname = "bankcrous";

try {
	$mysqli = new mysqli($host,$user,$pwd,$dbname);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno) {
        echo "<script>alert(" . $mysqli->connect_error . ")</script>";
        exit();
    }
} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>