<?php
include 'config.php';
session_start();

$message="";

if (isset($_POST['login'])){
    $username=mysqli_real_escape_string($conn,$_POST['username']);
    $password=$_POST['password'];

    $query="SELECT * FROM users WHERE username='$username'";
    $result=mysqli_query($conn,$query);

    if (mysqli_num_rows($result)==1) {
        $user=mysqli_fetch_assoc($result);

        if(isset($user['status']) && $user['status']==0){
            $message="Please verify your email before logging in.";
        }elseif(password_verify($password,$user['password'])) {
            $_SESSION['user']=$username;
            header("Location: dashboard.php");
            exit();
        }else{
            $message="Incorrect password. Please try again.";
        }
    }else{
        $message="Username not found. Please sign up first.";
    }
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page auth-flow">
    <section class="auth-panel">
        <p class="eyebrow">Habit Tracker</p>
        <h2>Welcome Back</h2>
        <p>Login and keep your streak alive.</p>

        <?php if(!empty($message)){ ?>
            <p class="alert alert-warning"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <form class="profile-edit-form" method="POST">
            <label for="username">Username</label>
            <div class="input-with-icon">
                <span>@</span>
                <input id="username" type="text" name="username" placeholder="username" required>
            </div>

            <label for="password">Password</label>
            <div class="input-with-icon">
                <span>*</span>
                <input id="password" type="password" name="password" placeholder="Password" required>
            </div>

            <button name="login">Login</button>
        </form>

        <div class="auth-links">
            <a href="signup.php">Create account</a>
            <a href="forgot.php">Forgot password?</a>
        </div>
    </section>
</main>
