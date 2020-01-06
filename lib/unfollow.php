<?php
session_start();
require('../lib/dbconnect.php');

if(isset($_SESSION['id'])) {
  $follow_list = $db->prepare('SELECT * FROM follower WHERE id = ?');
  $follow_list->execute(array($_REQUEST['id']));
  $unfollow = $follow_list->fetch();

    if($unfollow['follower_id'] === $_SESSION['id']) {
    $del = $db->prepare('DELETE FROM follower WHERE id = ?');
    $del->execute(array($unfollow['id']));
  }
}

header('Location: ../public_html/follower.php?follow='. $_SESSION['id']);
exit();
