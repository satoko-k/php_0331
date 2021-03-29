<?php

session_start();
include("function.php");
loginCheck();

// echo $_SESSION["chk_ssid"];
// echo $_SESSION["u_name"] ;
// echo $_SESSION["rank_flg"];

/////////////////////////
// データの抽出
///////////////////////

// 1:DBに接続する（エラー処理の追加）
$pdo = db_connect();


//2：データ登録のSQL作成[選択]

    $stmt = $pdo->prepare("SELECT * FROM camp_plant_table");

    // SQLの実行
    $status = $stmt->execute();



// 3.データの表示
$view = "";
if($status==false){
    //execute (SQL実行時にErrorがある場合）
    $error = $stmt->errorInfo();
exit("ErrorQuery:".$error[2]);   //"ErrorQuery:"を日本語にしてもＯＫ「sqlエラーです」
} else {
    //Selectデータの数だけ自動でループして$resultに入れてくれる
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
           //「.=」で追加　「=」だと上書きしてしまう

           $view .='<div class="plantBox">';
           $view .='<h3>' ;
           $view .= $result["p_name"];
           $view .='</h3>';
           $view .='<p>';
           $view .= $result["f_name"];
           $view .='</p>';
           $view .='<p>';
           $view .= "季節 :".$result["season"]." : "."分類 :" .$result["category"];
           $view .='</p>';
           $view .='<figure class="hover-parent">';     
           $view .='<img class="plantImg" src="img/';
           $view .=$result["image"];
           $view .='">';
           $view .='<figcaption class="hover-mask">';     
           $view .=$result["comment"];
           $view .='</figcaption>';     
           $view .='</figure>';
           $view .='<?php if ($count == 0) : ?>';
           $view .='<form action="likes_test.php" method="post">';    
           $view .='<button type="submit" class="find" name="plant_id" value="';    
           $view .=$result["id"];   
           $view .='">お気に入りに登録</button>';       
           $view .='<?php else : ?>';
            $view .='<form action="zukan.php" method="post">';    
            $view .='<button type="submit" class="find" name="plant_id" value="';    
            $view .=$result["id"];   
            $view .='">お気に入り解除</button>';    
           $view .='</div>';  
     

    }
}





?>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>雑草図鑑</title>
    <link rel="stylesheet" href="css/reset.css /">
    <link rel="stylesheet" href="css/common.css" />
    <link rel="stylesheet" href="css/category.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p&display=swap" rel="stylesheet">
    
  </head>
  <body>
    <header>
    <div class="log">
        <p class="loginPlace"><?php echo h($_SESSION["u_name"]);?> <span style="font-size:14px;">さん</span><a href="logout.php" class="btn_logout">ログアウト</a></p>
      </div>
    <div class="header">
      <h1>雑草図鑑</h1>
      <p>身近にすごす草花たち。よく観察すれば見たことのあるものばかりのはず。</p>
    </div>
    </header>
    <div class="cp_breadcrumb" id="nav">
      <ul class="breadcrumbs">
      <li><a href="top.php">Home</a></li>
      <li class="lastList">Category</li>
      </ul>
  </div>

    <main>
     <h2>すべての雑草たち</h2>

     <div class="search">
        <form class="searchArea" method="post" action="season.php">
          <p>絞り込む </p>
          <div><p>

            <input type="radio" id="season1" class="radio_input" name="season" value="春"><label class="radio_label" for="season1" >春の雑草</label>
            <input type="radio" id="season2" class="radio_input" name="season" value="夏"><label class="radio_label" for="season2">夏の雑草</label>
            <input type="radio" id="season3" class="radio_input" name="season" value="秋"><label class="radio_label" for="season3">秋の雑草</label>
            <input type="radio" id="season4" class="radio_input" name="season" value="冬"><label class="radio_label" for="season4">冬の雑草</label>
            <input type="radio" id="season5" class="radio_input" name="season" value="%" checked><label class="radio_label" for="season5" >すべて</label><br>
            <hr>
            <input type="radio" id="category1" class="radio_input" name="category" value="一年草"><label class="radio_label" for="category1" >一年草</label>
            <input type="radio" id="category2" class="radio_input" name="category" value="多年草"><label class="radio_label" for="category2">多年草</label>
            <input type="radio" id="category3" class="radio_input" name="category" value="%" checked><label class="radio_label" for="category3">すべて</label>

            </p>
          </div>
          <p><input type="submit" class="form-Btn" value="検索" /></p> 
        </form>
      </div>  
      <hr>
     <div class="weeds">
     <?php echo ($view) ?>
      </div>


      <p id="page-top"><a href="#nav">PAGE TOP</a></p>

    </main>

    <footer></footer>
    <!-- jQueryを読み込む！必ず先に！ -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- slicknojsはiQueryの次に読み込む
    <script src="js/slick.js"></script> -->
    <!-- jsを読み込む -->
    <script src="js/app.js"></script>
  <script>
  $(function () {
                $('.find').on('click', function () {
                    if ($(this).text() === 'お気に入りに登録') {
                        $(this).text('お気に入りに解除');
                    } else {
                        $(this).text('お気に入りに登録');
                    }
                });
            });

</script>
    
  </body>
</html>
