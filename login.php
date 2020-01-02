<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="join/join.css">
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
          登録がまだの方はこちらからどうぞ。</p>
          <p>&raquo;<a href="join/join.php">登録手続きをする</a></p>
        </div>
        <form action="" method="post">
          <dl>
            <dt>メールアドレス</dt>
            <dd>
              <input class="form" type="text" name="email" size="40" maxlength="255"
            value="">
            </dd>
            <dt>パスワード</dt>
            <dd>
              <input class="form" type="text" name="pass" size="40" maxlength="20"
              value="">
            </dd>
            <dt>ログイン状態の情報</dt>
            <dd>
              <input type="checkbox" name="save" value="on">
              <label for="save">次回からは自動でログインする</label>
            </dd>
          </dl>
          <div>
            <input class="button" type="submit" value="ログイン">
        </div>
      </form>
      </div>
    </main>
  </body>
</html>
