<?php
session_start();
require('../lib/dbconnect.php');
require('../lib/function.php');


/*エラーの検出
  記入事項に空欄があったり、パスワードが既定の長さでなかったり、
  或いは画像形式が指定のものでない場合、エラー値を保存する*/
if(!empty($_POST)) {
  if($_POST['name'] === '') {
    $error['name'] = 'blank';
  }
  if($_POST['email'] === '') {
    $error['email'] = 'blank';
  }
  if($_POST['pass'] === '') {
    $error['pass'] = 'blank';
  } elseif(strlen($_POST['pass']) < 4 || strlen($_POST['pass']) > 20) {
    $error['pass'] = 'length';
  }
  $fileName = $_FILES['icon']['name'];
  if(!empty($fileName)) {
    $fileType = substr($fileName, -3);
    if($fileType != 'jpg' && $fileType != 'png') {
      $error['file'] = 'type';
    }
  }

// アカウント重複の確認
  if(empty($error)) {
    $users = $db->prepare('SELECT COUNT(*) AS count FROM users WHERE email = ?');
    $users->execute(array($_POST['email']));
    $user = $users->fetch();
    if($user['count'] > 0 ) {
      $error['email'] = 'duplication';
    }
  }

// もし何もなければ、アイコンファイルと入力値を保存する
  if(empty($error)) {
    if(!empty($_FILES['icon']['name'])) {
      $iconImage = date('TmdHis') . $_FILES['icon']['name'];
      move_uploaded_file($_FILES['icon']['tmp_name'], '../user_icon/' . $iconImage);
    } else {
      $iconImage = 'default_icon.png';
    }
    $_SESSION['join'] = $_POST;
    $_SESSION['join']['icon'] = $iconImage;
    header('Location: check.php');
    exit();
  }
}

/*書き直しでchek.phpから戻ってくる場合、記入欄に値を入れ直す
  アイコンのエラー文を出すために$error['rewrite']に値を入れておく*/
if($_REQUEST['action'] === 'rewrite') {
  $_POST = $_SESSION['join'];
  $error['rewrite'] = true;
}

 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/join.css">
    <title>会員登録 / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title">会員登録</h2>
      <a class="login" href="../login.php">ログイン</a>
    </header>

    <main>
      <div class="main-contents">
      <p class="text">以下のフォームに内容を記入してください。</p>
      <form action="" method="post" enctype="multipart/form-data">
        <dl>
          <dt>ニックネーム</dt>
          <dd><input class="form" type="text" name="name" size="40" maxlength="255"
            value="<?php echo h($_POST['name']); ?>">
            <?php if($error['name'] === 'blank'): ?>
              <p class="error">ニックネームを入力してください</p>
            <?php endif; ?>
          </dd>

          <dt>メールアドレス</dt>
          <dd>
            <input class="form" type="text" name="email" size="40" maxlength="20"
            value="<?php echo h($_POST['email']); ?>">
            <?php if($error['email'] === 'blank'): ?>
              <p class="error">メールアドレスを入力してください</p>
            <?php elseif($error['email'] === 'duplication'): ?>
              <p class="error">指定されたメールアドレスは登録済みです</p>
            <?php endif; ?>
          </dd>

          <dt>パスワード</dt>
          <dd>
            <input class="form" type="text" name="pass" size="40" maxlength="20"
            value="<?php echo h($_POST['pass']); ?>">
            <?php if($error['pass'] === 'blank'): ?>
              <p class="error">パスワードを入力してください</p>
            <?php elseif($error['pass'] === 'length'): ?>
              <p class="error">パスワードは4文字以上20文字以下にしてください</p>
            <?php endif; ?>
          </dd>

          <dt>アイコン</dt>
          <dd>
            <input class="icon-form" type="file" name="icon" size="40">
            <?php if($error['file'] === 'type'): ?>
              <p class="error">アイコンは「.jpg」或いは「.png」ファイルを指定してください</p>
            <?php elseif(!empty($error)) :?>
              <p class="error">申し訳ありませんが再度ファイルを指定してください</p>
            <?php endif; ?>
          </dd>
        </dl>
        <input class="button" type="submit" value="入力内容を確認する">
      </form>
    </div>
    </main>
  </body>
</html>
