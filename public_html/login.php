<?php
session_start();
require('../lib/dbconnect.php');
require('../lib/function.php');

// ログイン処理
if(!empty($_POST)) {
  if($_POST['email'] != '' && $_POST['pass'] != '') {
    $login = $db->prepare('SELECT * FROM users WHERE email = ? ');
    $login->execute(array($_POST['email']));
    $user = $login->fetch();

// 該当するアカウントのパスワードが正しければセッションにIDとログイン時間をセット
    if(password_verify($_POST['pass'], $user['pass'])) {
      $_SESSION['id'] = $user['id'];
      $_SESSION['time'] = time();

      header('Location: index.php');
      exit();

// emailとpassの組み合わせが一致しない場合のエラー
    } else {
      $error['login'] = 'failed';
    }

// アドレスかパスワードが空欄である場合のエラー
  } else {
    $error['login'] = 'blank';
  }
}

 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/join.css">
    <title>ログイン / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title">ログインフォーム</h2>
    </header>
    <main>
      <div class="main-contents">
        <div class="text">
          <p>メールアドレスとパスワードを記入してログインしてください。<br>
          会員登録がまだの方はこちらからどうぞ。</p>
          <p>&raquo;<a href="../join/join.php">登録手続きをする</a></p>
        </div>
        <form action="" method="post">
          <dl>
            <dt>メールアドレス</dt>
            <dd>
              <input class="form" type="text" name="email" size="40" maxlength="255"
            value="<?php echo h($_POST['email']); ?>">
            </dd>
            <dt>パスワード</dt>
            <dd>
              <input class="form" type="text" name="pass" size="40" maxlength="20"
              value="<?php echo h($_POST['pass']) ?>">
            </dd>
          </dl>
          <?php if($error['login'] === 'blank'): ?>
            <p class="error">メールアドレスとパスワードを記入してください</p>
          <?php elseif($error['login'] === 'failed'): ?>
            <p class="error">ログインに失敗しました
              <br>メールアドレスとパスワードをもう一度ご確認ください</p>
          <?php endif; ?>
          <div>
            <input class="button" type="submit" value="ログイン">
        </div>
      </form>
      </div>
    </main>
  </body>
</html>
