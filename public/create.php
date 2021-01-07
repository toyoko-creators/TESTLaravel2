<?php

/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */
    session_start();

require "../config.php";
require "../common.php";

if (isset($_POST['adduser'])) {
  try  {
    $connection = new PDO($dsn, $username, $password, $options);
    
    $new_user = array(
      "email"     => $_POST['email'],
      "lastname"  => $_POST['lastname'],
      "firstname" => $_POST['firstname'],
      "VerifyPassword"  => password_hash($_POST['Password'], PASSWORD_DEFAULT)
    );

    $sql = sprintf(
      "INSERT INTO %s (%s) values (%s)",
      "users",
      implode(", ", array_keys($new_user)),
      ":" . implode(", :", array_keys($new_user))
    );
    
    $statement = $connection->prepare($sql);
    $statement->execute($new_user);
    mkdir("../Strage/app/images/".$_POST['email']."Top", 0777, true);
    mkdir("../Strage/app/images/".$_POST['email']."Bottom", 0777, true);
}
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}
?>
<?php
$pagetitle = 'ユーザー登録';
include "templates/header.php";
?>
<body>
<div class="form-wrapper">
  <?php if (isset($_POST['adduser']) && $statement) : ?>
    <blockquote><?php echo escape($_POST['email']); ?> successfully added.</blockquote>
  <?php endif; ?>
  <h1>ユーザー登録</h1>
  <p style="color: red"></p>   
	<form method="post">
      <div class="form-item">
        <label for="lastname"></label>
        <input type="text" name="lastname" id="lastname" required="required" placeholder="姓">
      </div>
      <div class="form-item">
        <label for="firstname"></label>
        <input type="text" name="firstname" id="firstname" required="required" placeholder="名">
      </div>
      <div class="form-item">
        <label for="email"></label>
        <input type="email" name="email" id="email" required="required" placeholder="Email アドレス">
      </div>
      <div class="form-item">
        <label for="password"></label>
        <input type="password" name="Password" id="Password" required="required" placeholder="パスワード">
      </div>
      <div class="form-item">
        <label for="password2"></label>
        <input type="password" name="password2" required="required" placeholder="パスワード確認用">
      </div>
      <div class="button-panel">
        <input type="submit" name="adduser" class="button" value="登録">
      </div>
    </form>
  <div class="form-footer">
    <p><a href="index.php">Back to home</a></p>
  </div>


<?php require "templates/footer.php"; ?>
