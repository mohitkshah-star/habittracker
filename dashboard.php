<?php
include "config.php";
include "habit_utils.php";
session_start();

if (!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user=$_SESSION['user'];
$safe_user=mysqli_real_escape_string($conn,$user);

if(isset($_POST['add_habit'])){
    $habit=mysqli_real_escape_string($conn,$_POST['habit_name']);
    $goal=(int)$_POST['goal'];
    if($goal<1){ $goal=30; }

    mysqli_query($conn,"INSERT INTO habits(username,habit_name,goal) VALUES('$safe_user','$habit','$goal')");
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['post'])){
    $content=mysqli_real_escape_string($conn,$_POST['content']);
    $image_name="";

    if(isset($_FILES['image']) && !empty($_FILES['image']['name'])){
        $image_name=basename($_FILES['image']['name']);
        $tmp=$_FILES['image']['tmp_name'];
        move_uploaded_file($tmp,"uploads/" . $image_name);
    }

    mysqli_query($conn,"INSERT INTO posts(username,content,image) VALUES('$safe_user','$content','$image_name')");
    header("Location: dashboard.php");
    exit();
}

$habits=mysqli_query($conn,"SELECT * FROM habits WHERE username='$safe_user' ORDER BY id DESC");
$posts=mysqli_query($conn,"SELECT * FROM posts ORDER BY id DESC");

$total_habits=mysqli_num_rows($habits);
$today=date("Y-m-d");
$done_today_result=mysqli_query($conn,"SELECT COUNT(DISTINCT habit_id) as total FROM habit_logs WHERE username='$safe_user' AND status='$today'");
$done_today=mysqli_fetch_assoc($done_today_result)['total'];
$today_rate=($total_habits>0) ? round(($done_today/$total_habits)*100) : 0;
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page dashboard-page">
    <section class="dashboard-hero">
        <div>
            <p class="eyebrow">Habit Tracker</p>
            <h2>Welcome <?php echo htmlspecialchars($user); ?></h2>
            <p>Build consistency one day at a time.</p>
        </div>
        <nav class="quick-actions">
            <a class="btn-link" href="profile.php">My Profile</a>
            <a class="btn-link" href="analytics.php">Analytics</a>
            <a class="btn-link btn-muted" href="logout.php">Logout</a>
        </nav>
    </section>

    <section class="stats-strip">
        <article>
            <span>Total Habits</span>
            <strong><?php echo $total_habits; ?></strong>
        </article>
        <article>
            <span>Done Today</span>
            <strong><?php echo $done_today; ?></strong>
        </article>
        <article>
            <span>Today Score</span>
            <strong><?php echo $today_rate; ?>%</strong>
        </article>
    </section>

    <form class="smart-form add-habit-form" method="POST">
        <div>
            <label for="habit_name">New Habit</label>
            <input id="habit_name" type="text" name="habit_name" placeholder="Morning walk, reading, meditation..." required>
        </div>
        <div>
            <label for="goal">Goal Days</label>
            <input id="goal" type="number" name="goal" placeholder="30" value="30" min="1">
        </div>
        <button type="submit" name="add_habit">Add Habit</button>
    </form>

    <section class="habit-board">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Today</p>
                <h3>Your Habits</h3>
            </div>
        </div>

        <?php if($total_habits==0){ ?>
            <p class="empty-note">No habits yet. Add your first habit above.</p>
        <?php } ?>

        <?php mysqli_data_seek($habits,0); ?>
        <?php while($row=mysqli_fetch_assoc($habits)){ ?>
            <?php
                $stats=habit_stats($conn,$user,$row['id'],$row['goal']);
                $progress_color=$stats['progress']>=70 ? "green" : ($stats['progress']>=40 ? "orange" : "red");
            ?>
            <article class="habit-card-modern">
                <div class="habit-card-top">
                    <div>
                        <h3><?php echo htmlspecialchars($row['habit_name']); ?></h3>
                        <p>Goal: <?php echo (int)$row['goal']; ?> days</p>
                    </div>
                    <?php if($stats['done_today']){ ?>
                        <span class="status-pill done">Done Today</span>
                    <?php }else{ ?>
                        <a class="btn-link mark-today-btn" href="mark_done.php?id=<?php echo (int)$row['id']; ?>">Mark Today</a>
                    <?php } ?>
                </div>

                <div class="habit-metrics">
                    <span><strong><?php echo $stats['current_streak']; ?></strong> day streak</span>
                    <span><strong><?php echo $stats['total_done']; ?></strong> total days</span>
                    <span><strong><?php echo $stats['progress']; ?>%</strong> goal</span>
                </div>

                <div class="week-row">
                    <?php foreach($stats['last7'] as $day){ ?>
                        <span class="<?php echo $day['done'] ? 'day-dot done' : 'day-dot'; ?>" title="<?php echo htmlspecialchars($day['date']); ?>"></span>
                    <?php } ?>
                </div>

                <div class="progress-track">
                    <div class="progress-fill-modern <?php echo $progress_color; ?>" style="width: <?php echo $stats['progress']; ?>%;"></div>
                </div>
            </article>
        <?php } ?>
    </section>

    <section class="social-panel">
        <form class="smart-form post-form-modern" method="POST" enctype="multipart/form-data">
            <label for="content">Share Progress</label>
            <textarea id="content" name="content" placeholder="Share your habit progress..." required></textarea>
            <input type="file" name="image">
            <button type="submit" name="post">Post Update</button>
        </form>

        <div class="section-heading">
            <div>
                <p class="eyebrow">Community</p>
                <h3>Recent Posts</h3>
            </div>
        </div>

        <?php while($row=mysqli_fetch_assoc($posts)){ ?>
            <article class="clean-post">
                <b><a href="view_profile.php?user=<?php echo urlencode($row['username']); ?>"><?php echo htmlspecialchars($row['username']); ?></a></b>
                <?php if(!empty($row['image'])){ ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Post Image">
                <?php } ?>
                <p><?php echo htmlspecialchars($row['content']); ?></p>
                <?php if ($row['username']==$_SESSION['user']){ ?>
                    <a class="danger-link" href="delete.php?id=<?php echo (int)$row['id']; ?>">Delete</a>
                <?php } ?>
            </article>
        <?php } ?>
    </section>
</main>
