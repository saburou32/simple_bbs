<?php
session_start();
require('dbconnect.php');
require('function.php');

if(empty($_REQUEST['id'])) {
  header('Location: index.php');
  exit();
}

// メッセージ関連情報のquery
$posts = $db->prepare('SELECT u.name, u.icon, p.* FROM users u, posts p
WHERE u.id = p.user_id  AND p.id = ? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST['id']));
 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="master.css">
    <title>メッセージ / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title">メッセージ</h2>
      <a class="logout" href="logout.php">ログアウト</a>
    </header>

    <div class="main-container">
      <div class="sidebar">
        <p class="home">&raquo;<a href="index.php">ホーム</a></p>
      </div>
      <div class="main-contents">
        <?php if($post = $posts->fetch()): ?>
          <div class="msg-list">
            <img class="icon" src="user_icon/<?php echo h($post['icon']) ?>" alt="icon"
            width="30px" height="30px">
            <p class="name"><?php echo h($post['name']); ?></p>
            <div class="msg">
              <p class="msg-text"><?php echo h($post['message']); ?></p>
              <?php if(!empty($post['picture'])): ?>
                <div style="width: 350px;">
                  <img src="post_picture/<?php echo h($post['picture']) ?>" alt="picture" width="100%">
                </div>
              <?php endif; ?>
              <p ><?php echo h($post['created']); ?></p>
              <?php else: ?>
                <p>その投稿は削除されたか、存在しません</p>
              <?php endif; ?>
            </div>
          </div>
      </div>
    </div>
  </body>
</html>
