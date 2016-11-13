<?php

// configuration
require("includes/config.php"); 

// if user reached page via GET (as by clicking a link or via redirect)
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    // render form
    if (Input::get("failed") == 1)
    {
        $failed = 1;
    }
    else
    {
        $failed = 0;
    }
    render("templates/login_form.php", ["failed" => $failed, 
        "title" => "Log In"]);
}

// else if user reached page via POST (as by submitting a form via POST)
else if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $validate = new Validation();
    $validate->check($_POST, array(
        'username' => array(
            'required' => true
        ),
        'password' => array(
            'required' => true
        )
    ));

    if(!$validate->passed()){
        Redirect::error($validate->errors(), "logout.php");
    }

    $login = new Login();
    
    if (DEBUGMODE !== "on")
    {
        $login->ldapConnect();
        $login->ldapBind(Input::get('username'), Input::get('password'));
        
        if($login->error())
        {
            $login->ldapClose();
            Redirect::to("login.php?failed=1");
        }
        
        $login->ldapLoadEntry(Input::get('username'));
        $login->ldapClose();
		
        if($login->error())
        {
            Redirect::error("Unable to load user information from logon server", 
                    "logout.php");
        }
    }
    else
    {
        $login->dbDebugLoadEntry(Input::get('username'));
    }

    $login->dbLoadEntry(Input::get('username'));
    
    $nexturl = Input::get("next", FALSE);
    if ($nexturl !== FALSE)
    {
        Redirect::to($nexturl);
    }
    // redirect to main page
    Redirect::to("index.php");
}