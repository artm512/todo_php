<?php

namespace MyApp;

class Todo
{
  private $pdo;
  
  public function __construct($pdo)
  {
    $this->pdo = $pdo;
    Token::create();
  }

  public function processPost()
  {
    // $_SERVERを調べて、POSTだったら追加する
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      Token::validate();
      $action = filter_input(INPUT_GET, 'action');

      switch ($action) {
        case 'add':
          $id = $this->add();
          header('Content-Type: application/json'); // json形式で返す宣言
          echo json_encode(['id' => $id]);
          break;
        case 'toggle':
          $this->toggle();
          break;
        case 'delete':
          $this->delete();
          break;
        case 'purge':
          $this->purge();
          break;
        default:
          exit;
      }

      exit;
    }
  }

  // レコードの追加
  private function add() {
    $title = trim(filter_input(INPUT_POST, 'title')); // 前後に半角空白が入って来たら除去
    if ($title === '') { 
      return;
    }
    $stmt = $this->pdo->prepare("INSERT INTO todos (title) VALUES (:title)");
    $stmt->bindValue('title', $title, \PDO::PARAM_STR); // 値を紐付ける
    $stmt->execute();

    // 非同期通信のため、追加した項目のidを返す
    return (int) $this->pdo->lastInsertId();
  }

  private function delete()
  {
    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)) {
      return;
    }

    $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = :id");
    $stmt->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt->execute();
  }

  private function toggle()
  {
    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)) {
      return;
    }

    // レコードがすでに更新されていた場合はエラーを出す
    $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE id = :id");
    $stmt->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    $todo = $stmt->fetch();
    if (empty($todo)) {
      header('HTTP', tre, 404); // 404エラーコードを返す。HTTP status code
      exit;
    }

    $stmt = $this->pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = :id");
    $stmt->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt->execute();
  }

  private function purge(){
    $this->pdo->query("DELETE FROM todos WHERE is_done = 1");
  }

  public function getAll()
  {
    $stmt = $this->pdo->query("SELECT * FROM todos ORDER BY id DESC"); // 新しい順に並べた上で取得するSQL
    $todos = $stmt->fetchAll(); // SQL文の結果を返す
    return $todos;
  }
}