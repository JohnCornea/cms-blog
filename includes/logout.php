<?php session_start(); ?>

<?php

//empty the session by assigning null
$_SESSION['user_name'] =  null;
$_SESSION['firstname'] =  null;
$_SESSION['lastname'] =  null;
$_SESSION['user_role'] =  null;

header("Location: ../index.php");

?>
