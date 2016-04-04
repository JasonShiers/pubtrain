<?php

    /**
     * functions.php
     *
     * Helper functions.
     */

    require_once("constants.php");

    /**
     * Apologizes to user with message.
     */
    function apologize($message)
    {
        render("templates/apology.php", ["message" => $message]);
        exit;
    }

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
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
    function logout()
    {
        // unset any session variables
        $_SESSION = [];

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
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

	function getLineTreeHelper(array $users, $depth)
	{
		/** 
		 * Function to return array of line reports in tree under initial user(s) specified
		 * $users: array of initial user(s), e.g. $users[0]["userid"] = "j.shiers"
		 * $depth: integer to track depth within tree, will be incremented at each
		 * recursive call and prevent further recursion beyond $depth = 5
		 * Returns: Array of results including firstname, lastname and userid 
		**/
		
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
			return array_merge(getLineTreeHelper($reports, $depth + 1), $reports);
		}
	}
	
	function getLineTree($userid)
	{
		/**
		 * Function to return array of line report tree under user excluding initial user
		 * using call to getLineReportsHelper function.
		 * $userid: UserID of the line manager
		 * Returns: Array of results including firstname, lastname and userid
		**/
		$users = [];
		$users[0]["userid"] = $userid;
		return getLineTreeHelper($users, 0);
	}
?>
