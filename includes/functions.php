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

    $query = ["SELECT firstname, lastname, userid FROM users WHERE linemgr IN ("];

    foreach($users as $user)
    {
        // Add parameter to query and append variable
        $query[0] = $query[0] . "?, ";
        $query[] = $user["userid"];
    }
    // Remove trailing comma and terminate query
    $query[0] = rtrim($query[0], ", ") . ")";

    $reports = call_user_func_array("query", $query);

    if (count($reports) == 0 || $depth > 5)
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

function enumerateselectusers($users, $selected, $includeself = false)
{
    /* Function to enumerate the <option> tags for a <select> container that is used to select users. 
     * $users is an array of associative arrays including keys: firstname, lastname, userid 
     * $selected is the userid that should be selected by default */	
    foreach ($users as $user)
    {
        if($includeself === true || $user["userid"] !== $_SESSION["userid"])
        {
            print("<option style=\"text-align: left;\" value=\"" . htmlspecialchars($user["userid"]) . "\" ");
            if(strlen($selected)>0 && $user["userid"] == $selected)
            {
                print("selected=\"selected\" ");
            }
            print (">" . htmlspecialchars($user["firstname"] . " " . $user["lastname"]) . "</option>\n");
        }
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
