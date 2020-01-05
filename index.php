<?php
session_start();
require('dbconnect.php');
require('function.php');

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

  if(empty($error)) {
    if(!empty($_FILES['image']['name'])){
      $post_picture = date('TmdHis') . $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], 'post_picture/' . $post_picture);
    }
  }

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
  $response = $db->prepare('SELECT u.name, p.* FROM users u,
    posts p WHERE u.id = p.user_id AND p.id = ?');
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  $reply = '@'. $table['name']. ' '. $table['message'];
}

// メッセージ関連情報query
$posts = $db->query('SELECT u.name, u.icon, p.* FROM users u, posts p
WHERE u.id = p.user_id ORDER BY p.created DESC');
 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="master.css">
    <title>ホーム / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title">書き込み掲示板</h2>
      <a class="logout" href="logout.php">ログアウト</a>
    </header>

    <main>
      <div class="main-container">
        <div class="sidebar">
          <div class="side-contents">
            <img class="icon" src="user_icon/<?php echo h($user['icon']); ?>" alt="icon" width="30px" height="30px">
            <p class="name"><?php echo h($user['name']); ?></p>
            <p class="follower">フォロー<a href="follower.php"><?php echo '人数';?></a>
               / フォロワー<a href="follower.php"><?php echo '人数';?></a>
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
          </div>
        </div>

        <div class="main-contents">
          <?php foreach($posts as $post): ?>
            <div class="msg-list">
              <img class="icon" src="user_icon/<?php echo h($post['icon']); ?>"
               alt="icon" width="30px" height="30px">
              <p class="name"><?php echo h($post['name']); ?></p>

              <div class="msg">
                <p class="msg-text"><?php echo makeLink(h($post['message'])); ?></p>
                <?php if(!empty($post['picture'])): ?>
                  <div style="width: 350px;">
                    <img src="post_picture/<?php echo h($post['picture']) ?>" alt="picture" width="100%">
                  </div>
                <?php endif; ?>
                [<a href="index.php?res=<?php echo h($post['id']); ?>">返信</a>]
                <a href=""><img src="icon/onpu.png" alt=fav height="13px" width="13px"></a>
                <p><a href="view.php?id=<?php echo h($post['id']); ?>"><?php echo h($post['created']); ?></a>
                  <?php if($post['reply_post_id'] > 0): ?>
                    <a href="view.php?id=<?php echo h($post['reply_post_id']); ?>">返信元メッセージ</a>
                  <?php endif; ?>

                  <?php if($_SESSION['id'] === $post['user_id']): ?>
                    [<a href="delete.php?id=<?php echo h($post['id']) ?>">削除</a>]
                  <?php endif; ?>
                </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </main>

    <footer>
      <p class="logout"><a href="">退会</a></p>
    </footer>
  </body>
</html>
