<?
	//process the script only if the form has been submitted
	if (array_key_exists('login', $_POST)) {
		//start the session
		session_start();
		//connect to the database
		include('includes/connection.inc.php');
		$conn = dbConnect('query');
		//include nukeMagicQuotes and clean the $_POST arary
		include('includes/corefuncs.php');
		nukeMagicQuotes();
		
		$sql = 'SELECT * FROM users';
		foreach ($conn->query($sql) as $row) {
			if ($row['uname'] == $_POST['uname'] && $row['upass'] == sha1($_POST['uname'].$_POST['upass']) && $row['ugroup'] == 'admin') {
				$_SESSION['authenticated'] = '';
				break;
			}
		}
		if(isset($_SESSION['authenticated'])) {
			header('Location: ');
			exit;
		}
		//if the session variable hasn't been set, refuse entry
		else {
			$error = 'Invalid username or password.';
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
	     Remove this if you use the .htaccess -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Login</title>
	<meta name="description" content="" />
	<meta name="author" content="Eric J. Little" />
	<meta name="robots" content="noindex, nofollow" />

	<meta name="viewport" content="width=device-width; initial-scale=1.0" />

	<link rel="stylesheet" href="../styles/styles.css" type="text/css" media="screen" />
</head>

<body>
	<div id="sitewrapper">
	<div class="wrapper">
		<div id="header">
			<h1>Penny's Pictures<span> - Admin Login</span></h1>
			<div class="clearfix"></div>
		</div>
		<form action="" method="post" name="lform" id="lform">
			<ul>
				<li>
					<label for="uname">Username: </label><input type="text" name="uname" id="uname" />
				</li>
				<li>
					<label for="upass">Password: </label><input type="password" name="upass" id="upass" />
				</li>
				<li>
					<input type="submit" name="login" id="login" value="Log In" />
				</li>
				<?
					if(isset($error)) {
						echo '<hr /><li><p class="warning">'.$error.'</p></li>';
					}
				?>
			</ul>
		</form>
		<div id="footer">
			<p>&nbsp;</p>
		</div>
		<img src="../images/owl.png" id="winniethepooh" />
	</div>
	<div id="shadow"></div>
	</div>
</body>
</html>
