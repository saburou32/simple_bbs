<?php
session_start();
require('../lib/function.php');

if(empty($_SESSION['id'])) {
  header('Location: index.php');
  exit();
}
 ?>

 <!DOCTYPE html>
 <html lang="jp">
   <head>
     <meta charset="utf-8">
     <link rel="stylesheet" href="../css/join.css">
     <title>退会/ 書き込み掲示板</title>
   </head>
   <body>
     <header>
       <h2 class="head-title">退会</h2>
     </header>
     <main>
       <div class="main-contents" style="height:300px;">
         <h3 style="color:#ff82b2; text-align:center; margin-top:50px;">
           退会した場合、元に戻すことはできません。<br>
           本当に退会しますか？</h3>
         <div style="margin-top:50px; width:100%; text-align:center;">
           <a class="button" href="../lib/all_delete.php?id=<?php echo h($_SESSION['id']); ?>">退会</a>
           <a class="button" style="margin-left:50px;" href="index.php">戻る</a>
         </div>
       </div>
     </main>
   </body>
 </html>
