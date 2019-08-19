<header class="header1">
        <h1>社内システム</h1>
        <div class="user">
                <p><?PHP echo $_SESSION['busho'];?></p>
                <p><?PHP echo $_SESSION['name'].'さん';?></p>
        </div>
    </header>
      <header class="header2">
        <ul class="menu">
          <li>NEWS
            <ul class="submenu">
              <li><a href="#">お知らせ</a></li>
              <li><a href="#">辞令発令</a></li>
              <li><a href="#">社内規定改訂</a></li>
            </ul>
          </li>
          <li>勤怠管理
            <ul class="submenu">
              <li><a href="input.php">入力</a></li>
              <li><a href="shukei.php">集計</a></li>
            </ul>
          </li>
          <li>会議室予約</li>
          <li>ユーザー
            <ul class="submenu">
              <li><a href="henkou.php">情報変更</a></li>
              <li><a href="logout.php">ログアウト</a></li>
            </ul>
          </li>
        </ul>
        
      </header>
    

    
