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

    if($_SESSION['CheckType'] == 0 && !isset($_SESSION['WearType_before']))
    {
        if(!isset($_SESSION['WearType'])){
            $wearType = 'Top';
        }else{
            if (strpos($_SESSION['WearType'],'All') !== false){
                $wearType = 'Top';
            }
            else{
                $wearType = $_SESSION['WearType'];
            }
        }
        $_SESSION['WearType_before'] = $wearType;
    }else{
        $wearType = $_SESSION['WearType_before'];
    }
    
    $sql = "SELECT WearType,ImageFile FROM Clothes WHERE email = :email AND WearType = '".$wearType."'" ;
    $connection = new PDO($dsn, $username, $password, $options);
    $statement = $connection->prepare($sql);
    $email = $_SESSION['Email'];
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $clothesAll = $statement->fetchAll();
    
    $image_path = $email;
  
    if (isset($_POST['upload'])) {
        if (!empty($_FILES['image']['name'])) {
            try  {
                $imageid = uniqid(mt_rand(), true);
                $email = $_SESSION['Email'];
                //$filepath = '../storage/app/images/'.$email.'/'.$wearType.'/'.$imageid.'.png';
                $filepath = 'Images/'.$email.'/'.$wearType.'/'.$imageid.'.png';
                $sql = "INSERT INTO Clothes(ImageFile,email,WearType) VALUES (:ImageFile,:email,:WearType)";
                $stmt = $connection->prepare($sql);
                $stmt->bindValue(':WearType', $wearType, PDO::PARAM_STR);
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

    if(isset($_POST['TopDisp'])){$wearType='Top'; $_SESSION['WearType']='Top'; unset ($_SESSION['WearType_before']); $_SESSION['CheckType'] = 1; }
    if(isset($_POST['BottomDisp'])){$wearType='Bottom'; $_SESSION['WearType']='Bottom'; unset ($_SESSION['WearType_before']);$_SESSION['CheckType'] = 1; }

    $_SESSION['CheckType'] =1;
    $pagetitle = 'イメージ追加：'.$wearType;
include "templates/header.php";
?>
<h1>画像アップロード：<?php echo $wearType;?></h1>
<form method="post" action="#">
<p>・タイプ切り替え<br>
    <input type="submit" name="TopDisp"  value="トップス">
    <input type="submit" name="BottomDisp"  value="ボトム">
</form><br>
</p>
<?php if (isset($_POST['upload'])  ): ?>
    <p><?php echo $message; ?></p>
    <p><a href="image_view.php?WearType=<?php echo $wearType?>">画像表示へ</a></p>
<?php else: ?>
    <form method="post" enctype="multipart/form-data">
        <p>アップロード画像</p>
        <input type="file" name="image" onchange="javascript:document.getElementById('upload').disabled = false">
        <button><input type="submit" name="upload" id="upload" value="送信" disabled></button>
    </form>
    <?php if (!empty($clothesAll)): ?>
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
                <td><img src="Images/<?php echo $_SESSION['Email'];?>/<?php echo $row["WearType"];?>/<?php echo $row["ImageFile"]; ?>.png" width="300" height="300"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif;?>
<?php endif;?>
<br><hr><br>
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
