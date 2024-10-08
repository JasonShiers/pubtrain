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

// Autoload classes when used
spl_autoload_register(function($class) {
    require_once("classes/" . $class . ".php");
});

// require authentication for all pages except /login.php, /logout.php, and /register.php
$php_self = filter_input(INPUT_SERVER, "PHP_SELF", FILTER_SANITIZE_SPECIAL_CHARS);

if (!in_array($php_self, ["/pubtrain/login.php", "/pubtrain/logout.php"]))
{
    if (empty(Session::get("timestamp")))
    {
                    // user is not logged in to a session
        Redirect::to("login.php?next=" . $php_self);
    }
    else if (time() - Session::get('timestamp') > 660)
    {
        // session has had no activity for > 11 minutes
        Redirect::to("logout.php");        	
    }
    else if (!in_array($php_self, ["/confdb/userinfo.php"]) 
            && (empty(Session::get("department")) 
            || empty(Session::get("linemgr"))))
    {
        // user has not set profile info
        Redirect::to("userinfo.php");
    }
    else
    {
        // else refresh session timestamp
        Session::put('timestamp', time());	
    }
}
