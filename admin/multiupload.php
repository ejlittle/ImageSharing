<?
	session_start();
	ob_start();
	include('includes/connection.inc.php');
	$conn = dbConnect('query');
	//if session variable not set, redirect to login page
	if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != '') {
		header('Location: ');
		exit;
	}
	//8M max limit imposed by hosting service
	define('MAX_FILE_SIZE', 2097152);
	
	if(array_key_exists('upload', $_POST)) {
		//include nukeMagicQuotes and clean the $_POST array
		include('includes/corefuncs.php');
		nukeMagicQuotes();
		//define constant for upload folder
		define('UPLOAD_DIR', '');
		//convert the maximum size to KB
		$max = number_format((MAX_FILE_SIZE/1024)/1024, 1).'MB';
		//create an array of permitted MIME types
		$permitted = array('image/gif', 'image/jpeg','image/pjpeg', 'image/png');
		
		$caption_count = 1;
		foreach ($_FILES['image']['name'] as $number => $file) {
			//replace any spaces in original filename with underscores
			//at the same time, assign to a simpler variable
			$file = str_replace(' ', '_', $file);
			//begin by assuming the file is unacceptable
			$sizeOK = false;
			$typeOK = false;
		
			//check that file is within the permitted size
			if ($_FILES['image']['size'][$number] > 0 && $_FILES['image']['size'][$number] <= MAX_FILE_SIZE) {
				$sizeOK = true;
			}
			//check that file is of a permitted MIME type
			foreach($permitted as $type) {
				if ($type == $_FILES['image']['type'][$number]) {
					$typeOK = true;
					break;
				}
			}
			if($sizeOK && $typeOK) {
				switch($_FILES['image']['error'][$number]) {
					case 0:
						include('includes/create_thumb.inc.php');
						break;
					case 3:
						$result = 'Error uploading '.$file.'. Please try again.';
					default:
						$result = 'System error uploading '.$file.'. Contact Webmaster';
				}
			}
			elseif($_FILES['image']['error'] == 4) {
				$result[] = 'No file selected';
			}
			else {
				$result[] = $file.' cannot be uploaded. Maximum size: '.$max.'. Acceptable file types: gif, jpg, png.';
			}
			$caption_count++;
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

	<title>Upload</title>
	<meta name="description" content="" />
	<meta name="author" content="Eric J. Little" />
	<meta name="robots" content="noindex, nofollow" />
	
	<link rel="stylesheet" href="../styles/styles.css" type="text/css" media="screen" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		var num = 2;
		$('#addupload').click(function(e){
			e.preventDefault();
			$('ul').append('<li><label for="image' + num + '">Upload Image: </label><input type="file" name="image[]" id="image' + num + '" /><input type="text" name="caption' + num + '" id="caption' + num + '" /></li>');
			num++;
		});
	});
	</script>
</head>

<body>
	<div id="sitewrapper">
	<div class="wrapper">
		<div id="header">
			<h1>Penny's Pictures<span> - Admin Control Panel</span></h1>
			<div id="logout">
				<? include('includes/logout.inc.php'); ?>
			</div>
			<div class="clearfix"></div>
		</div>
		<div id="banner">
			<h2><strong>Upload Photos</strong> - Upload multiple photos at once!</h2>
		</div>
		<form action="" method="post" enctype="multipart/form-data" name="uploadImage" id="uploadImage">
			<ul>
				<li>
					<input type="hidden" name="MAX_FILE_SIZE" value="<? echo MAX_FILE_SIZE; ?>" />
					<label for="image1">Upload Image: </label><input type="file" name="image[]" id="image1" />
					<input type="text" name="caption1" id="caption1" />
				</li>
			</ul>
			<p>
				<a href="#" id="addupload">Add Upload Field &raquo;</a>
			</p>
			<p>
				<input type="submit" name="upload" id="upload" value="Upload" />
			</p>
		</form>
		<?
			//if the form has been submitted, display result
			if(isset($result)) {
				echo '<ol>';
				foreach ($result as $item) {
					echo '<li><strong>'.$item.'</strong></li>';
				}
				echo '</ol>';
			}
		?>
		<div id="footer">
			<p>&nbsp;</p>
		</div>
		<img src="../images/owl.png" id="winniethepooh" />
	</div>
	<div id="shadow"></div>
	</div>
</body>
</html>
