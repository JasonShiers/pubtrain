<?php

/**
 * functions.php
 *
 * Helper functions.
 */

require_once("constants.php");

/**
 * Facilitates debugging by dumping contents of variable
 * to browser.
 */
function dump($variable)
{
    require("templates/dump.php");
    exit;
}

/**
 * Outputs string safely escaped for HTML.
 */
function escapeHTML($string)
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Renders template, passing in values.
 */
function render($template, $values = [])
{
    // if template exists, render it
    if (file_exists("$template"))
    {
        // extract variables into local scope
        extract($values);

        // render header
        require("templates/header.php");

        // render template
        require("$template");

        // render footer
        require("templates/footer.php");
    }

    // else err
    else
    {
        trigger_error("Invalid template: $template", E_USER_ERROR);
    }
}

function enumerateselectusers($users, $selected = "", $includeself = 0)
{
    /* Function to enumerate the <option> tags for a <select> container that is used to select users. 
     * $users is an array of associative arrays including keys: firstname, lastname, userid 
     * $selected is the userid that should be selected by default
     * $includeself can be set to 1 so that logged in user is included in list */	
    
    $self = Session::get("userid");
    
    foreach ($users as $user)
    {
        if($user["userid"] == $self && $includeself == 0)
        {
            continue;
        }
        // else
        print("<option style=\"text-align: left;\" value=\"" . escapeHTML($user["userid"]) . "\" ");
        if(strlen($selected)>0 && $user["userid"] == $selected)
        {
            print("selected=\"selected\" ");
        }
        print (">" . escapeHTML($user["firstname"] . " " . $user["lastname"]) . "</option>\n");
    }
}

function enumeratemonthoptions()
{
    /* Function to enumerate the <option> tags for a <select> container that is used to
     * select a month. */
    print("<option value selected disabled>Month</option>\n");
    foreach (['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', 
        '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'] AS $val => $label)
    {
        print("<option value=\"{$val}\">{$label}</option>");
    }
}

function getLineGroupArray(array $users, $depth)
{
    /** 
     * Function to return array of line reports in group under initial user(s) specified
     * $users: array of initial user(s) in an associative array with key userid
     * e.g. [["userid" => "j.shiers"], ...]
     * $depth: integer to track depth within tree, will be incremented at each
     * recursive call and prevent further recursion beyond $depth = $MAXDEPTH
     * Returns: Indexed array of results in an associative array
     * Associative array keys: firstname, lastname and userid
     */
    $MAXDEPTH = 5;

    $DB = DB::getInstance();
    
    $reports = $DB->assocListQuery("SELECT firstname, lastname, userid "
            . "FROM users WHERE linemgr IN (", 
            array_column($users, "userid"), 
            ")")->results();
    
    if ($DB->error()){
        Redirect::error("Cannot retrieve line reports");
    }
    
    if ($DB->count() == 0 || $depth > $MAXDEPTH)
    {
        // base case
        return $reports;
    }
    else
    {
        // recursive case
        return array_merge(getLineGroupArray($reports, $depth + 1), $reports);
    }
}

function getLineGroupUser($userid)
{
    /**
     * Function to return array of line report group under user excluding initial user
     * using call to getLineReportsHelper function.
     * $userid: UserID of the line manager
     * Returns: Indexed array of results in an associative array
     * Associative array keys: firstname, lastname and userid
     */
    return getLineGroupArray([["userid" => $userid]], 0);
}

function parseDepartment($department)
{
    /**
     * Function to convert department to database format
     * $department: Department string returned from either LDAP query or DB
     * Returns: Department string in DB format or NULL
     */
    switch($department)
    {
        case "Chemistry":
        case "Compchem":
        case "Bioscience":
        case "DMPK":
        case "Non-scientific":
            return $department;
        case "Computational Chemistry":
            return "Compchem";
        case "Analytical Chemistry":
            return "DMPK";
        default:
            return NULL;
    }
}

function cleanLDAPUser($user)
{
    /*
     * Function to change structure of LDAP user to cleaner format for input
     * $user: LDAP user object in following format:
     * array(
     *   'first-name' => array(
     *     'count' => 1,
     *     0 => 'Alex'
     *    ),
     *   ...
     * )
     * Returns: Associative array of ["property" => "value"] pairs
     */
    
    // Filter out keys that are not used
    $filteredUser = array_intersect_key($user, array_flip(
            ["givenname", "sn", "department", "samaccountname", "mail"]
            ));
    
    $cleanedUser = [];
    
    // For each key, assign value as value of subkey [0]
    foreach ($filteredUser as $key => $value) {
        $cleanedUser[$key] = $value[0];
    }
    
    return $cleanedUser;
}