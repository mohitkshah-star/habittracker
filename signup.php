<?php
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$message="";

if (isset($_POST['signup'])) {
    $username=mysqli_real_escape_string($conn,$_POST['username']);
    $name=mysqli_real_escape_string($conn,$_POST['name']);
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $password=$_POST['password'];

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $message="Username can only contain letters, numbers, and underscores.";
    }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $message="Invalid email format.";
    }elseif(strlen($password)<6){
        $message="Password must be at least 6 characters.";
    }else{
        $check_query="SELECT * FROM users WHERE username='$username' OR email='$email'";
        $check_result=mysqli_query($conn,$check_query);

        if (mysqli_num_rows($check_result)>0) {
            $message="Username or email already exists.";
        }else{
            $hashed_password=password_hash($password,PASSWORD_DEFAULT);
            $token=bin2hex(random_bytes(16));
            $sql="INSERT INTO users (username, name, email, password, token) VALUES ('$username', '$name', '$email', '$hashed_password', '$token')";

            if (mysqli_query($conn,$sql)) {
                $verify_link="http://localhost/habit-social-app/verify.php?token=$token";
                $mail=new PHPMailer(true);

                try{
                    $mail->isSMTP();
                    $mail->Host='smtp.gmail.com';
                    $mail->SMTPAuth=true;
                    $mail->Username='mohitm416x@gmail.com';
                    $mail->Password='mwdj mpvj soxw dviw';
                    $mail->SMTPSecure='tls';
                    $mail->Port=587;

                    $mail->setFrom('mohitm416x@gmail.com','Habit Tracker');
                    $mail->addAddress($email,$username);
                    $mail->isHTML(true);
                    $mail->Subject='Welcome to Habit Tracker';
                    $mail->Body="
                        <h2>Welcome $username</h2>
                        <p>Your account has been created successfully.</p>
                        <p>Click to verify your account:</p>
                        <a href='$verify_link'>Verify Account</a>
                    ";

                    $mail->send();
                    header("Location: login.php");
                    exit();
                }catch(Exception $e){
                    $message="Signup complete, but email could not be sent. Development verify link: $verify_link";
                }
            }else{
                $message="Signup failed. Please try again.";
            }
        }
    }
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page auth-flow">
    <section class="auth-panel">
        <p class="eyebrow">Habit Tracker</p>
        <h2>Create Account</h2>
        <p>Start tracking habits and building streaks.</p>

        <?php if(!empty($message)){ ?>
            <p class="alert alert-warning"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <form class="profile-edit-form" method="POST">
            <label for="username">Username</label>
            <div class="input-with-icon">
                <span>@</span>
                <input id="username" type="text" name="username" placeholder="username" required>
            </div>

            <label for="name">Full Name</label>
            <div class="input-with-icon">
                <span>+</span>
                <input id="name" type="text" name="name" placeholder="Full name" required>
            </div>

            <label for="email">Email</label>
            <div class="input-with-icon">
                <span>#</span>
                <input id="email" type="email" name="email" placeholder="Email" required>
            </div>

            <label for="password">Password</label>
            <div class="input-with-icon">
                <span>*</span>
                <input id="password" type="password" name="password" placeholder="Password" required>
            </div>

            <button name="signup">Sign Up</button>
        </form>

        <div class="auth-links">
            <a href="login.php">Already have an account?</a>
        </div>
    </section>
</main>
