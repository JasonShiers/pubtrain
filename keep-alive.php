<?php
    session_start();
	if (!empty($_SESSION["timestamp"]))
	{
		$_SESSION['timestamp']=time();
	}
?>
