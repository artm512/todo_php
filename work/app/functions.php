<?php

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

// pdoを返す
function getPdoInstance()
{
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

    return $pdo;
  } catch (PDOException $e) {
    echo $e->getMessage();
    exit;
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

function toggleTodo($pdo)
{
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = :id");
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}