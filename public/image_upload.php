<?php
    session_start();

    require "../config.php";

    /*
    // https://www.php.net/manual/ja/function.imagecopyresized.php
    function img_resize( $tmpname, $size, $save_dir, $save_name )
    {
    $save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
        $gis       = GetImageSize($tmpname);
    $type       = $gis[2];
    switch($type)
        {
        case "1": $imorig = imagecreatefromgif($tmpname); break;
        case "2": $imorig = imagecreatefromjpeg($tmpname);break;
        case "3": $imorig = imagecreatefrompng($tmpname); break;
        default:  $imorig = imagecreatefromjpeg($tmpname);
        }

        $x = imageSX($imorig);
        $y = imageSY($imorig);
        if($gis[0] <= $size)
        {
        $av = $x;
        $ah = $y;
        }
            else
        {
            $yc = $y*1.3333333;
            $d = $x>$yc?$x:$yc;
            $c = $d>$size ? $size/$d : $size;
              $av = $x*$c;
              $ah = $y*$c;
        }   
        $im = imagecreate($av, $ah);
        $im = imagecreatetruecolor($av,$ah);
    if (imagecopyresampled($im,$imorig , 0,0,0,0,$av,$ah,$x,$y))
        if (imagejpeg($im, $save_dir.$save_name))
            return true;
            else
            return false;
    }
    */

    //ログイン済みかを確認
    if (!isset($_SESSION['USER'])) {
        header('Location: index.php');
        exit;
    }
     $WearType = $_SESSION['WearType'];

    $connection = new PDO($dsn, $username, $password, $options);
    //$sql = "SELECT * FROM Clothes WHERE WearType = 'Top'";
    $sql = "SELECT WearType,ImageFile FROM Clothes WHERE email = :email" ;
    //Allの時はTopを表示
    if (strcmp($WearType,'All') == 0){
        $_SESSION['WearType'] = "Top";
        $WearType = "Top";
        $sql = $sql." AND WearType = 'Top'" ;
    }else{
        $sql = $sql." AND WearType = '".$WearType."'" ;
    }
    $statement = $connection->prepare($sql);
    $email = $_SESSION['Email'];
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $clothesAll = $statement->fetchAll();

    if (isset($_POST['upload'])) {
        if (!empty($_FILES['image']['name'])) {
            try  {
                $imageid = uniqid(mt_rand(), true);
                $email = $_SESSION['Email'];
                $wearType = $_SESSION['WearType'];
                $filepath = "./images/$imageid.png";
                $sql = "INSERT INTO Clothes(ImageFile,email,WearType) VALUES (:ImageFile,:email,'$wearType')";
                $stmt = $connection->prepare($sql);
                $stmt->bindValue(':ImageFile', $imageid, PDO::PARAM_STR);
                $stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                //$tmpname  = $_FILES['image']['tmp_name'];
                //img_resize( $tmpname , 15 , "./images" , $imageid.".png");
                move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
                $message = '画像をアップロードしました';
            }
            catch (PDOException $e) {
                echo $e->getMessage();
            }  
        }
    }

    if(isset($_POST['TopDisp'])){$_SESSION['WearType']='Top'; }
    if(isset($_POST['BottomDisp'])){$_SESSION['WearType']='Bottom'; }
?>
<?php
$pagetitle = 'イメージ追加：'.$_SESSION['WearType'];
include "templates/header.php";
?>
<h1>画像アップロード：<?php echo $_SESSION['WearType']?></h1>
<form method="post" action="image_upload.php">
<p>・タイプ切り替え<br>
    <input type="submit" name="TopDisp"  value="トップス">
    <input type="submit" name="BottomDisp"  value="ボトム">
</form><br>
</p>
<?php if (isset($_POST['upload'])): ?>
    <p><?php echo $message; ?></p>
    <p><a href="image_view.php?WearType=<?php echo $_SESSION['WearType']?>">画像表示へ</a></p>
<?php else: ?>
    <form method="post" enctype="multipart/form-data">
        <p>アップロード画像</p>
        <input type="file" name="image">
        <button><input type="submit" name="upload" value="送信"></button>
        <?php if (isset($clothesAll)): ?>
            <br><hr><br>
            <p>登録済み画像一覧</p>
            <table>
                    <thead>
                        <tr>
                        <th>WearType</th>
                        <th>imageid</th>
                        <th>image</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ((array)$clothesAll as $row) : ?>
                        <tr>
                        <td><?php echo $row["WearType"]; ?></td>
                        <td><?php echo $row["ImageFile"]; ?></td>
                        <td><img src="images/<?php echo $row["ImageFile"]; ?>.png" width="300" height="300"></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
        <?php endif;?>
    </form>
<?php endif;?>
<br><hr><br>
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
