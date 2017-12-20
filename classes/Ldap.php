<?php
class Ldap
{
    private $_ldap = null, $_bind = false, $_error = false;
    
    public function connect()
    {
        /*
         * Connect to LDAP Server ahead of authenticating
         */
        try
        {
            $this->_ldap = ldap_connect(LDAPURL);
            
            ldap_set_option($this->_ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->_ldap, LDAP_OPT_REFERRALS, 0);
        } 
        catch (Exception $e) 
        {
            // trigger (big, orange) error
            trigger_error($e->getMessage(), E_USER_ERROR);
            exit;
        }
    }
    
    public function bind($username, $password)
    {
        /*
         * Authenticate to LDAP server over TLS
         * using $username and $password
         * returns: Login instance for subsequent query (e.g. ->ldapLoadEntry)
         */
        $ldaprdn = LDAPDOMAIN . "\\" . $username;
        
        if (ldap_start_tls($this->_ldap))
        {
            $this->_bind = ldap_bind($this->_ldap, $ldaprdn, $password);
        }
        
        if (!$this->_bind)
        {
            $this->_error = true;
        }
        
        return $this;
    }
    
    public function getUser($username)
    {
        /*
         * Perform LDAP query for user information and load into session
         * $username: Domain username of user to query
         * returns: Array. Cleaned user information or NULL if there is an error
         */
        if (!$this->_bind || $this->_error)
        {
			return null;
        }
        
        $filter="(sAMAccountName=" . $username . ")";
        
        $search = ldap_search($this->_ldap, LDAPBASEDN, $filter);

        $results = ldap_get_entries($this->_ldap, $search);

        // Make sure there is exactly one result
        if (!isset($results['count']) || $results['count'] != 1)
        {
            $this->_error = 1;
            return null;
        }
        
        // Clean up and return first result
        return cleanLDAPUser($results[0]);
    }
        
    public function close() {
        /*
         * Close connection to LDAP server
         */
        ldap_close($this->_ldap);
        $this->_bind = false;
    }
    
    public function getNewUsers() 
    {
        /*
         * Get all users from LDAP and cross-reference against those in DB
         * Returns: Array of users not found in DB in following format
         *   [userid, firstname, lastname, email, department]
         */
        if (!$this->_bind || $this->_error)
        {
            Redirect::error("No connection to Active Directory", "login.php");
        }
        
        // Get LDAP entries
        $result = ldap_list($this->_ldap, LDAPBASEDN, "objectClass=person");    
        $entries = ldap_get_entries($this->_ldap, $result);
        
        // query database for users
        $DB = DB::getInstance();
        $users = array_column($DB->assocQuery("SELECT userid FROM users")
                ->results(), "userid");
        
        if ($DB->error() || $DB->count() == 0)
        {
            Redirect::error("Cannot access database", "login.php");
        }
        
        $userlist = [];
            
        foreach ($entries as $entry)
            {
            // Skip any user matching DB user, or without email or department
            if (in_array(strtolower($entry["samaccountname"][0]), $users) 
                    || !isset($entry["mail"][0]) 
                    || !isset($entry["department"][0]))
            {
                continue;
            }

            // Clean up department
            $department = parseDepartment($entry["department"][0]);

            // If department recognised, append user to userlist
            if (isset($department))
            {
                $userlist[] = [
                    strtolower($entry["samaccountname"][0]), 
                    $entry["givenname"][0], 
                    $entry["sn"][0], 
                    $entry["mail"][0], 
                    $department];
            }
            }
        return $userlist;
            }
    
    public function error()
    {
        return $this->_error;
    }
}