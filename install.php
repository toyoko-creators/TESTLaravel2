<?php

/**
 * Open a connection via PDO to create a
 * new database and table with structure.
 *
 */

require "config.php";

//ファイルの初期化
foreach (array_filter(glob('public/images/*'),'is_file') as $fileName) {
    echo 'delete: public/images/'.$fileName."\n";
    unlink($fileName);
}

//ファイルのコピー
foreach (glob('public/Images_Default/*') as $fileName) {
    echo 'copy from: public/Images_Default/'.$fileName."\n";
    copy($fileName, 'public/images/'.basename($fileName));
}

//DB初期化
try {
    $connection = new PDO("mysql:host=$host", $username, $password, $options);
    $sql = file_get_contents("database/init.sql");
    $connection->exec($sql);
    
    echo "Database and table users created successfully.";
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

?>