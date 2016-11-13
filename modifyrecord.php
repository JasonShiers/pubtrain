<?php

// configuration
require("includes/config.php");

$type = Input::get("type");
$month = Input::get("month");
$year = Input::get("year");

if ($month !== "" && $year !== "") {
    $date = date("Y-m-d", strtotime($year . "-" . $month . "-" . "01"));
}
else
{
    $date = "";
}

$admin = 0;
if(Session::exists("admin")) $admin = Session::get("admin");
$userid = Session::get("userid");
$id = Input::get("id");
$page = Input::get("page");

$title = Input::get("title");
$location = Input::get("location");
$days = Input::get("days");
$otherusers = Input::get("otherusers");
$internal_trainer = Input::get("internal_trainer");
$internal_location = Input::get("internal_location");
$description = Input::get("description");
$superuser = Input::get("superuser");
$verifyrecords = Input::get("verifyrecords");

$journal = Input::get("journal");
$volume = Input::get("volume");
$issue = Input::get("issue");
$startpage = Input::get("startpage");
$endpage = Input::get("endpage");
$source = Input::get("source", NULL);
$thirdParty = Input::get("3rdParty");
$deleteusers = Input::get("deleteusers");

if ($type !== "") {
    $successcode = 0;
    $DB = DB::getInstance();
    
    if ($type == "newConf") {
        if ($date !== "" && $title !== "") {
            $query = "INSERT INTO suppconfrecords (userid, date, title, "
                    . "location, days, confirmed) VALUES (?, ?, ?, ?, ?, NULL)";
            $DB->assocQuery($query, $userid, $date, $title, $location, $days);
            
            // Failed to insert record into database for self
            if ($DB->error())
            {
                $successcode |= 1;
            } else {
                if (is_array($otherusers) && count($otherusers) > 0) {
                    foreach ($otherusers as $otheruser) {
                        $query = "INSERT INTO suppconfrecords (userid, date, 
                            title, location, days, confirmed) 
                            VALUES (?, ?, ?, ?, ?, 0) 
                            ON DUPLICATE KEY UPDATE id=id";
                        $DB->assocQuery($query, $otheruser, $date, $title, 
                                $location, $days);

                        // Failed to insert record into database for one or more other users
                        if ($DB->error()) {
                            $successcode |= 2; 
                        }
                    }
                }
                
                $url = "index.php?success=" . $successcode;
                if (intval($page) > 1) {
                    $url .= "&page=" . $page;
                }
                Redirect::to($url);
            }
        }
    } else if ($type == "newTrain") {
        $successcode = 0;
        if ($date !== "" && intval($id) > 0) {
            if ($internal_trainer == 2) {
                $internal_trainer = NULL;
            }

            if ($superuser == 1 && is_array($otherusers) && count($otherusers) > 0) {
                // Insert verified records from a superuser or admin
                $DB->assocQuery("SELECT 1 FROM trainingsuperusers "
                        . "WHERE trainingid=? AND userid=?", $id, 
                        $userid)->results();
                
                if ($DB->count() > 0 || ($admin == 1)) {
                    $query = "INSERT INTO trainingrecords (userid, date, 
                        trainingid, description, total_days, 
                        internal_location, internal_trainer, confirmed, 
                        verified) VALUES (?, ?, ?, ?, ?, ?, ?, 0, 1) 
                        ON DUPLICATE KEY UPDATE verified=1";
                    foreach ($otherusers as $otheruser) {
                        $DB->assocQuery($query, $otheruser, $date, $id, 
                                $description, $days, $internal_location, 
                                $internal_trainer);
                        // Failed to insert record into database for one or more other users
                        if ($DB->error()) {
                            $successcode |= 2;
                        }
                    }
                // You do not have permission to do this
                } else {
                    $successcode |= 8;
                }
                
                $startdate = Input::get("startdate");
                $enddate = Input::get("enddate");
                $depmask = Input::get("depmask");
                
                $url = "trainingsummary.php?success=" . $successcode;
                if ($admin == 1)
                    $url .= "&admin=1";
                if (intval($id) > 0)
                    $url .= "&trainingid=" . $id;
                if ($startdate !== "")
                    $url .= "&startdate=" . $startdate;
                if ($enddate !== "")
                    $url .= "&enddate=" . $enddate;
                if ($depmask !== "")
                    $url .= "&depmask=" . $depmask;
                Redirect::to($url);
            }
            else {
                // Insert record for a user and others they have specified
                $query = "INSERT INTO trainingrecords (userid, date, trainingid, 
                    description, total_days, internal_location, 
                    internal_trainer, confirmed) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NULL)";
                $DB->assocQuery($query, $userid, $date, $id, 
                        $description, $days, $internal_location, 
                        $internal_trainer);

                // Failed to insert record into database for self
                if ($DB->error()) {
                    $successcode |= 1;
                } else {
                    if (count($otherusers) > 0) {
                        $query = "INSERT INTO trainingrecords (userid, date, "
                                . "trainingid, description, total_days, "
                                . "internal_location, internal_trainer, confirmed) "
                                . "VALUES (?, ?, ?, ?, ?, ?, ?, 0) "
                                . "ON DUPLICATE KEY UPDATE recordid=recordid";
                        foreach ($otherusers as $otheruser) {
                            $DB->assocQuery($query, $otheruser, $date, 
                                    $id, $description, $days, 
                                    $internal_location, $internal_trainer);

                            // Failed to insert record into database for one or more other users
                            if ($DB->error()) {
                                $successcode |= 2;
                            }
                        }
                    }
                }
            }
            $url = "index.php?success=" . $successcode;
            if (intval($page) > 1) {
                $url .= "&page=" . $page;
            }
            Redirect::to($url . "#collapseTrainingHistory");
        }
    } else if ($type == "newPub") {
        $successcode = 0;
        if ($year !== "" && $title !== "" && $journal !== "") {
            if ($thirdParty == "") {
                $query = "INSERT INTO publicationrecords (userid, journal, "
                        . "title, year, volume, issue, startpage, endpage, "
                        . "source, confirmed) "
                        . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL) "
                        . "ON DUPLICATE KEY UPDATE id=id";
                $DB->assocQuery($query, $userid, $journal, $title, $year, 
                        $volume, $issue, $startpage, $endpage, $source);

                // Failed to insert record into database for self
                if ($DB->error()) {
                    $successcode |= 1;
                }
            }

            if (count($otherusers) > 0) {
                $query = "INSERT INTO publicationrecords (userid, journal, "
                        . "title, year, volume, issue, startpage, endpage, "
                        . "source, confirmed) "
                        . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0) "
                        . "ON DUPLICATE KEY UPDATE id=id";
                foreach ($otherusers as $otheruser) {
                    $DB->assocQuery($query, $otheruser, $journal, $title, $year, 
                            $volume, $issue, $startpage, $endpage, $source);

                    // Failed to insert record into database for one or more other users
                    if ($DB->error()) {
                        $successcode |= 2;
                    }
                }
            }

            if ($thirdParty !== "") {
                $url = "publicationsummary.php?success=" . $successcode;
            } else {
                $url = "index.php?success=" . $successcode;
                if (intval($page) > 1) {
                    $url .= "&page=" . $page;
                }
                $url .= "#collapsePublicationHistory";
            }
            Redirect::to($url);
        }
    } else if ($type == "editPub") {
        $successcode = 0;
        if ($year !== "" && $title !== "" && $journal !== "") {
            if (count($otherusers) > 0) {
                $query = "INSERT INTO publicationrecords (userid, journal, "
                        . "title, year, volume, issue, startpage, endpage, "
                        . "source, confirmed) "
                        . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0) "
                        . "ON DUPLICATE KEY UPDATE id=id";
                foreach ($otherusers as $user) {
                    $DB->assocQuery($query, $user, $journal, $title, $year, 
                            $volume, $issue, $startpage, $endpage, $source);

                    // Failed to insert record into database for one or more other users
                    if ($DB->error()) {
                        $successcode |= 2;
                    }
                }
            }

            if (is_array($deleteusers) && count($deleteusers) > 0) {
                foreach ($deleteusers as $user) {
                    $DB->assocQuery("DELETE FROM publicationrecords WHERE id = ?",
                            $user);

                    if ($DB->error()) {
                        $successcode |= 4; // Failed to delete record
                    }
                }
            }

            Redirect::to("publicationsummary.php?success=" . $successcode);
        }
    } else if ($type == "verifyTrain") {
        $successcode = 0;
        if (is_array($verifyrecords) && count($verifyrecords) > 0 && $superuser == 1) {
            // Insert verified records from a superuser or admin
            $DB->assocQuery("SELECT 1 FROM trainingsuperusers "
                    . "WHERE trainingid=? AND userid=?", $id, $userid);
            if ($DB->count() > 0 || ($admin == 1)) {
                
                $DB->assocListQuery("UPDATE trainingrecords SET verified = 1 "
                        . "WHERE recordid IN(", $verifyrecords, ")");
                
                // Failed to verify one or more records
                if ($DB->error()) {
                    $successcode |= 16;
                }
            // You do not have permission to do this
            } else {
                $successcode |= 8;
            }
        }
        $url = "trainingsummary.php?success=" . $successcode;
        if ($admin == 1)
            $url .= "&admin=1";
        Redirect::to($url);
    }
    else if ($type == "delConf") {
        $successcode = 0;
        if (intval($id) > 0) {
            $DB->assocQuery("DELETE FROM suppconfrecords WHERE id=? "
                    . "AND userid=?", $id, Session::get("userid"));
        }
        
        // Failed to delete record
        if ($DB->error()) {
            $successcode |= 4;
        }
        
        $url = "index.php?success=" . $successcode;
        if (intval($page) > 1) {
            $url .= "&page=" . $page;
        }
        Redirect::to($url);
    } 
    else if ($type == "delTrain") {
        $successcode = 0;
        if (intval($id) > 0) {
            $DB->assocQuery("DELETE FROM trainingrecords WHERE recordid=? "
                    . "AND userid=?", $id, Session::get("userid"));
        }
        // Failed to delete record
        if ($DB->error()) {
            $successcode |= 4;
        }
        
        $url = "index.php?success=" . $successcode;
        if (intval($page) > 1) {
            $url .= "&page=" . $page;
        }
        Redirect::to($url . "#collapseTrainingHistory");
    } 
    else if ($type == "delPub") {
        $successcode = 0;
        if (intval($id) > 0) {
            $DB->assocQuery("DELETE FROM publicationrecords WHERE id=? "
                    . "AND userid=?", $id, Session::get("userid"));
        }
        // Failed to delete record
        if ($DB->error()) {
            $successcode |= 4;
        }
        
        $url = "index.php?success=" . $successcode;
        if (intval($page) > 1) {
            $url .= "&page=" . $page;
        }
        Redirect::to($url . "#collapsePublicationHistory");
    } 
    else if ($type == "confirmConf") {
        $successcode = 0;
        if (intval($id) > 0) {
            $DB->assocQuery("UPDATE suppconfrecords SET confirmed=1 WHERE id=? "
                    . "AND userid=?", $id, Session::get("userid"));
        }
        // Failed to confirm this record
        if ($DB->error()) {
            $successcode |= 32;
        }

        $url = "index.php?success=" . $successcode;
        if (intval($page) > 1) {
            $url .= "&page=" . $page;
        }
        Redirect::to($url);
    } 
    else if ($type == "confirmTrain") {
        $successcode = 0;
        if (intval($id) > 0) {
            $DB->assocQuery("UPDATE trainingrecords SET confirmed=1 "
                    . "WHERE recordid=? AND userid=?", $id, Session::get("userid"));
        }
        // Failed to confirm this record
        if ($DB->error()) {
            $successcode |= 32;
        }
        
        $url = "index.php?success=" . $successcode;
        if (intval($page) > 1) {
            $url .= "&page=" . $page;
        }
        Redirect::to($url . "#collapseTrainingHistory");
    } else if ($type == "confirmPub") {
        $successcode = 0;
        if (intval($id) > 0) {
            $DB->assocQuery("UPDATE publicationrecords SET confirmed=1 "
                    . "WHERE id=? AND userid=?", $id, Session::get("userid"));
        }
        // Failed to confirm this record
        if ($DB->error()) {
            $successcode |= 32;
        }
        $url = "index.php?success=" . $successcode;
        if (intval($page > 1)) {
            $url .= "&page=" . $_GET["page"];
        }
        Redirect::to($url . "#collapsePublicationHistory");
    }
}