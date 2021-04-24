<?php

session_start();

/* PDOを使用してアクセス */
// DSN(データソースネーム): mysql => host名,db名,文字コード
define('DSN', 'mysql:host=db;dbname=myapp;charset=utf8mb4');
define('DB_USER', 'myappuser');
define('DB_PASS', 'myapppass');
// define('SITE_URL', 'http://localhost:8562');
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);

createToken();

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

// トークンを作る関数を定義
function createToken()
{
  if (!isset($_SESSION['token'])) {
    // 推測されにくい文字列をトークンとして設定
    $_SESSION['token'] = bin2hex(random_bytes(32));
  }
}

// トークンが一致しているかをチェックする関数
function validateToken()
{
  if (
    empty($_SESSION['token']) ||
    $_SESSION['token'] !== filter_input(INPUT_POST, 'token')
  ) {
    exit('Invalid post request');
  }
}

// レコードの追加
function addTodo($pdo) {
  $title = trim(filter_input(INPUT_POST, 'title')); // 前後に半角空白が入って来たら除去
  if ($title === '') { 
    return;
  }
  $stmt = $pdo->prepare("INSERT INTO todos (title) VALUES (:title)");
  $stmt->bindValue('title', $title, PDO::PARAM_STR); // 値を紐付ける
  $stmt->execute();
}

function getTodos($pdo) {
  $stmt = $pdo->query("SELECT * FROM todos ORDER BY id DESC"); // 新しい順に並べた上で取得するSQL
  $todos = $stmt->fetchAll(); // SQL文の結果を返す
  return $todos;
}

// $_SERVERを調べて、POSTだったら追加する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  addTodo($pdo);

  // 再読み込み時は、postではない形式でindex.phpにアクセスさせる
  header('Location: ' . SITE_URL);
  exit;
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

  <form action="" method="post">
    <input type="text" name="title" placeholder="Type new todo.">
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
  </form>

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