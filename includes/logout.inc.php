<?
	if(array_key_exists('logout', $_POST)) {
		//empty the $_SESSION array
		$_SESSION = array();
		//invalidate the session cookie
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-86400, '/');
		}
		//end session and redirect
		session_destroy();
		header('Location: ');
		exit;
	}
?>
<form id="logoutForm" name="logoutForm" method="post" action="">
	<input name="logout" type="submit" id="logout" value="Log out" />
</form>