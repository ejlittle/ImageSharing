<?
	session_start();
	ob_start();
	//if session variable not set, redirect to login page
	if (!isset($_SESSION['authenticated'])) {
		header('Location: ');
		exit;
	}
	else {
		header('Location: ');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
		 Remove this if you use the .htaccess -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Admin Control Panel - Penny Pictures</title>
	<meta name="description" content="" />
	<meta name="author" content="Eric J. Little" />
	<meta name="robots" content="noindex, nofollow" />

	<meta name="viewport" content="width=device-width; initial-scale=1.0" />

	<style type="text/css">
	body {
		margin: 0;
		padding: 0;
		background: #222;
		font-family: Arial, Helvetica, sans-serif;
	}
	.wrapper {
		width: 58em;
		padding: 1em;
		margin: 1em auto;
		background: #fff;
		border: 1px solid #000;
	}
	</style>
</head>

<body>
	<div class="wrapper">
		<h1>Admin Control Panel</h1>
		<p>This will be the control panel home page that lets you choose whether to upload, edit, or delete images.</p>
		<h2>Options</h2>
		<ul>
			<li><a href="multiupload.php">Upload Files</a></li>
		</ul>
	</div>
</body>
</html>
