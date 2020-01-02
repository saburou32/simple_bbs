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
      <a class="logout" href="login.php">ログアウト</a>
    </header>

    <main>
      <div class="main-container">
        <div class="sidebar">
          <div class="side-contents">
            <img class="icon" src="user_icon/default_icon.png" alt="icon" width="30px" height="30px">
            <p class="name">名前</p>
            <p class="follower">フォロー<a href="follower.php"><?php echo '人数'?></a>
               / フォロワー<a href="follower.php"><?php echo '人数'?></a>
            </p>
            <form class="" action="" method="post">
              <textarea class="msg-area" name="msg" rows="8" cols="35"
              placeholder="メッセージを入力してください"></textarea>
            </form>
          </div>
        </div>

        <div class="main-contents">
          <div class="msg-list">
            <img class="icon" src="user_icon/default_icon.png" alt="icon" width="30px" height="30px">
            <p class="name">投稿者の名前</p>
            <div class="msg">
              <p>投稿内容</p>
              <a href=""><img src="icon/onpu.png" alt=fav height="15px" width="15px"></a>
            </div>
          </div>
        </div>
      </div>
    </main>

    <footer>
      <p class="delete">&raquo;<a href="">退会</a></p>
    </footer>
  </body>
</html>
