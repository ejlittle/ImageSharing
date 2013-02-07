<?
	function dbConnect($type) {
		if ($type == 'query') {
			$user = '';
			$pwd = '';
		}
		elseif ($type == 'admin') {
			$user = '';
			$pwd = '';
		}
		else {
			exit('Unrecognized connection type');
		}
		try {
			$conn = new PDO('', $user, $pwd);
			return $conn;
		}
		catch (PDOException $e) {
			echo 'Cannot connect to database';
			exit;
		}
	}
?>