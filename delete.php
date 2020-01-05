<?php
session_start();
require('dbconnect.php');

/* ログインしている場合、削除対象メッセージの投稿者IDと
  ログインしているアカウントのIDを比較して、合致すれば削除*/
if(isset($_SESSION['id'])) {
  $id = $_REQUEST['id'];

  $messages = $db->prepare('SELECT * FROM posts WHERE id = ?');
  $messages->execute(array($id));
  $message = $messages->fetch();

  if($message['user_id'] === $_SESSION['id']) {
    $del = $db->prepare('DELETE FROM posts WHERE id = ?');
    $del->execute(array($id));
  }
}

header('Location: index.php');
exit();
