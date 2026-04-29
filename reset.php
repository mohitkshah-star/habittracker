<?php
include "config.php";

$message="";
$alert_type="alert-warning";
$valid_token=false;
$password_updated=false;
$token="";

if(isset($_GET['token'])){
    $token=mysqli_real_escape_string($conn,$_GET['token']);
    $result=mysqli_query($conn,"SELECT * FROM users WHERE reset_token='$token'");
    $valid_token=mysqli_num_rows($result)>0;
}

if(!$valid_token){
    $message="Invalid or expired reset link.";
}elseif(isset($_POST['reset'])){
    $password=$_POST['newpass'];
    $confirm=$_POST['confirm_pass'];

    if(strlen($password)<6){
        $message="Password must be at least 6 characters.";
    }elseif($password !== $confirm){
        $message="Passwords do not match.";
    }else{
        $newpass=password_hash($password,PASSWORD_DEFAULT);
        mysqli_query($conn,"UPDATE users SET password='$newpass', reset_token=NULL WHERE reset_token='$token'");
        $message="Password reset successfully. You can login now.";
        $alert_type="alert-success";
        $valid_token=false;
        $password_updated=true;
    }
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page auth-flow">
    <section class="page-toolbar">
        <div>
            <p class="eyebrow">Account Recovery</p>
            <h2>Reset Password</h2>
        </div>
        <a class="btn-link btn-muted" href="login.php">Back to Login</a>
    </section>

    <?php if(!empty($message)){ ?>
        <p class="alert <?php echo $alert_type; ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <?php if($valid_token){ ?>
        <form class="profile-edit-form" method="POST">
            <label for="newpass">New Password</label>
            <div class="input-with-icon">
                <span>*</span>
                <input id="newpass" type="password" name="newpass" placeholder="Enter new password" required>
            </div>

            <label for="confirm_pass">Confirm Password</label>
            <div class="input-with-icon">
                <span>*</span>
                <input id="confirm_pass" type="password" name="confirm_pass" placeholder="Confirm new password" required>
            </div>

            <button name="reset">Update Password</button>
        </form>
    <?php }elseif($password_updated){ ?>
        <p class="empty-note"><a class="btn-link" href="login.php">Login Now</a></p>
    <?php }else{ ?>
        <p class="empty-note"><a href="forgot.php">Request a new reset link</a></p>
    <?php } ?>
</main>
