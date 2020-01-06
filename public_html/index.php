<?php
session_start();
require('../lib/dbconnect.php');
require('../lib/function.php');

// セッションにIDとTIMEがセットされている場合、TIMEを更新しつつユーザーデータを$userにいれる
if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();

  $users = $db->prepare('SELECT * FROM users WHERE id = ?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();

} else {
  header('Location: login.php');
  exit();
}

// メッセージ投稿処理
if(!empty($_POST)) {
  $fileName = $_FILES['image']['name'];
  if(!empty($fileName)) {
    $fileType = substr($fileName, -3);
    if($fileType != 'jpg' && $fileType != 'png') {
      $error['file'] = 'type';
    }
  }

// エラーが空なら画像保存
  if(empty($error)) {
    if(!empty($_FILES['image']['name'])){
      $post_picture = date('TmdHis') . $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], '../post_picture/' . $post_picture);
    }
  }

// メッセージが入っており、エラーがないなら投稿処理
  if($_POST['msg'] != '' && empty($error)) {
    $message = $db->prepare('INSERT INTO posts SET user_id = ?, message = ?,
    reply_post_id = ?, picture = ?, created = NOW()');

    if(empty($_POST['reply_post_id'])) {
      $reply_post_id = 0;
    } else {
      $reply_post_id = $_POST['reply_post_id'];
    }
    $message->execute(array(
      $user['id'],
      $_POST['msg'],
      $reply_post_id,
      $post_picture
    ));

    header('Location: index.php');
    exit();
  }
}

// 返信
if(isset($_REQUEST['res'])) {
  $response = $db->prepare('SELECT u.name FROM users u,
    posts p WHERE u.id = p.user_id AND p.id = ?');
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  $reply = '@'. $table['name'];
}

// メッセージ関連情報query
$posts = $db->query('SELECT u.name, u.icon, p.* FROM users u, posts p
WHERE u.id = p.user_id ORDER BY p.created DESC');

 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/master.css">
    <title>ホーム / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title">ホーム</h2>
      <a class="logout" href="../lib/logout.php">ログアウト</a>
    </header>

    <main>
      <div class="main-container">
        <div class="sidebar">
          <div class="side-contents">
            <div style="height:40px;">
            <img class="icon" src="../user_icon/<?php echo h($user['icon']); ?>" alt="icon" height="100%">
            <p class="name">
              <a href="profile.php?id=<?php echo h($user['id']); ?>"><?php echo h($user['name']); ?></a>
            </p>
            </div>
            <p class="follower"><a href="follower.php?follow=<?php echo h($user['id']); ?>">フォロー</a>
               / <a href="follower.php?follower=<?php echo h($user['id']); ?>">フォロワー</a>
            </p>

            <form action="" method="post" enctype="multipart/form-data">
              <textarea class="msg-area" name="msg" rows="8" cols="35"
              placeholder="メッセージを入力してください"><?php echo h($reply); ?></textarea>
              <input type="hidden" name="reply_post_id" value="<?php echo h($_REQUEST['res']); ?>">
              <input type="file" name="image" size="40">
                <?php if($error['file'] === 'type'): ?>
                  <p class="error">アイコンは「.jpg」或いは「.png」ファイルを指定してください<br>
                  申し訳ありませんが再度ファイルを指定してください</p>
                <?php endif; ?>
              <div>
                <input class="button" type="submit" value="投稿する">
              </div>
            </form>
            <?php if($_REQUEST['follow_yet']): ?>
              <p class="error" style="padding-top: 10px;">指定されたアカウントは既にフォロー済みです</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="main-contents">
          <?php foreach($posts as $post): ?>
            <div class="msg-list">
              <div style="height:40px">
                <img class="icon" src="../user_icon/<?php echo h($post['icon']); ?>" alt="icon" height="100%">
              <p class="name"><?php echo h($post['name']); ?></p>
              <?php if($_SESSION['id'] != $post['user_id']): ?>
                <a class="button" style="margin-left: 20px;"
                href="../lib/follow.php?id=<?php echo h($post['user_id']); ?>">フォロー</a>
              <?php endif; ?>
              </div>

              <div class="msg">
                <p class="msg-text"><?php echo makeLink(h($post['message'])); ?></p>
                <?php if(!empty($post['picture'])): ?>
                  <div style="width: 350px;">
                    <img src="../post_picture/<?php echo h($post['picture']) ?>" alt="picture" width="100%">
                  </div>
                <?php endif; ?>
                [<a href="index.php?res=<?php echo h($post['id']); ?>">返信</a>]
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
          <?php endforeach; ?>
        </div>
      </div>
    </main>

    <footer>
      <a class="logout" href="delete_account.php">退会</a>
    </footer>
  </body>
</html>
