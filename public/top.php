<?php
    require "../config.php";
    session_start();
    //ログイン済みかを確認
    if (!isset($_SESSION['USER'])) {
        header('Location: index.php');
        exit;
    }

    try  {
        $connection = new PDO($dsn, $username, $password, $options);
        $sqltops = "SELECT ImageFile FROM Clothes WHERE email = :email AND WearType = 'Top'" ;
        $stmt = $connection->prepare($sqltops);
        $email = $_SESSION['Email'];
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $tops = $stmt->fetchAll();
        $sqlbottoms = "SELECT ImageFile FROM Clothes WHERE email = :email AND WearType = 'Bottom'" ;
        $stmt = $connection->prepare($sqlbottoms);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $bottoms = $stmt->fetchAll();
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    function WearButtonClick($arg_1){
        $_SESSION['WearType'] =$arg_1;
        header("Location: image_upload.php");
        exit;
    }
    //初期化(デフォルトではすべて表示する)
    $_SESSION['WearType'] ='All';
    $message="";

    
    //コーディネート保存
    if(isset($_POST['outfit'])){
        try  {
            $connectionFav = new PDO($dsn, $username, $password, $options);
            $email = $_SESSION['Email'];
            $TopFile = "12750326105f88e2b1d27894.99618425";
            $BottomFile = "14294579835f8902f30e1a91.36829885";
            $sqlFav = "SELECT TopFile FROM FavoList WHERE TopFile = :TopFile AND BottomFile = :BottomFile" ;

            $stmtFav = $connection->prepare($sqlFav);
            $stmtFav->bindValue(':TopFile', $TopFile, PDO::PARAM_STR);
            $stmtFav->bindValue(':BottomFile', $BottomFile, PDO::PARAM_STR);
            $stmtFav->execute();
            $CheckUniq = $stmtFav->fetchAll();

            if (empty($CheckUniq))
            {
                $sqlFav = "INSERT INTO FavoList(email,TopFile,BottomFile) VALUES (:email,:TopFile,:BottomFile)";
                $stmtFav = $connectionFav->prepare($sqlFav);
                $stmtFav->bindValue(':email', $email, PDO::PARAM_STR);
                $stmtFav->bindValue(':TopFile', $TopFile, PDO::PARAM_STR);
                $stmtFav->bindValue(':BottomFile', $BottomFile, PDO::PARAM_STR);
                $stmtFav->execute();
                $message = '登録しました';
            }
            else{
                //すでにその組み合わせがある場合は作らない
                $message = 'その組み合わせはすでに登録済みです';                
            }
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
        //exit;
    }

    //クローゼット
    if(isset($_POST['closet'])){
        header('Location: closet.php');
        exit;
    }
    //ログアウト機能
    if(isset($_POST['logout'])){
        $_SESSION = [];
        session_destroy();
        header('Location: index.php');
        exit;
    }

    //Topsボタンクリック
    if(isset($_POST['TopsButton'])){
        WearButtonClick("Top");
        exit;
    }
    //Bottomsボタンクリック
    if(isset($_POST['BottomsButton'])){
        WearButtonClick("Bottom");
        exit;
    }
?>


<?php
$pagetitle = 'トップ画面';
include "templates/header.php";
?>
        <div id="container">
            <div id="Menu_frame">
                <form method="post">
                    <div class="button-normal">
                        <input type="submit" name="closet" value="クローゼット">
                    </div>
                    <div class="button-normal">
                        <input type="submit"  name="outfit" value="コーディネート保存">
                    </div>
                    <div class="button-normal">
                        <input type="submit" name="logout" value="ログアウト">
                    </div>
                	<div class="button-normal">
	                    <input type="submit" class="button" name="TopsButton" value="トップス選択">
	                    <input type="submit" class="button" name="BottomsButton"  value="ボトムス選択">
                    </div>
                </form>
            </div>
            <div id="Main_frame_tops">
                <div class="slick01">
                    <?php foreach ((array)$tops as $row) : ?>
                        <img alt=" <?php echo $row["ImageFile"]; ?>" src="images/<?php echo $row["ImageFile"]; ?>.png">
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="Main_frame_bottoms">
                <div class="slick02">
                    <?php foreach ((array)$bottoms as $row) : ?>
                        <img alt=" <?php echo $row["ImageFile"]; ?>" src="images/<?php echo $row["ImageFile"]; ?>.png">
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <p>
        <a href="image_view.php">一覧表示</a>
    </p>

    <script type="text/javascript" src="js/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    
<?php include "templates/footer.php"; ?>
