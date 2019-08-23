<?PHP
session_start();
include('include/function.php');//時間計算用自作関数読み込み
$errormessage ="";
$date = "'".date('Y').'-'.date('m').'%'."'";//曖昧検索用
$id = $_SESSION['id'];

// シフト整理
if($_SESSION["shift"] == 'A'){
    $shift = '8:30 ~ 17:30';
    $start_shift = '08:30:00';
    $end_shift = '17:30:00';
}elseif($_SESSION["shift"] == 'B'){
    $shift = '9:00 ~ 18:00';
    $start_shift = '09:00:00';
    $end_shift = '18:00:00';
}elseif($_SESSION["shift"] == 'C'){
    $shift = '10:00 ~ 19:00';
    $start_shift = '10:00:00';
    $end_shift = '19:00:00';
}
// シフト整理ここまで

// 選択フォーム関連
$d = date('Y-m');
list($year, $month) = explode('-', $d);
$thisYear=$year;
if(isset($_POST["year"])) $year=$_POST["year"];
if(isset($_POST["month"])) $month=$_POST["month"];
$optionYear="";
for ($i=($thisYear-2); $i<=($thisYear); $i++) {
    $selected=($i==$year)?" selected":"";
    $optionYear .= "<option value=\"{$i}\"{$selected}>{$i}</option>\n"; 
}
$optionMonth="";
for($i=1;$i<=12; $i++) {
    $selected=($i==$month)?" selected":"";
    $optionMonth .= "<option value=\"{$i}\"{$selected}>{$i}</option>\n"; 
}
//選択フォーム関連ここまで

//DB接続
try{
    $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
    } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
    }
//祝日データ取得
$stmt_holiday = $pdo->prepare("SELECT * FROM holiday_table");
$status = $stmt_holiday->execute();
$result_holidayarray = $stmt_holiday->fetchall(PDO::FETCH_ASSOC);
$holidayarray = array_column($result_holidayarray, 'date');
//祝日ここまで

//有給データ取得
$stmt_yasumi = $pdo->prepare("SELECT * FROM yasumi_table WHERE id = $id");
$status = $stmt_yasumi->execute();
$result_yasumiarray = $stmt_yasumi->fetchall(PDO::FETCH_ASSOC);
$yasumiarray = array_column($result_yasumiarray, 'date');
//有給ここまで

$weekday = array('日','月','火','水','木','金','土');//曜日配列セット

// 集計ボタン押されたらスタート
if(isset($_POST["select"])){
    $date = "'".$_POST["year"] .'-'.'0'.$_POST["month"].'%'."'";}
