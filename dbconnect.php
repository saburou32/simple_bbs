<?php
try {
  $db = new PDO('mysql:dbname=simple_bbs;host=localhost;charset=utf8',
  'root', 'root');
} catch (PDOException $e) {
  echo 'DB接続エラー：' . $e->getMessage();
}
