<?php
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
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}

function h($str) {
  // HTMLに値を埋め込むには htmlspecialchars() が必要
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function getTodos($pdo) {
  $stmt = $pdo->query("SELECT * FROM todos ORDER BY id DESC"); // 新しい順に並べた上で取得するSQL
  $todos = $stmt->fetchAll(); // SQL文の結果を返す
  return $todos;
}

$todos = getTodos($pdo);
// var_dump($todos); // $todosを表示してみる
// exit;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>My Todos</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <h1>Todos</h1>

  <ul>
    <?php foreach ($todos as $todo): ?>
    <li>
      <input type="checkbox" <?= $todo->is_done ? 'checked' : ''; ?>>
      <span class="<?= $todo->is_done ? 'done' : ''; ?>">
        <?= h($todo->title); ?>
      </span>
    </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>