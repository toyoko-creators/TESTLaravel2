<?php

/**
 * Function to query information based on 
 * a parameter: in this case, location.
 *
 */

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {

  try  {
    //$connection = new PDO("mysql:host=localhost; dbname=test_db5; charset=utf8", "$user", "$password");
    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * 
            FROM users";

    $statement = $connection->prepare($sql);
    $statement->execute();

    $result = $statement->fetchAll();
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}
?>
<?php
$pagetitle = 'ユーザ一覧確認';
include "templates/header.php";
?>
    <h2>Results</h2>

    <table>
      <thead>
        <tr>
          <th>Email Address</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Password-hash</th>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach ((array)$result as $row) :
       ?>
        <tr>
          <td><?php echo escape($row["email"]); ?></td>
          <td><?php echo escape($row["firstname"]); ?></td>
          <td><?php echo escape($row["lastname"]); ?></td>
          <td><?php echo escape($row["VerifyPassword"]); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
</table>


<form method="post">
  <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
  <input type="submit" name="submit" value="View Results">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>