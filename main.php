<?PHP
session_start();

try{
    $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
    } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
    }
    



include('include/head.php');
?>
    <title>お知らせ</title>
</head>
<body>
    <?php include('include/header.php'); ?>
    


</body>
</html>