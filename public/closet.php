<?php
    $results = "";
    $ListNo=1;
    require "../config.php";
    session_start();
    //ログイン済みかを確認
    if (!isset($_SESSION['USER'])) {
        header('Location: index.php');
        exit;
    }


    try  {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT TopFile,BottomFile FROM FavoList WHERE email = :email" ;
        $stmt = $connection->prepare($sql);
        $email = $_SESSION['Email'];
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll();
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }   
    //クローゼット
    if(isset($_POST['TopPage'])){
        header('Location: top.php');
        exit;
    }
    //ログアウト機能
    if(isset($_POST['logout'])){
        $_SESSION = [];
        session_destroy();
        header('Location: index.php');
        exit;
    }

    //削除ボタンクリック
    if(isset($_POST['DeleteCollection'])){
        //WearButtonClick("Bottom");
        exit;
    }
    
?>
<?php
$pagetitle = 'クローゼット';
include "templates/header.php";
?>
        <div id="container">
            <div id="Menu_frame">
            <h2>クローゼット</h2>
                <form method="post">
                    <div class="button-normal">
                        <input type="submit" name="TopPage" value="Topに戻る">
                    </div>
                    <div class="button-normal">
                        <input type="submit"  name="DeleteCollection" value="お気に入り削除">
                    </div>
                    <div class="button-normal">
                        <input type="submit" name="logout" value="ログアウト">
                    </div>
                </form>
            </div>
            <div id="Main_frame">
        
            <?php if (empty($results)): ?>
                <p>1つもお気に入り登録されていません</p>
            <?php else: ?>
                    <div class="slickSet">
                        <?php foreach ((array)$results as $row)  : ?>
                        <span>
                        <img  alt=" <?php echo $row["TopFile"]; ?>" src="images/<?php echo $row["TopFile"]; ?>.png"  width="300" height="300">
                        <img  alt=" <?php echo $row["BottomFile"]; ?>" src="images/<?php echo $row["BottomFile"]; ?>.png"  width="300" height="300">
                        </span>
                        <?php endforeach; ?>
                    </div>
            <?php endif;?>
            </div>
        </div>
    <script type="text/javascript" src="js/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    
<?php include "templates/footer.php"; ?>