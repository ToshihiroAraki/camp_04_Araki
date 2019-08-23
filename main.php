<?PHP
session_start();

try{
    $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
    } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
    }
    //[お知らせ]取得
$stmt = $pdo->prepare("SELECT * FROM board_table WHERE bunrui ='お知らせ' order by indate desc LIMIT 15;");
$status = $stmt->execute();
if($status==false){
    $errormessage = $stmt->errorInfo();
    exit("ErrorQuery:".$errormessage[2]);
}else{
    $view_A ='<table><tr class="tr_under"><th class="th_title">TITLE</th><th class="th_naiyou">内容</th>
              <th class="th_date">日付</th><th class="th_name">投稿者</th><th class="th_file">file</th></tr>';
    while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view_A .= '<tr><td>';
    $view_A .= $result["title"];
    $view_A .="</td><td>";
    $view_A .= $result["naiyou"];
    $view_A .="</td><td>";
    $view_A .= $result["indate"];
    $view_A .="</td><td>";
    $view_A .= $result["name"];
    $view_A .="</td><td>";
if(!empty($result["file"])){
    $view_A .= '<a href="upload/'.$result["file"].'"target="_blank"><img src="img/pdf3.jpg" alt=""></a>';
}else{
    $view_A .="";
}
    $view_A .="</td></tr>";        
    }
    $view_A .="</table>";
}

//[辞令発令]取得
$stmt = $pdo->prepare("SELECT * FROM board_table WHERE bunrui ='辞令発令' order by indate desc;");
$status = $stmt->execute();
if($status==false){
    $errormessage = $stmt->errorInfo();
    exit("ErrorQuery:".$errormessage[2]);
}else{
    $view_B ='<table><tr class="tr_under"><th class="th_title">TITLE</th><th class="th_naiyou">内容</th>
              <th class="th_date">日付</th><th class="th_name">投稿者</th><th class="th_file">file</th></tr>';
    while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view_B .= '<tr><td>';
    $view_B .= $result["title"];
    $view_B .="</td><td>";
    $view_B .= $result["naiyou"];
    $view_B .="</td><td>";
    $view_B .= $result["indate"];
    $view_B .="</td><td>";
    $view_B .= $result["name"];
    $view_B .="</td><td>";
    if(!empty($result["file"])){
        $view_B .= '<a href="upload/'.$result["file"].'"target="_blank"><img src="img/pdf3.jpg" alt=""></a>';
    }else{
        $view_B .="";
    }
    $view_B .="</td></tr>";        
    }
    $view_B .="</table>";
}
//[社内規定]取得
$stmt = $pdo->prepare("SELECT * FROM board_table WHERE bunrui ='社内規定' order by indate desc;");
$status = $stmt->execute();
if($status==false){
    $errormessage = $stmt->errorInfo();
    exit("ErrorQuery:".$errormessage[2]);
}else{
    $view_C ='<table><tr class="tr_under"><th class="th_title">TITLE</th><th class="th_naiyou">内容</th>
              <th class="th_date">日付</th><th class="th_name">投稿者</th><th class="th_file">file</th></tr>';
    while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view_C .= '<tr><td>';
    $view_C .= $result["title"];
    $view_C .="</td><td>";
    $view_C .= $result["naiyou"];
    $view_C .="</td><td>";
    $view_C .= $result["indate"];
    $view_C .="</td><td>";
    $view_C .= $result["name"];
    $view_C .="</td><td>";
    if(!empty($result["file"])){
        $view_C .= '<a href="upload/'.$result["file"].'"target="_blank"><img src="img/pdf3.jpg" alt=""></a>';
    }else{
        $view_C .="";
    }
    $view_C .="</td></tr>";        
    }
    $view_C .="</table>";
}


include('include/head.php');
?>
    <title>TOP</title>
</head>
<body>
    <?php include('include/header.php'); ?>
    <div class="mainbox">
            <div class="leftbox">
                <ul>
                    <li><a href="main.php">掲示板</a></li>
                    <li><a href="board_insert.php">投稿</a></li>
                </ul>
            </div>
            <div class="rightbox">
                <h3>掲示板</h3>
                <ul class="tab_group">
                    <li class="tab is-active">お知らせ</li>
                    <li class="tab">辞令発令</li>
                    <li class="tab">社内規定</li>
                </ul>
                <div class="box">
                    <div class="panel is-show">
                        <div class="ichiran">
                            <?php echo $view_A; ?>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="ichiran">
                            <?php echo $view_B; ?>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="ichiran">
                            <?php echo $view_C; ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <footer></footer>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="js/test.js"></script>
</body>
</html>