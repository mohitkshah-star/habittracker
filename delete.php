<?php

include "config.php";
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])){
    $id=(int)$_GET['id'];
    $user=mysqli_real_escape_string($conn,$_SESSION['user']);
    mysqli_query($conn,"DELETE FROM posts WHERE id=$id AND username='$user'");
}

header("Location: dashboard.php");
exit();
?>