$stmt = $pdo->prepare("SELECT * FROM kintai_table WHERE id = $id AND date LIKE $date");
$status = $stmt->execute();
$zangyo_sum = '00:00:00';//時間外合計リセット
$kyujitsu_sum = '00:00:00';//休日勤務合計リセット
$kyujitsu_kaisuu = 0;//休日勤務回数
if($status==false){
    $errormessage = $stmt->errorInfo();
    exit("ErrorQuery:".$errormessage[2]);
}else{
    //テーブル作成
    $s = '<table border="1"><tr><th class="midashi">日付</th><th class="midashi" colspan = "2">曜日</th><th class="midashi">出勤時刻</th><th class="midashi">退社時刻</th><th class="midashi">時間外勤務</th><th class="midashi">休日勤務</th><tr></tr>';
    $resultarray = $stmt->fetchall(PDO::FETCH_ASSOC);
    $datearray = array_column($resultarray, 'date');
    for($i=mktime(0,0,0,$month,1,$year);$i<mktime(0,0,0,$month+1,1,$year);$i+=60*60*24) {
        $kensaku = array_search(date('Y-m-d',$i), $datearray);
        $kensaku_holiday = array_search(date('Y-m-d',$i), $holidayarray);
        $kensaku_yasumi = array_search(date('Y-m-d',$i), $yasumiarray);
        //color設定
        if($kensaku_holiday !== false){//_祝日がオレンジ
            $FC = '<font color = "orange">';
            $icon = '祝日';
        }elseif(date('w',$i) == 0){//_祝日が赤
            $FC = '<font color = "red">';
            $icon = '休日';
        }elseif(date('w',$i) == 6){//_土曜日が青
            $FC = '<font color = "blue">';
            $icon = '休日';
        }elseif($kensaku_yasumi !== false){//_有給が緑
            $FC = '<font color = "green">';
            $icon = $result_yasumiarray[$kensaku_yasumi]["bunrui"];
        }else{
            $FC = '';
            $icon = '〇';
        }
        //勤怠入力されてるかチェック
        if($kensaku === false){//入力されてない場合の処理
            $s .='<td>'.$FC.date('Y-m-d',$i)."</td><td>".$FC.$weekday[date('w',$i)]."</td><td>".$FC.$icon."</td><td></td><td></td><td></td><td></td>";
        }elseif((date('w',$i) == 0) || (date('w',$i) == 6) || ($kensaku_holiday != false)){//入力されており、休日の場合の処理
            $kyujitsu = minusTime($resultarray[$kensaku]["starttime"],$resultarray[$kensaku]["endtime"]); 
            $kyujitsu_sum = plusTime($kyujitsu_sum,$kyujitsu);
            $kyujitsu_kaisuu = $kyujitsu_kaisuu + 1 ;
            $s .="<td>".$FC.date('Y-m-d',$i)."</td><td>".$FC.$weekday[date('w',$i)]."</td><td>".$FC.$icon."</td><td>".date('G:i',strtotime($resultarray[$kensaku]["starttime"]))."</td><td>".date('G:i',strtotime($resultarray[$kensaku]["endtime"]))."</td><td></td><td>".date('G:i',strtotime($kyujitsu))."</td>";
        }else{//入力されており平日の場合の処理
            //残業時間計算
            $zangyo_st = minusTime($resultarray[$kensaku]["starttime"],$start_shift);
            $zangyo_end = minusTime($end_shift,$resultarray[$kensaku]["endtime"]);
            $zangyo = plusTime($zangyo_st , $zangyo_end);
            $zangyo_sum = plusTime($zangyo_sum , $zangyo);
            //残業時間計算ここまで
            $s .="<td>".$FC.date('Y-m-d',$i)."</td><td>".$FC.$weekday[date('w',$i)]."</td><td>".$FC.$icon."</td><td>".date('G:i',strtotime($resultarray[$kensaku]["starttime"]))."</td><td>".date('G:i',strtotime($resultarray[$kensaku]["endtime"]))."</td><td>".$zangyo."</td><td></td>";
        }$s .="</tr>";
    }
    $s .="<tr><td colspan='5'>時間外勤務合計</td><td>".$zangyo_sum."</td><td></td></tr>";
    $s .="<tr><td colspan='5'>休日勤務合計</td><td>".$kyujitsu_kaisuu."回</td><td>".$kyujitsu_sum."</td></tr>";
    $s .= "</table>";
    //テーブルここまで
}
include('include/head.php');
?>
    <title>月別集計表</title>
</head>
<body>
    <?php include('include/header.php'); 
    echo $errormessage;
    ?>
    <div class="mainbox">
        <div class="leftbox">
            <ul>
                <li><a href="input.php">入力</a></li>
                <li><a href="shukei.php">集計</a></li>
            </ul>
        </div>
        <div class="rightbox">
            <div class="hyouBox">
                <div class="hyoutop">
                    <div class="left">
                        <p><?PHP echo '所属：'.$_SESSION['busho'];?></p>
                        <p><?PHP echo '氏名：'.$_SESSION['name'];?></p>
                    </div>
                    <div class="center">
                        <h2>月別集計表</h2>
                        <div class="form">
                            <form action="" id="monthselect" name="monthselect" method="POST"> 
                                <select name="year"id ="year"><?php echo $optionYear; ?></select>
                                <label for="year">年</label>  
                                <select name="month" id="month"><?php echo $optionMonth; ?></select>
                                <label for="month">月</label>
                                <input type="submit" value="集計" name="select" id ="select">
                                <input type="button" class="insatu"name="print" value="印刷" >
                            </form>
                        </div>
                    </div>
                    <div class="right">
                        <div class="shounin">上長印</div>
                        <div class="shouninbox"></div>
                    </div>
                </div>
                <p class="shift"><?PHP echo '勤務シフト='.$shift;?></p>
                <div class="shukeihyou">
                    <?php echo $s; ?>
                </div>
            </div>
        </div>
    </div>
    <footer></footer>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="js/test.js"></script>
</body>
</html>