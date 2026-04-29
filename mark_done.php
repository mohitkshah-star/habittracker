<?php

include 'config.php';
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user=mysqli_real_escape_string($conn,$_SESSION['user']);
$habit_id=(int)$_GET['id'];
$date=date("Y-m-d");

$check=mysqli_query($conn,"SELECT * FROM habit_logs WHERE username='$user' AND habit_id='$habit_id' AND status='$date'");

if(mysqli_num_rows($check)==0){
    mysqli_query($conn,"INSERT INTO habit_logs (username, habit_id, status) VALUES ('$user', '$habit_id', '$date')");
}

header("Location: dashboard.php");
exit();

?>
