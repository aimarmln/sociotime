<?php 
session_start();

unset($_SESSION["userId"]);
$_SESSION["signOut"] = true;

header("Location: sign-in.php");
exit;
?>