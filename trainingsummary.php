<?php

// configuration
require("includes/config.php");

$DB = DB::getInstance();

$id = Input::get("id");
$admin = Input::get("admin", "0");

if ($_SERVER["REQUEST_METHOD"] == "GET" && $id == "") {
    if ($admin == 1) {
        $traintypes = $DB->assocQuery("SELECT trainingid, type "
                . "FROM traininglibrary ORDER BY type ASC")->results();
    } else {
        $traintypes = $DB->assocQuery("SELECT l.trainingid, l.type "
                . "FROM traininglibrary l, trainingsuperusers s "
                . "WHERE s.userid = ? AND l.trainingid = s.trainingid "
                . "ORDER BY type ASC", Session::get("userid"))->results();
    }
    
    if ($DB->error()){
        Redirect::error("Cannot load training types");
    }

    $depts = $DB->assocQuery("SELECT department, depmask from departments")
            ->results();

    if ($DB->error()){
        Redirect::error("Cannot load department list");
    }
    
    // render table
    render("templates/trainingsummary.php", ["traintypes" => $traintypes, 
        "depts" => $depts, "title" => "Training Summary"]);
} else {
    // Required form field missing
    if ($id == "") {
        Redirect::to("trainingsummary.php?success=64&admin=" . $admin);
    }

    // Check user is an administrator or superuser
    if (Session::get("admin") == 0 || $admin == 0) {
        $traintypes = $DB->assocQuery("SELECT l.trainingid, l.type "
                . "FROM traininglibrary l, trainingsuperusers s "
                . "WHERE s.userid = ? AND l.trainingid = s.trainingid "
                . "AND l.trainingid = ? ORDER BY type ASC", 
                Session::get("userid"), $id)->results();

        // You do not have permission to do this
        if ($DB->count() == 0) {
            redirect("trainingsummary.php?success=8&admin=" . $admin);
        }
    } else {
        $traintypes = $DB->assocQuery("SELECT trainingid, type "
                . "FROM traininglibrary ORDER BY type ASC")->results();
    }

    $depmask = intval(Input::get("depmask", 0));
    $departments = Input::get("departments");
    if (is_array($departments)) {
        foreach ($departments AS $department) {
            $depmask |= $department;
        }
    }
    
    $startdate = Input::get("startdate");
    $enddate = Input::get("enddate");
    $description = Input::get("description");

    // get verified users
    $verified = $DB->assocQuery("SELECT GROUP_CONCAT(t.recordid) AS recordid, "
            . "u.firstname, u.lastname, u.userid, COUNT(u.userid) AS count "
            . "FROM users u, trainingrecords t, departments d "
            . "WHERE u.userid = t.userid AND t.verified = 1 AND t.trainingid = ? "
            . "AND t.description LIKE ?	AND t.date > ? AND t.date < ? "
            . "AND u.department = d.department AND d.depmask & ? "
            . "GROUP BY u.userid ORDER BY u.lastname, u.firstname", 
            $id, "%" . $description . "%", $startdate, $enddate, $depmask)
            ->results();
    
    if ($DB->error()){
        Redirect::error("Cannot get verified user list");
    }

    // get users with record
    $unverified = $DB->assocQuery("SELECT GROUP_CONCAT(t.recordid) AS recordid, "
            . "u.firstname, u.lastname, u.userid, COUNT(u.userid) AS count, "
            . "GROUP_CONCAT(IF(confirmed IS NULL || confirmed=1, 1, 0)) AS confirmed "
            . "FROM users u, trainingrecords t, departments d "
            . "WHERE u.userid = t.userid AND t.verified = 0 AND t.trainingid = ? "
            . "AND t.description LIKE ? AND t.date > ? AND t.date < ? "
            . "AND u.department = d.department AND d.depmask & ? "
            . "GROUP BY u.userid ORDER BY u.lastname, u.firstname", 
            $id, "%" . $description . "%", $startdate, $enddate, $depmask)
            ->results();

    if ($DB->error()){
        Redirect::error("Cannot get users with training record");
    }
    
    // get unverified, unconfirmed users
    $unconfirmed = $DB->assocQuery("SELECT u.firstname, u.lastname, u.userid "
            . "FROM users u, departments d "
            . "WHERE u.department = d.department AND d.depmask & ? "
            . "AND NOT EXISTS "
                . "(SELECT 1 FROM trainingrecords t "
                . "WHERE u.userid = t.userid AND t.trainingid = ? "
                . "AND t.description LIKE ? AND t.date > ? AND t.date < ?) "
            . "ORDER BY u.lastname, u.firstname", 
            $depmask, $id, "%" . $description . "%", $startdate, $enddate)
            ->results();
    
    if ($DB->error()){
        Redirect::error("Cannot get users with training record");
    }

    $depts = $DB->assocQuery("SELECT department, depmask from departments")
            ->results();

    if ($DB->error()){
        Redirect::error("Cannot get department list");
    }
    
    if (!is_array($departments)) {
        $departments = [];
        foreach ($depts as $dept) {
            if (($dept["depmask"] & $depmask) !== 0) {
                array_push($departments, $dept["depmask"]);
            }
        }
    }

    // render table
    render("templates/trainingsummary.php", ["traintypes" => $traintypes,
        "verified" => $verified, "unverified" => $unverified, 
        "unconfirmed" => $unconfirmed, "trainingid" => $id, 
        "startdate" => $startdate, "enddate" => $enddate, 
        "description" => $description, "depts" => $depts, 
        "departments" => $departments, "depmask" => $depmask,
        "admin" => $admin, "title" => "Training Summary"]);
}