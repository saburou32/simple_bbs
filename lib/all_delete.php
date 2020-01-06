<?php
session_start();
require('../lib/dbconnect.php');

if(empty($_SESSION['id'])) {
  header('Location: ../public_html/index.php');
  exit();
}

$users = $db->prepare('SELECT id FROM users WHERE id = ?');
$users->execute(array($_REQUEST['id']));
$user = $users->fetch();

if($_SESSION['id'] === $user['id']) {
  $del = $db->prepare('DELETE FROM users WHERE id = ?');
  $del->execute(array($_SESSION['id']));

  header('Location: ../public_html/delete_done.php');
  exit();
} else {
  header('Location: ../public_html/index.php');
  exit();
}
