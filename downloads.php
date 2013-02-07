<?
	session_start();
	ob_start();
	include('includes/connection.inc.php');
	$conn = dbConnect('query');
	//if session variable not set, redirect to login page
	if (!isset($_SESSION['authenticated'])) {
		header('Location: ');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
		 Remove this if you use the .htaccess -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Portrait Session Downloads</title>
	<meta name="description" content="" />
	<meta name="author" content="Eric J. Little" />
	<meta name="robots" content="noindex, nofollow" />

	<meta name="viewport" content="width=device-width; initial-scale=1.0" />

	<link rel="stylesheet" href="styles/styles.css" type="text/css" media="screen" />
</head>

<body>
	<div id="sitewrapper">
        <div class="wrapper">
            <div id="header">
                <h1>Penny's Pictures</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="downloads.php" id="current">Downloads</a></li>
                </ul>
                <div id="logout">
                    <? include('includes/logout.inc.php'); ?>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="banner">
                <h2><strong>Portrait Session Downloads</strong> - Professional photo archives.</h2>
                <!-- Add this in when I program the funtionality -->
                <!--
                <hr />
                <p>Select an album &raquo; <a href="personal">Personal</a> | <a href="our365newborn">Our365 Newborn</a> | <a href="studioonetoone">Studio One To One</a></p>
                -->
            </div>
            <div id="pictures">
                <ul>
                    <li><a href="mallSanta2012.zip">Fashion Square Mall Santa 2012</a> - <em>2 MB</em></li>
                    <li><a href="studioOneToOne.zip">Studio One to One</a> - <em>110 MB</em></li>
                    <li><a href="our365.zip">Our 365</a> - <em>20 MB</em></li>
                </ul>
            </div>
        </div>
		<div id="shadow"></div>
	</div>
</body>
</html>

