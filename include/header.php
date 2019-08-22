<header class="header1">
        <h1>Portal system</h1>
        <div class="user">
                <p><?PHP echo $_SESSION['busho'];?></p>
                <p><?PHP echo $_SESSION['name'].'さん';?></p>
        </div>
    </header>

        <ul class="dropdown">
          <li><a href="main.php">TOP</a>
            <ul class="dropdown_menu">
              <li><a href="board_insert.php">入力</a></li>
              <li><a href="main.php">掲示板</a></li>
            </ul>
          </li>
          <li><a href="input.php">勤怠管理</a>
            <ul class="dropdown_menu">
              <li><a href="input.php">入力</a></li>
              <li><a href="shukei.php">集計</a></li>
            </ul>
          </li>
          <li>会議室予約</li>
          <li><a href="#">ユーザー</a>
            <ul class="dropdown_menu">
              <li><a href="henkou.php">情報変更</a></li>
              <li><a href="logout.php">ログアウト</a></li>
            </ul>
          </li>
        </ul>
        
    

    
