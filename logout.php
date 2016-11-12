<?php
// configuration
require("includes/config.php"); 

// log out current user, if any
Session::logout();

// redirect user
Redirect::to("/pubtrain");

