<!-- DB Connection -->
<?php include "includes/db.php"; ?>
<!-- Header -->
<?php include "includes/header.php"; ?>
<?php require_once "./admin/functions.php"; ?>
<?php session_start(); ?>
<?php

echo loggedInUserId();

 if (userLikedThisPost(94)) {
     echo "USER LIKED THIS POSTERINO";
 } else {
     echo  "USER DID NOT LIKED THE POST";
 }

?>

