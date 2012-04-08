<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<style type="text/css">
	.error-message {
		text-align: center;
		color: #E5E5E5;
		display: block;
		font-weight: bold;
		font-family: Arial, Verdana;
	}
	
	.error-message h1 {
		font-size: 214px;
		line-height: 200px;
		margin-top: 100px;
		height: 200px;
		margin-bottom: 2px
	}
	
	.error-message h4 {
		font-size: 48px;
		line-height: 48px;
		letter-spacing: -1px
	}
	</style>
</head>
<body>
	<div class="error-message">
		<h1><?=$heading?></h1>
		<h4><?=$message?></h4>
	</div>
</body>
</html>


