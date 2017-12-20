<?php

// configuration
require("includes/config.php"); 

// if user reached page via GET (as by clicking a link or via redirect)
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    // render form, passing failed flag
    render("templates/login_form.php", ["failed" => Input::get("failed"), 
        "title" => "Log In"]);
}

// else if user reached page via POST (as by submitting a form via POST)
else if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (!Token::check(Input::get('token')))
    {
        Redirect::error("Unable to validate form token", "login.php");
    }
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

    $username = Input::get('username');
    
    /*
     * Use LDAP Authentication when not in debug mode
     */   
    if (DEBUGMODE === "off")
    {
        // Connect to LDAP server and authenticate
        $ldap = new Ldap();
        $ldap->connect();
        
        // Retrieve raw password to allow special characters
        $ldap->bind($username, $_POST["password"]);
        
        // Assume incorrect username/password on error
        if($ldap->error())
        {
            $ldap->close();
            Redirect::to("login.php?failed=1");
        }
        
        // Get user information and disconnect from LDAP server
        $userinfo = $ldap->getUser($username);
        $ldap->close();
		
        // Load information into session or redirect on error
        if(isset($userinfo)){
            Session::loadUser($userinfo);
        }
        else
        {
            Redirect::error("Unable to load user information from logon server", 
                    "logout.php");
        }
    }
    
    /*
     * Get user information from database
     */
    $DB = DB::getInstance();
    
    $results = $DB->assocQuery("SELECT * FROM users WHERE userid = ?", 
                $username)->results();
    if ($DB->error())
    {
        // if there was an error, redirect
        Redirect::error("Cannot access user in database", "login.php");
    }
    elseif ($DB->count() == 1)
    {
        // If a single user was found, load information into session
        Session::loadUser($results[0]);

        // Calculate department mask
        $depmask = $DB->getDepMask(Session::get("department"));
        Session::put("depmask", $depmask);
    
        // Login complete, redirect to next url if specified
        $nexturl = Input::get("next");
        if (isset($nexturl))
        {
            Redirect::to($nexturl);
        }
            // If not, redirect to main page
        Redirect::to("index.php");
    }
    elseif ($DB->count() != 0)
    {
        // For any other non-zero number of results, redirect
        Redirect::error("Cannot find user information in database", "login.php");
    }
    else if (DEBUGMODE === "off")
    {
        // If not in debug mode and no results found in DB, add new user to DB
        $DB->assocQuery("INSERT INTO users (userid, firstname, "
                . "lastname, email) VALUES (?, ?, ?, ?)", 
                Session::get("userid"), Session::get("forename"), 
                Session::get("surname"), Session::get("mail"));

        if ($DB->error())
        {
            Session::logout();
            Redirect::error("Cannot insert new user into database", "login.php");
        }

        // Redirect to userinfo to confirm any missing info
        Redirect::to("userinfo.php");

    }
    else
    {
        // Incorrect username entered while in debug mode
        Redirect::to("login.php?failed=1");
    }   
}