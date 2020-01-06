<?php
session_start();
require('../lib/dbconnect.php');
require('../lib/function.php');

// セッションに値がセットされていない場合登録画面に返す
if(!isset($_SESSION['join'])) {
  header('Location: join.php');
  exit();
}

// 記入内容でsubmitされた場合、データを登録
if(!empty($_POST)) {
  $stmt = $db->prepare('INSERT INTO users SET name = ?, email = ?,
  pass = ?, icon = ?, created = NOW()');
  $stmt->execute(array(
    $_SESSION['join']['name'],
    $_SESSION['join']['email'],
    password_hash($_SESSION['join']['pass'], PASSWORD_DEFAULT),
    $_SESSION['join']['icon']
  ));
  unset($_SESSION['join']);

  header('Location: done.php');
  exit();
}
 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/join.css">
    <title>登録内容確認 / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title">登録内容確認</h2>
    </header>

    <main>
      <div class="main-contents">
      <p class="text">以下の内容で登録します。<br>よろしいでしょうか？</p>
      <form action="" method="post">
        <input type="hidden" name="action" value="submit">
        <dl>
          <dt>ニックネーム</dt>
          <dd><input class="form" type="text" name="name" size="40" maxlength="255"
            value="<?php echo h($_SESSION['join']['name']); ?>" readonly></dd>
          <dt>メールアドレス</dt>
          <dd><input class="form" type="text" name="email" size="40" maxlength="255"
            value="<?php echo h($_SESSION['join']['email']); ?>" readonly></dd>
          <dt>パスワード</dt>
          <dd><input class="form" type="text" name="pass" size="40" maxlength="255"
            value="【表示されません】" readonly></dd>
          <dt>アイコン</dt>
          <dd><img src="../user_icon/<?php echo h($_SESSION['join']['icon']); ?>"
            height="50%" width="50%">
          </dd>
        </dl>
          <p class="text"><a href="join.php?action=rewrite">書き直す</a></p>
          <input class="button" type="submit" value="登録する">
        </div>
      </form>
    </div>
    </main>
  </body>
</html>
