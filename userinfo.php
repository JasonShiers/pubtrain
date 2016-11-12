<?php
// configuration
require("includes/config.php"); 

$DB = DB::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    
    $userinfo = $DB->assocQuery("SELECT * FROM users WHERE userid=?", 
            Session::get('userid'))->results();

    if ($DB->error())
    {
        Redirect::error("Error getting your user information");
    }

    $linemgrs = $DB->assocQuery("SELECT userid, firstname, lastname FROM users "
            . "ORDER BY lastname ASC, firstname ASC")->results();

    if ($DB->error())
    {
        Redirect::error("Error getting list of line managers");
    }

    $departments = $DB->assocQuery("SELECT department FROM departments")->results();

    if ($DB->error())
    {
        Redirect::error("Error getting list of departments");
    }
    
    render("templates/modify_curr_user.php", ["departments" => $departments, "linemgrs" => $linemgrs, 
        "userinfo" => $userinfo, "title" => "Modify User Profile"]);
}
else if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (Token::check(Input::get('token')))
    {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
           'linemgr' => array(
               'required' => true
           ),
            'department' => array(
                'required' => true
            )
        ));

        if(!$validate->passed()){
            Redirect::error($validate->errors());
        }
    }
    else
    {
        Redirect::error("Unable to validate form token");
    }

    // Get linemgr and department info     
    $linemgr = $DB->
            assocQuery("SELECT userid FROM users WHERE userid=?", 
                    Input::get('linemgr'))->results();

    if ($DB->error())
    {
        Redirect::error("Error getting line manager details");
    }

    $dep = $DB->
            assocQuery("SELECT department, depmask FROM departments WHERE department = ?", 
                    Input::get('department'))->results();

    if ($DB->error())
    {
        Redirect::error("Error getting department details");
    }

    // update existing entry
    $success = $DB->assocQuery("UPDATE users SET linemgr=?, department=? WHERE userid=?", 
        $linemgr[0]["userid"], Input::get('department'), Input::get('userid'))->
            results();

    if ($success === false)
    {
        Redirect::error("Can't update database.");
    }
    else 
    {
        Session::put('linemgr', $linemgr[0]["userid"]);
        Session::put('department', $dep[0]["department"]);
        Session::put('depmask', $dep[0]["depmask"]);
        Redirect::to("index.php");
    }
}