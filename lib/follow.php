<?php
session_start();
require('../lib/dbconnect.php');

if(isset($_SESSION['id'])) {
  $id = $_REQUEST['id'];

  $followers = $db->prepare('SELECT COUNT(*) AS count FROM follower WHERE follow_id = ? AND follower_id = ?');
  $followers->execute(array($id, $_SESSION['id']));
  $follower = $followers->fetch();

  if(empty($follower['count'])) {
    $do = $db->prepare('INSERT INTO follower SET follow_id = ?, follower_id = ?, created = NOW()');
    $do->execute(array($id, $_SESSION['id']));
  } else {
    header('Location: ../public_html/index.php?follow_yet=1');
    exit();
  }
}
header('Location: ../public_html/index.php');
exit();
