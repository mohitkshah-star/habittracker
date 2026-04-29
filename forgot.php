<?php
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$message="";
$alert_type="alert-success";
$dev_link="";

if(isset($_POST['submit'])){
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $token=bin2hex(random_bytes(16));

    $check=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check)==0){
        $message="If this email exists, a reset link will be sent.";
    }else{
        mysqli_query($conn,"UPDATE users SET reset_token='$token' WHERE email='$email'");

        $scheme=(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host=$_SERVER['HTTP_HOST'];
        $path=rtrim(dirname($_SERVER['PHP_SELF']),"/\\");
        $base_url=$scheme . "://" . $host . $path . "/reset.php?token=" . urlencode($token);

        $mail= new PHPMailer(true);

        try{
            $mail->isSMTP();
            $mail->Host='smtp.gmail.com';
            $mail->SMTPAuth=true;
            $mail->Username='mohitm416x@gmail.com';
            $mail->Password='mwdj mpvj soxw dviw';
            $mail->SMTPSecure='tls';
            $mail->Port=587;

            $mail->setFrom('mohitm416x@gmail.com','Habit Tracker');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject='Reset your Habit Tracker password';
            $mail->Body="
                <h3>Reset your password</h3>
                <p>Click the link below to set a new password.</p>
                <a href='$base_url'>Reset Password</a>
            ";

            $mail->send();
            $message="Password reset link sent. Check your email.";
        }catch(Exception $e){
            $alert_type="alert-warning";
            $message="Mail could not be sent from this local setup. Use the development reset link below.";
            $dev_link=$base_url;
        }
    }
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page auth-flow">
    <section class="page-toolbar">
        <div>
            <p class="eyebrow">Account Recovery</p>
            <h2>Forgot Password</h2>
        </div>
        <a class="btn-link btn-muted" href="login.php">Back to Login</a>
    </section>

    <?php if(!empty($message)){ ?>
        <p class="alert <?php echo $alert_type; ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <?php if(!empty($dev_link)){ ?>
        <p class="empty-note">Development reset link: <a href="<?php echo htmlspecialchars($dev_link); ?>">Open reset page</a></p>
    <?php } ?>

    <form class="profile-edit-form" method="POST">
        <label for="email">Email Address</label>
        <div class="input-with-icon">
            <span>#</span>
            <input id="email" type="email" name="email" required placeholder="Enter your email">
        </div>
        <button name="submit">Send Reset Link</button>
    </form>
</main>
