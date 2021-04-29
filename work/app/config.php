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
  $fileName = sprintf(__DIR__ . '/%s.php', $class);

  if (file_exists($fileName)) {
    require($fileName);
  } else {
    echo 'File not found: ' . $fileName;
    exit;
  }
});