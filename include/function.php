<?php
// 時間計算用関数作成
function minusTime($time1,$time2) {
    if(strtotime($time2) >= strtotime($time1)){
     //時間を「時」「分」「秒」に分ける
        $times1 = explode(":",$time1);
        $times2 = explode(":",$time2);
     //「時」を「秒」に変換
        $hour1 = $times1[0] * 3600;
        $hour2 = $times2[0] * 3600;
     //「分」を秒に変換
        $minutes1 = $times1[1] * 60;
        $minutes2 = $times2[1] * 60;
     // 秒にしたものを足す
        $result1 = $hour1 + $minutes1;
        $result2 = $hour2 + $minutes2;
        $result3 = $result2 - $result1;
     // 秒のtimestampを「mktime」で算出し、date関数で「H:i:s」に変換
        return date("H:i:s", mktime(0,0,$result3));
    }else{
        return date("H:i:s", mktime(0,0,0));
    }
}
function plusTime($time1,$time2) {
        $times1 = explode(":",$time1);
        $times2 = explode(":",$time2);      
        $hour1 = $times1[0] * 3600;
        $hour2 = $times2[0] * 3600;
        $minutes1 = $times1[1] * 60;
        $minutes2 = $times2[1] * 60;
        $result1 = $hour1 + $minutes1;
        $result2 = $hour2 + $minutes2;
        $result3 = $result2 + $result1;
        return floor($result3 / 3600). gmdate(":i", $result3);
       }
// 時間計算用関数ここまで
?>