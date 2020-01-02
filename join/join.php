<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="join.css">
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
      <form class="" action="" method="post">
        <dl class="">
          <dt>メールアドレス</dt>
          <dd>
            <input class="form" type="text" name="email" size="40" maxlength="255"
            value="">
          </dd>
          <dt>ニックネーム</dt>
          <dd>
            <input class="form" type="text" name="name" size="40" maxlength="20"
            value="">
          </dd>
          <dt>パスワード</dt>
          <dd>
            <input class="form" type="text" name="pass" size="40" maxlength="20"
            value="">
          </dd>
          <dt>アイコン</dt>
          <dd>
            <input class="icon-form" type="file" name="icon" size="40">
          </dd>
        </dl>
        <input class="button" type="submit" value="入力内容を確認する">
      </form>
    </div>
    </main>
  </body>
</html>
