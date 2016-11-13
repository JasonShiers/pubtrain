<?php

// configuration
require("includes/config.php");

$DB = DB::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (Input::get("admin") == 1) {
        $linegroup = $DB->assocQuery("SELECT userid, firstname, lastname "
                . "FROM users WHERE department=? "
                . "ORDER BY lastname ASC, firstname ASC", 
                Session::get("department"))->results();
        
        if ($DB->error()){
            Redirect::error("Unable to retrieve department users", 
                    "linereportsummary.php?admin=1");
        }
        
    } else {
        $linegroup = getLineGroupUser(Session::get("userid"));
    }
    // render table
    render("templates/usersummary.php", ["linegroup" => $linegroup, 
        "title" => "User Summary"]);
    
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $admin = Input::get("admin");
    
    if (!Token::check(Input::get('token')))
    {
        Redirect::error("Unable to validate form token", 
                "linereportsummary.php?admin=" . $admin);
    }
    $validate = new Validation();
    $validation = $validate->check($_POST, array(
       'userid' => array(
           'required' => true           
           )
        ));

    if(!$validate->passed()){
        Redirect::error($validate->errors(), 
                "linereportsummary.php?admin=" . $admin);
    }
    
    $userid = Input::get("userid");
    
    if (Session::get("admin") == 0 || $admin == 0) {
        $linegroup = getLineGroupUser(Session::get("userid"));
        if (array_search($userid, array_column($linegroup, "userid")) === FALSE) 
        {
            Redirect::error("This user is not in your line group", 
                    "linereportsummary.php");
        }
    } else {
        $linegroup = $DB->assocQuery("SELECT userid, firstname, lastname "
                . "FROM users WHERE department=? "
                . "ORDER BY lastname ASC, firstname ASC", 
                Session::get("department"))->results();
        
        if ($DB->error()){
            Redirect::error("Unable to retrieve department users", 
                    "linereportsummary.php?admin=" . $admin);
        }
    }

    // get user's conference history
    $query = "(SELECT c.id, c.confdate AS date, "
            . "DATE_FORMAT(c.confdate, '%b %Y') AS confdate, c.title, c.location, "
            . "c.days, r.attended, r.id AS req_id, 0 AS editable, 1 AS confirmed, "
            . "1 AS verified "
            . "FROM conferences c, requests r "
            . "WHERE r.userid=? AND r.conf_id=c.id AND c.confdate <= ?) "
            . "UNION "
            . "(SELECT id AS id, date, DATE_FORMAT(date, '%b %Y') AS confdate, "
            . "title, location, days, 0 AS attended, 0 AS req_id, 1 AS editable, "
            . "confirmed, verified FROM suppconfrecords WHERE userid=?) "
            . "ORDER BY date DESC";
    
    $confhistory = $DB->assocQuery($query, $userid, date("Y-m-d"), $userid)
            ->results();
    
    if ($DB->error())
    {
        Redirect::error("Cannot retrieve user's conference history", 
                "linereportsummary.php?admin=" . $admin);
    }

    // get user's training history
    $query = "SELECT tr.recordid AS id, DATE_FORMAT(tr.date, '%b %Y') AS date, "
            . "tl.type, tl.catmask, tr.description, tr.internal_location, "
            . "tr.internal_trainer, tr.total_days, tr.confirmed, tr.verified "
            . "FROM trainingrecords tr, traininglibrary tl WHERE tr.userid = ? "
            . "AND tr.trainingid = tl.trainingid "
            . "ORDER BY tr.date DESC";
    
    $trainhistory = $DB->assocQuery($query, $userid)->results();
    
    if ($DB->error())
    {
        Redirect::error("Cannot retrieve user's training history", 
                "linereportsummary.php?admin=" . $admin);
    }

    // get user's publication history
    $query = "SELECT * FROM publicationrecords WHERE userid = ? "
            . "ORDER BY year DESC, journal, title";
    $pubhistory = $DB->assocQuery($query, $userid)->results();
    
    if ($DB->error())
    {
        Redirect::error("Cannot retrieve user's publication history", 
                "linereportsummary.php?admin=" . $admin);
    }

    // render table
    render("templates/usersummary.php", ["linegroup" => $linegroup, 
        "pubhistory" => $pubhistory, "trainhistory" => $trainhistory,
        "confhistory" => $confhistory, "userid" => $userid, "admin" => $admin, 
        "title" => "User Summary"]);
}
?>