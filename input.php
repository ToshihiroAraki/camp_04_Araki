<?PHP
session_start();
// シフト整理_id_tableに登録してある勤務シフトを文字情報にしておく
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
if(empty($_SESSION['kanryomessage'])){//入力前はsession変数のままだと完了メッセージがemptyでエラーになるから””としておく
    $kanryomessage = "";
}else{
    $kanryomessage = $_SESSION['kanryomessage'];//登録内容をkintai_input.phpから受け取る
    $_SESSION['kanryomessage'] = "";//別ページに行って戻ってきたときに表示されないようにsession変数は空にしとく
}
if(empty($_SESSION['tourokumessage'])){
    $tourokumessage = "";
}else{
    $tourokumessage = $_SESSION['tourokumessage'];//登録内容をyasumi_input.phpから受け取る
    $_SESSION['tourokumessage'] = "";//別ページに行って戻ってきたときに表示されないようにsession変数は空にしとく
}
include('include/head.php');
?>
    <title>勤怠入力</title>
</head>
<body>
    <?php include('include/header.php'); ?>
    <div class="box">
        <div class="kintai">
            <h2>勤怠入力画面</h2>
            <div class="form">
                <form action="kintai_insert.php" method="POST">
                    <table>
                        <tr>
                            <th><label for="date">日付　　：</label></th>
                            <td><input type="date" id="date" name="date" required></td>
                        </tr>
                        <tr>
                            <th><label for="starttime">出社時刻：</label></th>
                            <td><input type="time" id="starttime" name="starttime" required></td>
                        </tr>
                        <tr>
                            <th><label for="endtime">退社時刻：</label></th>
                            <td><input type="time" id="endtime" name="endtime" required></td>
                        </tr>
                    </table>
                    <div class="button">
                        <input type="submit" name="touroku" id ="touroku"value="勤怠登録">
                    </div>
                </form>
            </div>
            <?PHP echo '<div class="message">'.$kanryomessage.'</div>' ; ?>
        </div>
        <div class="yasumi">
            <h2>休日登録画面</h2>
            <div class="form">
                <form action="yasumi_insert.php" method="POST">
                    <table>
                        <tr>
                            <th><label for="date2">日付　　：</label></th>
                            <td><input type="date" id="date2" name="date" required></td>
                        </tr>
                        <tr>
                            <th><label for="bunrui">休日分類：</label></th>
                            <td><select id="bunrui" name="bunrui" required>
                            <option value="">選択してください</option>
                            <option value="有給">有給休暇</option>
                            <option value="忌引">忌引休暇</option>
                            <option value="育児">育児休暇</option>
                            <option value="結婚">結婚休暇</option>
                            <option value="産休">出産休暇</option>
                            </select></td>
                        </tr>
                        <tr>
                            <th><label for="bikou">備考　　：</label></th>
                            <td><input type="text" id="bikou" name="bikou"></td>
                        </tr>
                    </table>
                    <div class="button">
                        <input type="submit" name="yasumi_touroku" id ="yasumi_touroku"value="休日登録">
                    </div>
                </form>
            </div>
            <?PHP echo '<div class="message">'.$tourokumessage.'</div>' ; ?>
        </div>
    </div>
    <footer></footer>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="js/test.js"></script>
</body>
</html>