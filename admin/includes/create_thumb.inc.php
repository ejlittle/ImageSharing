<?
	//define constants for resizing script
	define('THUMBS_DIR', '');
	define('DOWNLOAD_DIR', '');
	define('MAX_WIDTH', 800);
	define('MAX_HEIGHT', 500);
	define('THB_WIDTH', 280);
	define('THB_HEIGHT', 280);
	
	//process the uploaded image
	if (is_uploaded_file($_FILES['image']['tmp_name'][$number])) {
		$original = $_FILES['image']['tmp_name'][$number];
		//begin by getting the details of the original
		list($width, $height, $type) = getimagesize($original);
		//calculate the thumbnail scaling ratio
		if($width <= THB_WIDTH && $height <= THB_HEIGHT) {
			$thb_ratio = 1;
		}
		elseif ($width > $height) {
			$thb_ratio = THB_WIDTH/$width;
		}
		else {
			$thb_ratio = THB_HEIGHT/$height;
		}
		//calculate the image scaling ratio
		if($width <= MAX_WIDTH && $height <= MAX_HEIGHT) {
			$max_ratio = 1;
		}
		elseif ($width > $height) {
			$max_ratio = MAX_WIDTH/$width;
		}
		else {
			$max_ratio = MAX_HEIGHT/$height;
		}
		//strip the extension off the image filename
		$imagetypes = array('/\.gif$/', '/\.jpg$/', '/\.jpeg$/', '/\.png$/');
		$name = preg_replace($imagetypes, '', basename($file));
		
		//create an image resource for the original
		switch($type) {
			case 1:
				$source = @ imagecreatefromgif($original);
				if (!$source) {
					$result[] = 'Cannot process GIF files. Please use JPEG or PNG.';
				}
				break;
			case 2:
				$source = imagecreatefromjpeg($original);
				break;
			case 3:
				$source = imagecreatefrompng($original);
				break;
			default:
				$source = NULL;
				$result[] = 'Cannot identify file type.';
		}
		//make sure the image resource is OK
		if (!$source) {
			$result[] = 'Problem copying original';
		}
		else {
			//calculate the dimensions of the thumbnail
			$thumb_width = round($width * $thb_ratio);
			$thumb_height = round($height * $thb_ratio);
			$maximum_width = round($width * $max_ratio);
			$maximum_height = round($height * $max_ratio);
			//create the image resource for the thumbnail
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			$maximage = imagecreatetruecolor($maximum_width, $maximum_height);
			//create the resized copy
			imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
			imagecopyresampled($maximage, $source, 0, 0, 0, 0, $maximum_width, $maximum_height, $width, $height);

			if (!file_exists(UPLOAD_DIR.$file)) {
				$newfile = $file;
			}
			else {
				//get the date and time
				ini_set('date.timezone', 'America/New_York');
				$now = date('Y-m-d-His');
				$newfile = $now.$file;
			}
			
			//save the resized copy
			switch($type) {
				case 1:
					if (function_exists('imagegif')) {
						if (!file_exists(THUMBS_DIR.$name.'_thb.gif')) {
							$newname = $name;
						}
						else {
							$newname = $now.$name;
						}
						$success = imagegif($thumb, THUMBS_DIR.$newname.'_thb.gif');
						$maxsuccess = imagegif($maximage, UPLOAD_DIR.$newfile);
						$thumb_name = $name.'_thb.gif';
					}
					else {
						if (!file_exists(THUMBS_DIR.$name.'_thb.jpg')) {
							$newname = $name;
						}
						else {
							$newname = $now.$name;
						}
						$success = imagejpeg($thumb, THUMBS_DIR.$newname.'_thb.jpg',50);
						$maxsuccess = imagejpeg($maximage, UPLOAD_DIR.$newfile,50);
						$thumb_name = $name.'_thb.jpg';
					}
					break;
				case 2:
					if (!file_exists(THUMBS_DIR.$name.'_thb.jpg')) {
						$newname = $name;
					}
					else {
						$newname = $now.$name;
					}
					$success = imagejpeg($thumb, THUMBS_DIR.$newname.'_thb.jpg',100);
					$maxsuccess = imagejpeg($maximage, UPLOAD_DIR.$newfile,100);
					$thumb_name = $name.'_thb.jpg';
					break;
				case 3:
					if (!file_exists(THUMBS_DIR.$name.'_thb.png')) {
						$newname = $name;
					}
					else {
						$newname = $now.$name;
					}
					$success = imagepng($thumb, THUMBS_DIR.$newname.'_thb.png');
					$maxsuccess = imagepng($maximage, UPLOAD_DIR.$newfile);
					$thumb_name = $name.'_thb.png';
			}
		
			//move the temporary file to the download folder
			$moved = @ move_uploaded_file($original, DOWNLOAD_DIR.$newfile);
			if ($success) {
				$flagone = 'Success';
			}
			else {
				$flagone = 'Success Failed';
			}
			
			if ($moved) {
				$flagtwo = 'Moved';
			}
			else {
				$flagtwo = 'Moved Failed';
			}
			
			if ($maxsuccess) {
				$flagthree = 'Maxsuccess';
			}
			else {
				$flagthree = 'Maxsuccess Failed';
			}
			
			if ($success && $moved && $maxsuccess) {
				$result[] = $file.' successfully uploaded';
				$result[] .= $thumb_name.' created.';
				//Store upload information in database
				$sql = 'INSERT INTO images(filename, thumb, caption) VALUES(:filename, :thumb, :caption)';
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':filename', $newfile, PDO::PARAM_STR);
				$stmt->bindParam(':thumb', $thumb_name, PDO::PARAM_STR);
				$stmt->bindParam(':caption', $_POST['caption'.$caption_count], PDO::PARAM_STR);
				$OK = $stmt->execute();
				if(!$OK) {
					$error = $stmt->errorInfo();
					echo $error[2];
				}
			}
			else {
				$result[] = 'Problem uploading '.$file.'. '.$flagone.' '.$flagtwo.' '.$flagthree;
				$result[] .= 'Problem creating thumbnail';
			}
			//remote the image resources from memory
			imagedestroy($source);
			imagedestroy($thumb);
			imagedestroy($maximage);
		}
	}
?>