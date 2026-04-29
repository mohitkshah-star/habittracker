<?php
include "config.php";

$message="Invalid verification link.";
$target="login.php";

if(isset($_GET['token'])){
    $token=mysqli_real_escape_string($conn,$_GET['token']);
    $result=mysqli_query($conn,"SELECT * FROM users WHERE token='$token'");

    if(mysqli_num_rows($result)==1){
        mysqli_query($conn,"UPDATE users SET status=1 WHERE token='$token'");
        $message="Account verified successfully. You can login now.";
    }else{
        $message="Verification failed or link already used.";
    }
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page auth-flow">
    <section class="auth-panel">
        <p class="eyebrow">Verification</p>
        <h2>Email Verification</h2>
        <p class="alert alert-success"><?php echo htmlspecialchars($message); ?></p>
        <a class="btn-link" href="<?php echo $target; ?>">Go to Login</a>
    </section>
</main>
