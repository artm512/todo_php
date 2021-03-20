<?
/* PDOを使用してアクセス */
// DSN(データソースネーム): mysql => host名,db名,文字コード
define('DSN', 'mysql:host=db;dbname=myapp;charset=utf8mb4');
define('DB_USER', 'myappuser');
define('DB_PASS', 'myapppass');
// PDOのインスタンスを作成
try {
  $pdo = new PDO(
    DSN,
    DB_USER,
    DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]
  );
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>My Todos</title>
</head>
<body>
  <h1>Todos</h1>
</body>
</html>