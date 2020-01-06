<?php
session_start();
require('../lib/dbconnect.php');
require('../lib/function.php');

// ログインしていない場合ホームに返す
if(empty($_SESSION['id'])) {
  header('Loication: index.php');
  exit();
}

// プロフィールに飛ばすようのデータ
$my_profile = $db->prepare('SELECT id, name, profile, icon FROM users WHERE id = ?');
$my_profile->execute(array($_SESSION['id']));
$profile = $my_profile->fetch();

// ポストがある場合、エラーチェック
if(!empty($_POST)){
  if($_POST['name'] === '') {
    $error['name'] = 'blank';
  }
  $fileName = $_FILES['icon']['name'];
  if(!empty($fileName)) {
    $fileType = substr($fileName, -3);
    if($fileType != 'jpg' && $fileType != 'png') {
      $error['file'] = 'type';
    }
  }

// ポストに値があり、エラーがなければプロフィールを更新
  if(empty($error)) {
    $change_profile = $db->prepare('UPDATE users SET name = ?, profile = ?, icon = ? WHERE id = ?');
    if(!empty($_FILES['icon']['name'])) {
      $iconImage = date('TmdHis') . $_FILES['icon']['name'];
      move_uploaded_file($_FILES['icon']['tmp_name'], '../user_icon/' . $iconImage);
    } else {
      $iconImage = $profile['icon'];
    }
    $change_profile->execute(array(
      $_POST['name'],
      $_POST['profile'],
      $iconImage,
      $profile['id']
    ));
    header('Location: profile.php?id='. $_SESSION['id']);
    exit();
  }
}

 ?>

<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/join.css">
    <title>プロフィール編集 / 書き込み掲示板</title>
  </head>
  <body>
    <header>
      <h2 class="head-title">プロフィール編集</h2>
    </header>
    <main>
      <div class="main-contents">

        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>ニックネーム</dt>
            <dd>
              <input class="form" type="text" name="name" size="40" maxlength="255"
            value="<?php echo h($profile['name']); ?>">
              <?php if($error['name'] === 'blank'): ?>
                <p class="error">ニックネームを記入してください</p>
              <?php endif; ?>
            </dd>
            <dt>プロフィール</dt>
            <dd>
              <textarea class="form" name="profile" rows="8" cols="52"><?php echo h($profile['profile']); ?></textarea>
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
          <div>
            <input class="button" type="submit" value="登録">
            <p style="margin:10px;">&raquo;<a href="index.php">ホーム</a></p>
        </div>
      </form>
      </div>
    </main>
  </body>
</html>
