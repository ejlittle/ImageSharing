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

	<title>Penny Pictures</title>
	<meta name="description" content="" />
	<meta name="author" content="Eric J. Little" />
	<meta name="robots" content="noindex, nofollow" />

	<meta name="viewport" content="width=device-width; initial-scale=1.0" />

	<link rel="stylesheet" href="styles/styles.css" type="text/css" media="screen" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery.tipsy.js"></script>
	<link rel="stylesheet" href="styles/tipsy.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" href="js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	<script type="text/javascript">
		$(document).ready(function(){
			$('.pic table a').fancybox();
			$('.dwnld').tipsy();
		});
	</script>
</head>

<body>
	<div id="sitewrapper">
	<div class="wrapper">
		<div id="header">
			<h1>Penny's Pictures</h1>
            <ul>
            	<li><a href="index.php" id="current">Home</a></li>
                <li><a href="downloads.php">Downloads</a></li>
            </ul>
			<div id="logout">
				<? include('includes/logout.inc.php'); ?>
			</div>
			<div class="clearfix"></div>
		</div>
		<div id="banner">
			<img src="images/banner.jpg" />
			<h2><strong>Personal Photo Album</strong> - Photos we took with our fancy new camera.</h2>
			<!-- Add this in when I program the funtionality -->
			<!--
			<hr />
			<p>Select an album &raquo; <a href="personal">Personal</a> | <a href="our365newborn">Our365 Newborn</a> | <a href="studioonetoone">Studio One To One</a></p>
			-->
		</div>
		<div id="pictures">
		<?
			define('SHOWMAX', 12);
			
			$getTotal = 'SELECT COUNT(*) FROM images';
			$total = $conn->query($getTotal);
			$row = $total->fetchColumn();
			$totalPix = $row;
			$total->closeCursor();
			
			$curPage = isset($_GET['curPage']) ? $_GET['curPage'] : 1;
			$startRow = ($curPage-1) * SHOWMAX;
			
			$sql = 'SELECT * FROM images ORDER BY image_id DESC LIMIT '.$startRow.', '.SHOWMAX;
			foreach($conn->query($sql) as $row) {
				?>
				<div class="pic">
					<table>
						<tbody>
							<tr>
								<td>
									<a href="images/<? echo $row['filename']; ?>" rel="pennyGallery"><img src="images/thumbs/<? echo $row['thumb']; ?>" /></a>
								</td>
							</tr>
						</tbody>
					</table>
					<p><? echo htmlentities($row['caption']); ?><a href="images/download/<? echo $row['filename']; ?>" class="dwnld" title="Right click and Save link as...">Download &raquo;</a></p>
				</div>
				<?
			}
		?>
		</div>
		<div class="clearfix"></div>
		<div id="footer">
			<ul id="subnav">
				<li>
					<? if($curPage > 1) {
						echo '<a href="'.$_SERVER['PHP_SELF'].'?curPage='.($curPage-1).'" id="prevpage">&laquo; Newer</a>';
					} else {
						echo '&nbsp;';
					} ?>
				</li>
				<li>
					<? if($startRow+SHOWMAX < $totalPix) {
						echo '<a href="'.$_SERVER['PHP_SELF'].'?curPage='.($curPage+1).'" id="nextpage">Older &raquo;</a>';
					} else {
						echo '&nbsp;';
					} ?>
				</li>
			</ul>
		</div>
		<div class="clearfix"></div>
		<img src="images/winniethepooh.png" id="winniethepooh" />
	</div>
	<div id="shadow"></div>
	</div>
</body>
</html>
