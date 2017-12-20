<?php
class Session
{
    public static function exists($name)
    {
        return (isset($_SESSION[$name]));
    }

    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }
    
    public static function get($name)
    {
        return $_SESSION[$name];
    }
    
    public static function delete($name)
    {
        if (self::exists($name))
        {
            unset($_SESSION[$name]);
        }
    }
    
    public static function logout()
    {
        // unset any session variables
        $_SESSION = [];

        // expire cookie
        if (!empty(filter_input(INPUT_COOKIE, session_name(), 
                FILTER_SANITIZE_SPECIAL_CHARS)))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }
    
    public static function loadUser($info)
    {
        /*
         * Load user information into session
         * $info: Associative array of user information (from DB or LDAP)
         * Will populate any fields recognised, checking core fields 
         * (name, email userid) are captured
         * 
         * Returns: TRUE, or logs out and redirects on error
         */
        
        // Update timestamp and initialise admin flags
        self::put("timestamp", time());
        self::put("admin", 0);
        self::put("publicationadmin", 0);
        self::put("superadmin", 0);
        self::put("confbookadmin", 0);
        
        // Iterate over array capturing useful values
        foreach ($info as $key => $value)
        {
            switch($key)
            {
                case "givenname":
                case "firstname":
                    self::put("forename", $value);
                    break;
                case "sn":
                case "lastname":
                    self::put("surname", $value);
                    break;
                case "department":
                    self::put("department", parseDepartment($value));
                    break;
                case "userid":
                case "samaccountname":
                    self::put("userid", strtolower($value));
                    break;
                case "mail":
                case "email":
                    self::put("mail", $value);
                    break;
                case "linemgr":
                    self::put("linemgr", $value);
                    break;
                case "admin":
                    self::put("admin", $value);
                    break;
                case "publicationadmin":
                    self::put("publicationadmin", $value);
                    break;
                case "superadmin":
                    self::put("superadmin", $value);
                    break;
                case "confbookadmin":
                    self::put("confbookadmin", $value);
                    break;
                default:
                    break;
            }
        }
        // Check that minimum information has been obtained to continue
        $validate = new Validation();
        $validate->check($_SESSION, array(
            'forename' => array(
               'required' => true
               ),
            'surname' => array(
               'required' => true
               ),
            'mail' => array(
               'required' => true
               ),
            'userid' => array(
               'required' => true
               )
            ));
        
        if(!$validate->passed()){
            Session::logout();
            Redirect::error($validate->errors(), "logout.php");
        }
        
        self::put("name", self::get("forename") . " " . self::get("surname"));
        return TRUE;
    }
}