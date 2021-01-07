<?php
    require "../config.php";
    //require "../common.php";

    session_start();
    //ログイン済みかを確認
    if (isset($_SESSION['USER'])) {
        header('Location: top.php');
        exit;
    }
    $_SESSION['CheckType'] =0;
    //ログイン機能
    $LoginUserName = '';
    $message = '';
    if(isset($_POST['login'])){
        try  {
                $connection = new PDO($dsn, $username, $password, $options);
                $stmt = $connection->prepare('SELECT * FROM users WHERE email = :email');
                $stmt->execute(array(':email' => $_POST['email']));
                $result = $stmt->fetch();
                if (password_verify($_POST['password'],$result['VerifyPassword'])){
                    $LoginUserName= $result['lastname']." ".$result['firstname'];
                    $_SESSION['USER'] = $LoginUserName;
                    $_SESSION['Email'] = $_POST['email'];
                    //$_SESSION['USER'] = '東横太郎';
                    header('Location: top.php');
                    exit;
                }else{
                    //PW間違い
                    $message = 'Aメールアドレスかパスワードが間違っています。';
                }
        } catch(PDOException $error) {
            //DB接続エラーorユーザが見つからない
            $message = 'Bメールアドレスかパスワードが間違っています。';
        }
    } 
?>
<?php
$pagetitle = 'ログイン';
include "templates/header.php";
?>

<div class="form-wrapper">
  <h1>ログイン</h1>
<p style="color: red"><?php echo $message ?></p>
  <form method="post" action="index.php">
    <div class="form-item">
      <label for="email"></label>
      <input type="email" name="email" required="required" placeholder="Email アドレス">
    </div>
    <div class="form-item">
      <label for="password"></label>
      <input type="password" name="password" required="required" placeholder="パスワード">
    </div>
    <div class="button-panel">
      <input type="submit" name="login" class="button" value="ログイン">
    </div>
  </form>
  <div class="form-footer">
    <p><a href="create.php">ユーザーアカウント作成</a></p>
    <!--<p><a href="#">パスワードを忘れた場合</a></p>-->
  </div>
</div>
<p>以下テンプレートのやつ
<ul>
	<li><a href="read.php"><strong>Read</strong></a> - find a user</li>
	<li><a href="update.php"><strong>Update</strong></a> - edit a user</li>
	<li><a href="delete.php"><strong>Delete</strong></a> - delete a user</li>
</ul>
</p>
</body>
</html>
<!--<?php include "templates/footer.php"; ?>-->
