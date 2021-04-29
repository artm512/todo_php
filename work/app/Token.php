<?php

namespace MyApp;

class Token
{
  // トークンを作る関数を定義
  public static function create()
  {
    if (!isset($_SESSION['token'])) {
      // 推測されにくい文字列をトークンとして設定
      $_SESSION['token'] = bin2hex(random_bytes(32));
    }
  }

  // トークンが一致しているかをチェックする関数
  public static function validate()
  {
    if (
      empty($_SESSION['token']) ||
      $_SESSION['token'] !== filter_input(INPUT_POST, 'token')
    ) {
      exit('Invalid post request');
    }
  }
}