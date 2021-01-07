<?php

/**
 * Open a connection via PDO to create a
 * new database and table with structure.
 *
 */

require "config.php";

//ファイルの初期化
$BaseImagePath = './public';
//$BaseImagePath = 'storage/app/images'
if(file_exists ($BaseImagePath.'/Images')){
    rmdirAll($BaseImagePath.'/Images');
}

//ファイルのコピー
$TargetImagePath = './storage/imagesDefault';
//    echo 'copy from: public/Images_Default/'.$fileName."\n";
//    copy($fileName, 'public/images/'.basename($fileName));
//}
exec("cp -r ".$TargetImagePath." ".$BaseImagePath);
rename($BaseImagePath.'/imagesDefault',$BaseImagePath.'/Images');

//DB初期化
try {
    $connection = new PDO("mysql:host=$host", $username, $password, $options);
    $sql = file_get_contents("database/init.sql");
    $connection->exec($sql);
    
    echo "Database and table users created successfully.";
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

function rmdirAll($dir) {
	$res = glob($dir.'/*');
	foreach ($res as $file) {
		if (is_file($file)) {
            unlink($file);
            echo 'delete file: '.$file."\n";
		} else {
			rmdirAll($file);
		}
	}
	rmdir($dir);
    echo 'delete dir: '.$dir."\n";
}
?>