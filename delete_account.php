<?php
include "config.php";
session_start();

if (!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$username=mysqli_real_escape_string($conn,$_SESSION['user']);

mysqli_query($conn,"DELETE FROM posts WHERE username='$username'");
mysqli_query($conn,"DELETE FROM habit_logs WHERE username='$username'");
mysqli_query($conn,"DELETE FROM habits WHERE username='$username'");

if(mysqli_query($conn,"DELETE FROM users WHERE username='$username'")){
    session_destroy();
    header("Location: login.php");
    exit();
}

echo "Error deleting account: " . mysqli_error($conn);
?>
