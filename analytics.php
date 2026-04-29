<?php
include "config.php";
include "habit_utils.php";
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user=$_SESSION['user'];
$safe_user=mysqli_real_escape_string($conn,$user);

$habits=mysqli_query($conn,"SELECT * FROM habits WHERE username='$safe_user' ORDER BY id DESC");
$total_habits=mysqli_num_rows($habits);

$habit_cards=[];
$best_habit="None";
$best_streak=0;
$attention_habit="None";
$lowest_week_rate=101;
$active_streaks=0;
$total_week_done=0;
$total_week_possible=$total_habits*7;
$goal_total=0;
$done_total=0;
$week_counts=[];

for($i=6;$i>=0;$i--){
    $date=date("Y-m-d",strtotime("-$i days"));
    $week_counts[$date]=0;
}

while($habit=mysqli_fetch_assoc($habits)){
    $stats=habit_stats($conn,$user,$habit['id'],$habit['goal']);
    $week_done=0;

    foreach($stats['last7'] as $day){
        if($day['done']){
            $week_done++;
            $week_counts[$day['date']]++;
        }
    }

    $week_rate=round(($week_done/7)*100);
    $goal_total+=(int)$habit['goal'];
    $done_total+=$stats['total_done'];

    if($stats['current_streak']>0){
        $active_streaks++;
    }

    if($stats['current_streak']>$best_streak){
        $best_streak=$stats['current_streak'];
        $best_habit=$habit['habit_name'];
    }

    if($week_rate<$lowest_week_rate){
        $lowest_week_rate=$week_rate;
        $attention_habit=$habit['habit_name'];
    }

    $total_week_done+=$week_done;

    $habit_cards[]=[
        "name"=>$habit['habit_name'],
        "goal"=>(int)$habit['goal'],
        "stats"=>$stats,
        "week_done"=>$week_done,
        "week_rate"=>$week_rate
    ];
}

$weekly_rate=($total_week_possible>0) ? round(($total_week_done/$total_week_possible)*100) : 0;
$goal_progress=($goal_total>0) ? min(100,round(($done_total/$goal_total)*100)) : 0;
$streak_health=($total_habits>0) ? round(($active_streaks/$total_habits)*100) : 0;
$best_day="None";
$best_day_count=-1;

foreach($week_counts as $date=>$count){
    if($count>$best_day_count){
        $best_day_count=$count;
        $best_day=date("D, M j",strtotime($date));
    }
}

usort($habit_cards,function($a,$b){
    if($a['week_rate']==$b['week_rate']){
        return $b['stats']['current_streak'] <=> $a['stats']['current_streak'];
    }
    return $b['week_rate'] <=> $a['week_rate'];
});
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<main class="app-page analytics-page">
    <section class="page-toolbar">
        <div>
            <p class="eyebrow">Deep Insights</p>
            <h2>Habit Analytics</h2>
        </div>
        <a class="btn-link btn-muted" href="dashboard.php">Back to Dashboard</a>
    </section>

    <?php if($total_habits==0){ ?>
        <p class="empty-note">No habits to analyze yet. Add habits from your dashboard first.</p>
    <?php }else{ ?>
        <section class="insight-grid">
            <article class="insight-card compact">
                <p class="eyebrow">7-Day Completion</p>
                <h3><?php echo $weekly_rate; ?>%</h3>
                <p><?php echo $total_week_done; ?> check-ins from <?php echo $total_week_possible; ?> possible habit-days.</p>
            </article>

            <article class="insight-card compact">
                <p class="eyebrow">Streak Health</p>
                <h3><?php echo $streak_health; ?>%</h3>
                <p><?php echo $active_streaks; ?> of <?php echo $total_habits; ?> habits still have an active streak.</p>
            </article>

            <article class="insight-card compact">
                <p class="eyebrow">Best Streak</p>
                <h3><?php echo htmlspecialchars($best_habit); ?></h3>
                <p><?php echo $best_streak; ?> consecutive day<?php echo $best_streak==1 ? "" : "s"; ?>.</p>
            </article>

            <article class="insight-card compact warning-card">
                <p class="eyebrow">Needs Attention</p>
                <h3><?php echo htmlspecialchars($attention_habit); ?></h3>
                <p><?php echo $lowest_week_rate; ?>% completion in the last 7 days.</p>
            </article>
        </section>

        <section class="analytics-panel">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Trend</p>
                    <h3>Last 7 Days</h3>
                </div>
                <span class="status-pill">Best day: <?php echo htmlspecialchars($best_day); ?></span>
            </div>

            <div class="trend-bars">
                <?php foreach($week_counts as $date=>$count){ ?>
                    <?php $height=($total_habits>0) ? max(8,round(($count/$total_habits)*100)) : 8; ?>
                    <div class="trend-day">
                        <div class="trend-bar-wrap">
                            <span class="trend-bar" style="height: <?php echo $height; ?>%;"></span>
                        </div>
                        <strong><?php echo $count; ?></strong>
                        <small><?php echo date("D",strtotime($date)); ?></small>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section class="analytics-panel">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Goal Progress</p>
                    <h3>Overall Goal Completion</h3>
                </div>
                <span class="status-pill"><?php echo $goal_progress; ?>%</span>
            </div>
            <div class="progress-track large">
                <div class="progress-fill-modern green" style="width: <?php echo $goal_progress; ?>%;"></div>
            </div>
            <p><?php echo $done_total; ?> completed days across <?php echo $goal_total; ?> planned goal days.</p>
        </section>

        <section class="analytics-panel">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Consistency Ranking</p>
                    <h3>Habit Breakdown</h3>
                </div>
            </div>

            <div class="analytics-table">
                <div class="analytics-row analytics-head">
                    <span>Habit</span>
                    <span>7 Days</span>
                    <span>Streak</span>
                    <span>Goal</span>
                </div>

                <?php foreach($habit_cards as $card){ ?>
                    <div class="analytics-row">
                        <span>
                            <strong><?php echo htmlspecialchars($card['name']); ?></strong>
                            <small><?php echo $card['stats']['total_done']; ?> total check-ins</small>
                        </span>
                        <span><?php echo $card['week_done']; ?>/7 <small><?php echo $card['week_rate']; ?>%</small></span>
                        <span><?php echo $card['stats']['current_streak']; ?> days</span>
                        <span><?php echo $card['stats']['progress']; ?>%</span>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
</main>
