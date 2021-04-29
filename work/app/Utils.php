<?php

namespace MyApp;

class Utils
{
  public static function h($str) {
    // HTMLに値を埋め込むには htmlspecialchars() が必要
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
}