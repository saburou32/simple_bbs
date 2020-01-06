<?php
session_start();
require('../lib/dbconnect.php');
require('../lib/function.php');

if(empty($_REQUEST['id'])) {
  header('Location: index.php');
  exit();
}

// リクエストIDからデータ取得
$profile_data = $db->prepare('SELECT id, name, icon, profile FROM users WHERE id = ?');
$profile_data->execute(array($_REQUEST['id']));
$profile = $profile_data->fetch();

$posts = $db->prepare('SELECT u.name, u.icon, p.* FROM users u, posts p
  WHERE u.id = p.user_id AND p.user_id = ? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST['id']));

 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/master.css">
    <title><?php echo h($profile['name']); ?>さん / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title"><?php echo h($profile['name']); ?>さんのプロフィール</h2>
      <a class="logout" href="../logout.php">ログアウト</a>
    </header>

    <div class="main-container">
      <div class="sidebar">
        <div class="side-contents">
          <div style="height:40px;">
            <img class="icon" src="../user_icon/<?php echo h($profile['icon']); ?>" alt="icon" height="100%">
            <p class="name"><?php echo h($profile['name']); ?></p>
          </div>
          <p class="follower"><a href="follower.php?follow=<?php echo h($profile['id']); ?>">フォロー</a>
             / <a href="follower.php?follower=<?php echo h($profile['id']); ?>">フォロワー</a>
          </p>
          <hr>
          <p class="msg">
            <?php if($profile['profile']){
              echo h($profile['profile']);
            } else {
              echo 'プロフィールはまだ登録されていません';
            }
            ?>
          </p>
          <hr style="margin-bottom: 10px;">
          <?php if($_SESSION['id'] === $profile['id']): ?>
            <a class="button" href="change_profile.php" style="font-size:13px;">プロフィールを変更する</a>
          <?php endif; ?>
          <p style="margin:10px;">&raquo;<a href="index.php">ホーム</a></p>
        </div>
      </div>
      <div class="main-contents">
        <?php while($post = $posts->fetch()): ?>
          <div class="msg-list">
            <div style="height:40px;">
              <img class="icon" src="../user_icon/<?php echo h($post['icon']); ?>" alt="icon" height="100%">
              <p class="name"><?php echo h($post['name']); ?></p>
            </div>
            <div class="msg">
              <p class="msg-text"><?php echo makeLink(h($post['message'])); ?></p>

              <?php if(!empty($post['picture'])): ?>
                <div style="width: 350px;">
                  <img src="../post_picture/<?php echo h($post['picture']) ?>" alt="picture" width="100%">
                </div>
              <?php endif; ?>

              <?php if($post['user_id'] != $_SESSION['id']): ?>
                [<a href="index.php?res=<?php echo h($post['id']); ?>">返信</a>]
              <?php endif; ?>
              <p><a href="view.php?id=<?php echo h($post['id']); ?>"><?php echo h($post['created']); ?></a>

                <?php if($post['reply_post_id'] > 0): ?>
                  <a href="view.php?id=<?php echo h($post['reply_post_id']); ?>">返信元メッセージ</a>
                <?php endif; ?>

                <?php if($_SESSION['id'] === $post['user_id']): ?>
                  [<a href="../lib/delete.php?id=<?php echo h($post['id']) ?>">削除</a>]
                <?php endif; ?>
              </p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </body>
</html>
