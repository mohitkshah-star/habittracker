<?php

include "config.php";
session_start();

echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link rel="stylesheet" href="style.css">';

if(!isset($_GET['user'])){
    echo "<main class='profile-page'><p class='alert alert-warning'>No user selected.</p></main>";
    exit();
}

$username=mysqli_real_escape_string($conn,$_GET['user']);
$query="SELECT * FROM users WHERE username='$username'";
$result=mysqli_query($conn,$query);
$user=mysqli_fetch_assoc($result);

if(!$user){
    echo "<main class='profile-page'><p class='alert alert-warning'>User not found.</p></main>";
    exit();
}

if($user['privacy']=='private' && (!isset($_SESSION['user']) || $username != $_SESSION['user'])){
    echo "<main class='profile-page'><p class='alert alert-warning'>This account is private.</p></main>";
    exit();
}

$post_query="SELECT * FROM posts WHERE username ='$username' ORDER BY id DESC";
$post_result=mysqli_query($conn,$post_query);
?>

<main class="profile-page">
    <section class="profile-hero">
        <div class="profile-photo-wrap">
            <?php if(!empty($user['profile_pic'])){ ?>
                <img class="profile-photo" src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">
            <?php }else{ ?>
                <div class="profile-photo profile-photo-empty"><?php echo strtoupper(substr($username,0,1)); ?></div>
            <?php } ?>
        </div>

        <div class="profile-summary">
            <p class="eyebrow">Public Profile</p>
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p><?php echo !empty($user['bio']) ? htmlspecialchars($user['bio']) : "No bio added yet."; ?></p>
        </div>

        <div class="profile-actions">
            <a class="btn-link" href="dashboard.php">Dashboard</a>
        </div>
    </section>

    <section class="profile-posts">
        <div class="section-heading">
            <h3>Posts</h3>
        </div>

        <?php if(mysqli_num_rows($post_result)==0){ ?>
            <p class="empty-note">No posts yet.</p>
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
</main>
