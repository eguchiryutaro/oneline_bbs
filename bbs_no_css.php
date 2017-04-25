<?php
  // データベースに接続
		 $dsn = 'mysql:dbname=oneline_bbs;host=localhost';
		 $user = 'root';
		 $password = '';

		// $dsn = 'mysql:dbname=LAA0854007-onelinebbs;host=mysql109.phy.lolipop.lan';
		// $user = 'LAA0854007';
		// $password = 'nidototsukawanai';

		$dbh = new PDO($dsn, $user, $password);
		$dbh->query('SET NAMES utf8');

  //配列で取得したデータを格納
  //配列を初期化
	  	$post_datas = array();

  //post送信をされたらINSERT文を実行する
		if (!empty($_POST)){
 		$nickname = htmlspecialchars($_POST['nickname']);
 		$comment = htmlspecialchars($_POST['comment']);

  //SQLを作成
		$sql = 'INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES ("'.$nickname.'", "'.$comment.'",now());';

  // var_dump($sql);

  //insert文実行
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		}

	//SERECT文の実行
	//SQL文作成(SERECT文)
		$sql = 'SELECT * FROM `posts` ORDER BY `id` DESC';

	  //実行
	  $stmt = $dbh->prepare($sql);
	  $stmt->execute();

	  //繰り返し文でデータの所得 (フェッチ)
	  while (1) {
	  	$rec = $stmt->fetch(PDO::FETCH_ASSOC);
	  	if ($rec == false){
	  		break;
	  	}

	  //echo $rec('nickname');
	  	$post_datas[] = $rec;
	  }
	  
	  //データベースから切断
			$dbh = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body style = "background-color:#ccc"">
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
        <form action="bbs_no_css.php" method="post">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- つぶやくボタン -->
          <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
        </form>
      </div>

      <!-- 画面右側 -->
       <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">

        <?php foreach ($post_datas as $post_each) {?>
          <article class="timeline-entry">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon bg-success">
                      <i class="entypo-feather"></i>
                      <i class="fa fa-github-alt" aria-hidden="true"></i>
                  </div>

                  <div class="timeline-label">
                      <h2><a href="#"><?php echo $post_each['nickname'] . '<br>'; ?></a> <span><?php echo $post_each['comment'] . '<br>'; ?></span></h2>
                      <?php
                      //一旦日時型に変換(string型からDatetime型へ変換)
                      $created = strtotime($post_each['created']);
                      //書式を変換
                      $created = date('Y-m-d' ,$created);
                      ?>
                      <!--p --><?php //echo $post_each['created'] . '<br>'; ?><!--/p --> 
                      <p><?php echo $created; ?></p>
                      <a href="#" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-thumbs-up"></span> like</a>
                  </div>
              </div>
          </article>
          <?php } ?>

          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>
        </div>
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>