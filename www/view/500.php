<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title>500 - Internal Server Error</title>
	<style type="text/css">

	body { background-color: #f4f2eb; margin: 20px 10px 10px 10px; font-family: Arial,"ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro",Osaka,"メイリオ",Meiryo,"ＭＳ Ｐゴシック","MS PGothic",sans-serif; font-size: 12px; color: #b16460; }

	#container  {
		max-width: 1000px;
		padding: 0px;
		margin: 0 auto;
	}
	#header {
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		background-color: #fffffa;
		-webkit-border-radius: 10px 10px 0 0;
		-moz-border-radius: 10px 10px 0 0;
		border-radius: 10px 10px 0 0;
		border: 1px solid #f8ea1a;
	}
	#header h1 {
		color: #b16460;
		font-weight: bold;
		font-size: 16px;
		padding: 10px;
		margin: 0px;
	}
	#body {
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		background-color: #fffffa;
		-webkit-border-radius: 0 0 10px 10px;
		-moz-border-radius: 0 0 10px 10px;
		border-radius: 0 0 10px 10px;
		border: 1px solid #f8ea1a;
		padding: 10px;
	}
	</style>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>500 - Internal Server Error!</h1>
		</div>
		<div id="body">
			<p><?= $message ?></p>
		</div>
	</div>
</body>
</html>
