<?php

// configuration
require("includes/config.php"); 

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $args = ["title" => "Publication Summary"];
    if (isset($_GET["success"]))
    {
        $args["success"] = TRUE;
    }

    // render table
    render("templates/publicationsummary.php", $args);
}
else if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (!Token::check(Input::get('token')))
    {
        Redirect::error("Unable to validate form token");
    }
    $validate = new Validation();
    $validation = $validate->check($_POST, array(
       'startyear' => array(
           'required' => true,
           'minval' => 1900,
           'maxval' => date("Y")
           ),
        'endyear' => array(
            'required' => true,
            'minval' => 1900,
            'maxval' => date("Y")
            )
        ));
    
    if(!$validate->passed()){
        Redirect::error($validate->errors());
    }
 
    // validate checkboxes
    $checkbox = [
        "patents" => Input::get("patents") !== "",
        "journals" => Input::get("journals") !== "",
        "clients" => Input::get("clients") !== "",
        "internal" => Input::get("internal") !== "",
        "external" => Input::get("external") !== ""
        ];
    
    if (!($checkbox["patents"] || $checkbox["journals"]))
    {
        Redirect::error("Error: Must choose at least one publication type");
    }
    
    if (!($checkbox["clients"] || $checkbox["internal"] || $checkbox["external"]))
    {
        Redirect::error("Error: Must choose at least one publication source");
    }

    $query = ["SELECT p.title, p.year, p.volume, p.issue, p.startpage, "
        . "p.endpage, GROUP_CONCAT(DISTINCT p.source) AS source, p.journal, "
        . "GROUP_CONCAT(u.firstname, ' ', u.lastname "
        . "ORDER BY p.id SEPARATOR ', ') AS userlist, "
        . "GROUP_CONCAT(p.id ORDER BY p.id SEPARATOR ', ') AS idlist "
        . "FROM publicationrecords p, users u "
        . "WHERE p.userid = u.userid AND p.year BETWEEN ? AND ? "];

    $startyear = Input::get("startyear");
    $endyear = Input::get("endyear");  
    
    $query[] = $startyear;
    $query[] = $endyear;
    
    $title = Input::get("title");

    if ($title !== "")
    {
        $query[0] = $query[0] . "AND p.title LIKE ? ";
        $query[] = "%" . $title . "%";
    }

    if (!$checkbox["patents"])
    {
        $query[0] = $query[0] . "AND p.journal = 1 ";
    }
    else if (!$checkbox["journals"])
    {
        $query[0] = $query[0] . "AND p.journal = 0 ";
    }

    if (!$checkbox["clients"])
    {
        if (!$checkbox["internal"])
        {
            // External
            $query[0] = $query[0] . "AND p.source = 'External' ";
        }
        else if (!$checkbox["external"])
        {
            // Internal
            $query[0] = $query[0] . "AND p.source = 'Internal' ";
        }
        else
        {
            // Internal + External
            $query[0] = $query[0] . "AND p.source IN ('Internal', 'External') ";
        }
    }
    else
    {
        if (!$checkbox["internal"])
        {
            if (!$checkbox["external"])
            {
                    // Clients
                    $query[0] = $query[0] . "AND p.source NOT IN ('Internal', 'External') ";
            }
            else
            {
                    // Clients + External
                    $query[0] = $query[0] . "AND p.source <> 'Internal' ";
            }
        }
        else if (!$checkbox["external"])
        {
            // Clients + Internal
            $query[0] = $query[0] . "AND p.source <> 'External' ";
        }
    }

    $query[0] = $query[0] . "GROUP BY p.title, p.year, p.volume, p.issue, "
            . "p.startpage, p.endpage, p.journal ORDER BY p.year DESC, title ASC";

    // get users list for multi select
    $DB = DB::getInstance();
    
    $users = $DB->assocQuery("SELECT userid, firstname, lastname FROM users "
            . "ORDER BY lastname ASC, firstname ASC")->results();
    
    if ($DB->error())
    {
        Redirect::error("Cannot retrieve user list");
    }

    $publications = $DB->runQuery(PDO::FETCH_ASSOC, null, $query)->results();
    
    if ($DB->error())
    {
        Redirect::error("Cannot retrieve publication list");
    }

    // render table
    render("templates/publicationsummary.php", ["publications" => $publications, 
        "pubtitle" => $title, "startyear" => $startyear, "endyear" => $endyear, 
        "checkbox" => $checkbox, "users" => $users, 
        "title" => "Publication Summary"]);
}
