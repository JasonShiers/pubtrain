<?php

// configuration
require("includes/config.php"); 

$DB = DB::getInstance();

// get user's conference history
$query = "(SELECT c.id, c.confdate AS date, "
        . "DATE_FORMAT(c.confdate, '%b %Y') AS confdate, c.title, "
        . "c.location, c.days, r.attended, r.id AS req_id, 0 AS editable, "
        . "1 AS confirmed, 1 AS verified "
        . "FROM conferences c, requests r WHERE r.userid=? AND r.conf_id=c.id "
        . "AND c.confdate <= ?) "
        . "UNION"
        . "(SELECT id AS id, date, DATE_FORMAT(date, '%b %Y') AS confdate, "
        . "title, location, days, 0 AS attended, 0 AS req_id, 1 AS editable, "
        . "confirmed, verified "
        . "FROM suppconfrecords WHERE userid=?) "
        . "ORDER BY date DESC";
$confhistory = $DB->assocQuery($query, $_SESSION["userid"], 
        date("Y-m-d"), $_SESSION["userid"])->results();

if ($DB->error())
{
    Redirect::error("Cannot retreive conference history");
}

// get users list for multi select
$users = $DB->assocQuery("SELECT userid, firstname, lastname FROM users "
        . "ORDER BY lastname ASC, firstname ASC")->results();

if ($DB->error())
{
    Redirect::error("Cannot retreive user list");
}


// get user's training history
$query = "SELECT tr.recordid AS id, "
        . "DATE_FORMAT(tr.date, '%b %Y') AS date, tl.type, tl.catmask, "
        . "tr.description, tr.internal_location, tr.internal_trainer, "
        . "tr.total_days, tr.confirmed, tr.verified "
        . "FROM trainingrecords tr, traininglibrary tl "
        . "WHERE tr.userid = ? AND tr.trainingid = tl.trainingid "
        . "ORDER BY tr.date DESC";
$trainhistory = $DB->assocQuery($query, Session::get("userid"))
        ->results();

if ($DB->error())
{
    Redirect::error("Cannot retreive training history");
}

// get training types for multi select
$traintypes = $DB
        ->assocQuery("SELECT trainingid, type FROM traininglibrary")
        ->results();

if ($DB->error())
{
    Redirect::error("Cannot retreive training types");
}

// get user's publication history
$query = "SELECT * FROM publicationrecords WHERE userid = ? "
        . "ORDER BY year DESC, journal, title";
$pubhistory = $DB->assocQuery($query, Session::get("userid"))->results();

if ($DB->error())
{
    Redirect::error("Cannot retreive publication history");
}

// render table
render("templates/dashboard.php", ["pubhistory" => $pubhistory, 
    "users" => $users, "trainhistory" => $trainhistory, 
    "traintypes" => $traintypes, "confhistory" => $confhistory, 
    "title" => "Dashboard"]);