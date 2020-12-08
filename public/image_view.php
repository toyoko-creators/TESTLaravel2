<?php
    session_start();
    $results = "";

    function DisplayImage(){
        require "../config.php";
        global $WearType;
        global $results;
        try  {
            $connection = new PDO($dsn, $username, $password, $options);
            //$sql = "SELECT * FROM Clothes WHERE WearType = 'Top'";
            $sql = "SELECT WearType,ImageFile FROM Clothes WHERE email = :email" ;
            if (strcmp($WearType,'All') != 0){
                //AllじゃないときはTop.Bottomで絞り込み
                $sql = $sql." AND WearType = '".$WearType."'" ;
            }
            $stmt = $connection->prepare($sql);
            $email = $_SESSION['Email'];
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll();
            $message = '画像を読み込みました';
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    function WearButtonClick(){
        global $WearType;
        $_SESSION['WearType'] =$WearType;
        DisplayImage($WearType);
    }

    //ログイン済みかを確認
    if (!isset($_SESSION['USER'])) {
        header('Location: index.php');
        exit;
    }
    if(isset($_POST['AllDisp'])){$_SESSION['WearType']='All'; }
    if(isset($_POST['TopDisp'])){$_SESSION['WearType']='Top'; }
    if(isset($_POST['BottomDisp'])){$_SESSION['WearType']='Bottom'; }

    $WearType = $_SESSION['WearType'];
    DisplayImage();
    
?>
<?php
$pagetitle = '画像一覧：'.$WearType;
include "templates/header.php";
?>
<h1>画像表示：<?php echo $_SESSION['WearType']?></h1>

<?php echo $message; ?>
<p>
    <form method="post" action="image_view.php">
        <input type="submit" name="AllDisp"  value="すべて表示">
        <input type="submit" name="TopDisp"  value="トップスのみ表示">
        <input type="submit" name="BottomDisp"  value="ボトムのみ表示">
    </form>
</p>
<?php if (isset($results)): ?>
    <table>
      <thead>
        <tr>
          <th>WearType</th>
          <th>imageid</th>
          <th>image</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ((array)$results as $row) : ?>
        <tr>
          <td><?php echo $row["WearType"]; ?></td>
          <td><?php echo $row["ImageFile"]; ?></td>
          <td><img src="images/<?php echo $row["ImageFile"]; ?>.png" width="300" height="300"></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
<?php endif;?>
<p><a href="image_upload.php">画像アップロードへ</a></p>
<a href="index.php">Back to home</a>
<?php include "templates/footer.php"; ?>


