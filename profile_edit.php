<?php
include "config.php";
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user=$_SESSION['user'];
$message="";
$safe_user=mysqli_real_escape_string($conn,$user);

if(isset($_POST['update'])){
    $new_username=mysqli_real_escape_string($conn,$_POST['username']);
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $bio=mysqli_real_escape_string($conn,$_POST['bio']);
    $privacy=mysqli_real_escape_string($conn,$_POST['privacy']);
    $newpass=$_POST['newpass'];

    mysqli_query($conn,"UPDATE users SET username='$new_username', email='$email', bio='$bio', privacy='$privacy' WHERE username='$safe_user'");

    if($new_username != $user){
        mysqli_query($conn,"UPDATE posts SET username='$new_username' WHERE username='$safe_user'");
        mysqli_query($conn,"UPDATE habits SET username='$new_username' WHERE username='$safe_user'");
        mysqli_query($conn,"UPDATE habit_logs SET username='$new_username' WHERE username='$safe_user'");
    }

    if(!empty($newpass)){
        $hashed=password_hash($newpass,PASSWORD_DEFAULT);
        mysqli_query($conn,"UPDATE users SET password='$hashed' WHERE username='$new_username'");
    }

    if(isset($_FILES['profile_pic']) && !empty($_FILES['profile_pic']['name'])){
        $filename=basename($_FILES['profile_pic']['name']);
        $tmp=$_FILES['profile_pic']['tmp_name'];
        $folder="uploads/" . $filename;
        move_uploaded_file($tmp,$folder);
        mysqli_query($conn,"UPDATE users SET profile_pic='$filename' WHERE username='$new_username'");
    }

    $_SESSION['user']=$new_username;
    $user=$new_username;
    $safe_user=mysqli_real_escape_string($conn,$user);
    $message="Profile updated successfully.";
}

$result=mysqli_query($conn,"SELECT * FROM users WHERE username='$safe_user'");
$row=mysqli_fetch_assoc($result);
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page profile-page">
    <section class="page-toolbar">
        <div>
            <p class="eyebrow">Account Settings</p>
            <h2>Edit Profile</h2>
        </div>
        <a class="btn-link btn-muted" href="profile.php">Back to Profile</a>
    </section>

    <?php if(!empty($message)){ ?>
        <p class="alert alert-success"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <form class="profile-edit-form" method="POST" enctype="multipart/form-data">
        <label for="username">Username</label>
        <div class="input-with-icon">
            <span>@</span>
            <input id="username" type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
        </div>

        <label for="email">Email</label>
        <div class="input-with-icon">
            <span>#</span>
            <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($row['email']);?>" required>
        </div>

        <label for="bio">Bio</label>
        <textarea id="bio" name="bio" placeholder="Write your bio..."><?php echo htmlspecialchars($row['bio']);?></textarea>

        <label for="privacy">Profile Visibility</label>
        <select id="privacy" name="privacy" required>
            <option value="public" <?php if(($row['privacy'] ?? "public")=="public") echo "selected"; ?>>Public</option>
            <option value="private" <?php if(($row['privacy'] ?? "public")=="private") echo "selected"; ?>>Private</option>
        </select>

        <label for="profile_pic">Profile Photo</label>
        <input id="profile_pic" type="file" name="profile_pic">

        <label for="newpass">New Password</label>
        <div class="input-with-icon">
            <span>*</span>
            <input id="newpass" type="password" name="newpass" placeholder="Leave blank to keep current password">
        </div>

        <button type="submit" name="update">Save Changes</button>
    </form>
</main>
