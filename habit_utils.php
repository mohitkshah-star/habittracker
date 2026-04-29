<?php

function habit_stats($conn, $username, $habit_id, $goal){
    $safe_user=mysqli_real_escape_string($conn,$username);
    $safe_habit=(int)$habit_id;
    $today=date("Y-m-d");
    $yesterday=date("Y-m-d",strtotime("-1 day"));

    $logs=mysqli_query($conn,"SELECT DISTINCT status FROM habit_logs WHERE username='$safe_user' AND habit_id='$safe_habit' ORDER BY status DESC");
    $dates=[];

    while($log=mysqli_fetch_assoc($logs)){
        $dates[$log['status']]=true;
    }

    $total_done=count($dates);
    $start=null;

    if(isset($dates[$today])){
        $start=$today;
    }elseif(isset($dates[$yesterday])){
        $start=$yesterday;
    }

    $current_streak=0;
    if($start){
        $cursor=$start;
        while(isset($dates[$cursor])){
            $current_streak++;
            $cursor=date("Y-m-d",strtotime($cursor . " -1 day"));
        }
    }

    $last7=[];
    for($i=6;$i>=0;$i--){
        $date=date("Y-m-d",strtotime("-$i days"));
        $last7[]=[
            "date"=>$date,
            "done"=>isset($dates[$date])
        ];
    }

    $progress=($goal>0) ? min(100,round(($total_done/$goal)*100)) : 0;

    return [
        "total_done"=>$total_done,
        "current_streak"=>$current_streak,
        "done_today"=>isset($dates[$today]),
        "last7"=>$last7,
        "progress"=>$progress
    ];
}

?>
