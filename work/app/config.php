<?php

session_start();

/* PDOを使用してアクセス */
// DSN(データソースネーム): mysql => host名,db名,文字コード
define('DSN', 'mysql:host=db;dbname=myapp;charset=utf8mb4');
define('DB_USER', 'myappuser');
define('DB_PASS', 'myapppass');
// define('SITE_URL', 'http://localhost:8562');
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);

spl_autoload_register(function ($class) {
  $prefix = 'MyApp\\'; // namespace

  if(strpos($class, $prefix) == 0) {
    // namespace分を切り出し
    // $fileName = sprintf(__DIR__ . '/%s.php', substr($class, 6));
    $fileName = sprintf(__DIR__ . '/%s.php', substr($class, strlen($prefix)));

    if (file_exists($fileName)) {
      require($fileName);
    } else {
      echo 'File not found: ' . $fileName;
      exit;
    }
  }
});