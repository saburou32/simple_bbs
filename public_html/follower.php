<?php
session_start();
require('../lib/dbconnect.php');
require('../lib/function.php');

// ログインしていない場合ホームに返す
if(empty($_SESSION['id'])) {
  header('Loication: index.php');
  exit();
}

// 自分をフォローしている人のリスト
if(isset($_REQUEST['follower'])){
$followers = $db->prepare('SELECT u.id, u.name, u.icon, u.profile, f.* FROM users u, follower f
WHERE u.id = f.follower_id AND f.follow_id = ? ORDER BY f.created DESC');
$followers->execute(array($_REQUEST['follower']));

$profile = $db->prepare('SELECT u.id FROM users u, follower f
  WHERE u.id = f.follower_id AND f.follow_id = ?');
$profile->execute(array($_REQUEST['follower']));
$pro = $profile->fetch();
}

// 自分がフォローしている相手のリスト
if(isset($_REQUEST['follow'])){
$followers = $db->prepare('SELECT u.id, u.name, u.icon, u.profile, f.* FROM users u, follower f
WHERE u.id = f.follow_id AND f.follower_id = ? ORDER BY f.created DESC');
$followers->execute(array($_REQUEST['follow']));

$profile = $db->prepare('SELECT u.id FROM users u, follower f
  WHERE u.id = f.follow_id AND f.follower_id = ?');
$profile->execute(array($_REQUEST['follow']));
$pro = $profile->fetch();
}
 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/sub.css">
    <title>フォロー|フォロワー / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title">
        <?php if(isset($_REQUEST['follower'])) {
        echo 'フォロワーリスト';
      } elseif(isset($_REQUEST['follow'])) {
        echo 'フォローリスト';
      } ?>
    </h2>
      <a class="logout" href="../lib/logout.php">ログアウト</a>
    </header>

    <div class="main-container">
      <div class="sidebar">
        <p>&raquo;<a href="index.php">ホーム</a></p>
      </div>
      <div class="main-contents">
        <?php while($follower = $followers->fetch()): ?>
          <div class="msg-list">
            <div style="height:40px; display:inline-block;">
              <img class="icon" src="../user_icon/<?php echo h($follower['icon']); ?>" alt="icon" height="100%">
              <p class="name">
                <a href="profile.php?id=<?php echo h($pro['id']); ?>"><?php echo h($follower['name']); ?></a>
              </p>
            </div>
            <?php if($_REQUEST['follow'] && $_SESSION['id'] = $_REQUEST['follow']) :?>
              <a class="unfollow" href="../lib/unfollow.php?id=<?php echo h($follower['id']); ?>">フォローを外す</a>
            <?php endif; ?>
            <p class="msg">
              <?php echo h($follower['profile']); ?>
            </p>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </body>
</html>
