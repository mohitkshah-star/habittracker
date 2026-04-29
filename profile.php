<?php
include "config.php";
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$username=$_SESSION['user'];
$safe_username=mysqli_real_escape_string($conn,$username);

$result=mysqli_query($conn,"SELECT * FROM users WHERE username='$safe_username'");
$user=mysqli_fetch_assoc($result);

$post_query="SELECT * FROM posts WHERE username='$safe_username' ORDER BY id DESC";
$post_result=mysqli_query($conn,$post_query);
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page profile-page">
    <section class="profile-hero">
        <div class="profile-photo-wrap">
            <?php if(!empty($user['profile_pic'])){ ?>
                <img class="profile-photo" src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">
            <?php }else{ ?>
                <div class="profile-photo profile-photo-empty"><?php echo strtoupper(substr($username,0,1)); ?></div>
            <?php } ?>
        </div>

        <div class="profile-summary">
            <p class="eyebrow">My Profile</p>
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p><strong><?php echo htmlspecialchars($user['name'] ?? ""); ?></strong></p>
            <p><?php echo !empty($user['bio']) ? htmlspecialchars($user['bio']) : "No bio added yet."; ?></p>
            <span class="status-pill"><?php echo ucfirst(htmlspecialchars($user['privacy'] ?? "public")); ?> account</span>
        </div>

        <div class="profile-actions">
            <a class="btn-link" href="dashboard.php">Dashboard</a>
            <a class="btn-link" href="profile_edit.php">Edit Profile</a>
            <a class="btn-link btn-muted" href="logout.php">Logout</a>
        </div>
    </section>

    <section class="profile-posts">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Timeline</p>
                <h3>Your Posts</h3>
            </div>
        </div>

        <?php if(mysqli_num_rows($post_result)==0){ ?>
            <p class="empty-note">You have not posted anything yet.</p>
        <?php } ?>

        <?php while($row=mysqli_fetch_assoc($post_result)){ ?>
            <article class="clean-post">
                <?php if(!empty($row['image'])){ ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Post Image">
                <?php } ?>
                <p><?php echo htmlspecialchars($row['content']); ?></p>
            </article>
        <?php } ?>
    </section>

    <form class="danger-zone" method="POST" action="delete_account.php">
        <h3>Danger Zone</h3>
        <p>Delete your account, habits, logs, and posts.</p>
        <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            Delete Account
        </button>
    </form>
</main>
