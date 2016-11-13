<?php

// Autoload classes when used
spl_autoload_register(function($class) {
    require_once("classes/" . $class . ".php");
});

session_start();
Session::put('timestamp', time());