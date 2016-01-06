<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title>ShortUrl Sample</title>
	<link type="text/css" rel="stylesheet" href="css/index.css" />
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>短縮URL生成サンプル</h1>
		</div>
		<div id="body">
			<?php if(isset($message)): ?>
			<p><?= $message ?></p>
			<?php endif; ?>
			<p>
			<form action="" method="post">
				<input type="text" name="original_url" value="" />
				<input type="submit" value="送信" />
			</form>
			</p>
			<?php if(isset($shorturl)): ?>
			<p id="shorturl">
			<?= $shorturl ?>
			<p>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
