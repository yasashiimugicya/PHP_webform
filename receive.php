<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>データ確認</title>
</head>
<body>


	<?php
		// エラーを格納するための変数
		$err = '';

		// POSTデータを受信したら
		if($_SERVER['REQUEST_METHOD']=='POST'){

			// 受信データをエスケープ
			$myname = htmlspecialchars($_POST['myname'], ENT_QUOTES,'UTF-8');
			$age = (int)$_POST['age'];

			// 名前の入力チェック
			if($myname){
				// 名前がが入力されていたら
				//　文字数チェック
				if(strlen($myname) > 30){$err='ユーザー名が長いです';}
			}else{
				// 名前の入力が空だった場合
				$err = '名前の入力がありません';
			}

			// 年齢の入力チェック
			// 半角数字のチェック
			if(preg_match('/^[0-9]+$/', $age)){
				// 200歳以上はエラーとみなす
				if($age > 200){
					$err = '正しい年齢を入力ください';
				}
			}else{
				$err = '年齢は半角数字を入れてください'; 
			}

		}else{
			$err = '正常にデータを受信できませんでした';
			$myname = '';
			$age = '';
		}

		// 文字の入力チェックを行い、エラーの有り無しを判定し、
		// 確認画面か、再度フォームを出力するかを切り分け
		if($err){
			// エラーの場合
			echo 'エラー：'.$err;
			echo '<form action="receive.php" method="post">';
			echo '名前：<input type="text" name="myname" value="'.$myname.'"><br>';
			echo '年齢：<input type="number" name="age" value="'.$age.'"><br>';
			echo '<input type="submit" value="データを送信">';
			echo '</form>';

		}else{ 
				// エラーが無かった場合
				// セッションにデータを格納する
				session_start();
				$_SESSION['myname'] = $myname;
				$_SESSION['age'] = $age;

				// Tokenを生成。Tokenを利用することで、CSRF対策となる
				// 疑似乱数を生成
				$bytes = openssl_random_pseudo_bytes(16);
				// 16進数に変換
				$token = bin2hex($bytes);

				echo 'トークン：'.$token;
				// セッションにセット
				$_SESSION['token'] = $token;
			?>
			<h1>入力データの確認</h1>
			名前：<?php echo $myname ?><br>
			年齢：<?php echo $age ?><br>
			<form action="tnk.php" method="post">
			<input type="hidden" name="token" value="<?php print $token ?>">
			<input type="submit" value="データ送信">
			</form>


<?php
		}

?>

</body>
</html>