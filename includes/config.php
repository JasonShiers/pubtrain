<?php

    /**
     * config.php
     *
     * Configures pages.
     */

    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // requirements
    require("constants.php");
    require("functions.php");

    // enable sessions
    session_start();
	
	// set timezone
	date_default_timezone_set ("Europe/London");
    
    // set the timezone
    date_default_timezone_set ("Europe/London");

    // require authentication for all pages except /login.php, /logout.php, and /register.php
    if (!in_array($_SERVER["PHP_SELF"], ["/pubtrain/login.php", "/pubtrain/logout.php"]))
    {
        if (empty($_SESSION["timestamp"]))
        {
			// user is not logged in to a session
            redirect("login.php");
        }
        else if (time() - $_SESSION['timestamp'] > 660)
        {
			// session has had no activity for > 11 minutes
			logout();
			redirect("login.php");        	
        }
        else if (!in_array($_SERVER["PHP_SELF"], ["/pubtrain/userinfo.php"]) && (empty($_SESSION["department"]) || empty($_SESSION["linemgr"])))
        {
			// user has not set profile info
            redirect("userinfo.php");
        }
        else
        {
        	// else refresh session timestamp
			$_SESSION['timestamp']=time();	
        }
	}
?>
