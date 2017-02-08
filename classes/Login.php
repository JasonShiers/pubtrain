<?php

class Login
{
    private $_ldap = null, $_bind = false, $_error = false;
    
    public function ldapConnect()
    {
        try
        {
            $this->_ldap = ldap_connect("ldap://syg-vdc1.sygnature.local");
            
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
    
    public function ldapBind($username, $password)
    {
        $ldaprdn = 'SYGNATURE' . "\\" . $username;
        
        if (ldap_start_tls($this->_ldap))
        {
            $this->_bind = @ldap_bind($this->_ldap, $ldaprdn, $password);
        }
        
        if (!$this->_bind)
        {
            $this->_error = true;
        }
        
        return $this;
    }
    
    public function ldapLoadEntry($username)
    {
        if (!$this->_bind || $this->_error)
        {
			return null;
        }
        
        $filter="(sAMAccountName=" . $username . ")";
        
        $result = ldap_search($this->_ldap,"dc=SYGNATURE,dc=local",$filter);

        // Can optionally sort (e.g. by surname), but only expecting 1 result here
        // ldap_sort($ldap,$result,"sn");

        $info = ldap_get_entries($this->_ldap, $result);
        
        if (!isset($info['count']) || $info['count'] != 1)
        {
            $this->_error = 1;
            return null;
        }
        
        Session::put("name", $info[0]["cn"][0]);
        Session::put("forename", $info[0]["givenname"][0]);
        Session::put("surname", $info[0]["sn"][0]);
        Session::put("jobtitle", $info[0]["title"][0]);
        Session::put("department", $info[0]["department"][0]);
        Session::put("accountname", $info[0]["samaccountname"][0]);
        Session::put("mail", $info[0]["mail"][0]);
        Session::put("userid", strtolower($info[0]["samaccountname"][0]));
        Session::put("timestamp", time());
        Session::put("admin", 0);
        Session::put("publicationadmin", 0);
        Session::put("superadmin", 0);
        
        return $this;
    }
    
    public function dbDebugLoadEntry($username)
    {
        // query database for user
        $DB = DB::getInstance();
        
        $rows = $DB->assocQuery("SELECT * FROM users WHERE userid = ?", 
                $username)->results();
        if ($DB->error())
        {
            Redirect::error("Cannot access user in database", "logout.php");
        }
        else if ($DB->count() != 1)
        {
            Redirect::error("Cannot find user information in database", 
                    "logout.php");
        }
        
        Session::put("name", $rows[0]["firstname"] . " " . $rows[0]["lastname"]);
        Session::put("forename", $rows[0]["firstname"]);
        Session::put("surname", $rows[0]["lastname"][0]);
        Session::put("department", $rows[0]["department"]);
        Session::put("accountname", $rows[0]["username"]);
        Session::put("mail", $rows[0]["email"]);
        Session::put("userid", $rows[0]["userid"]);
        Session::put("timestamp", time());
        Session::put("admin", 0);
        Session::put("publicationadmin", 0);
        Session::put("superadmin", 0);
        
        return $this;
    }
    
    public function ldapClose() {
        @ldap_close($this->_ldap);
        $this->_bind = false;
    }
    
    public function dbLoadEntry($username)
    {
        // query database for user
        $DB = DB::getInstance();
        
        $rows = $DB->assocQuery("SELECT * FROM users WHERE userid = ?", 
                Session::get("userid"))->results();
        if ($DB->error())
        {
            Redirect::error("Cannot access user in database", "logout.php");
        }
        
        if ($DB->count() == 0)
        {
            // Insert new user into database and redirect to user info page
            $DB->assocQuery("INSERT INTO users (userid, firstname, "
                    . "lastname, email) VALUES (?, ?, ?, ?)", 
                    Session::get("userid"), Session::get("forename"), 
					Session::get("surname"), Session::get("mail"));
            
            if ($DB->error())
            {
                Redirect::error("Cannot insert new user into database", 
                        "logout.php");
            }
            Redirect::to("userinfo.php");
        }
        else
        {
            // Read user information from DB into Session and redirect if incomplete
            Session::put("department", $rows[0]["department"]);
            Session::put("linemgr", $rows[0]["linemgr"]);
            Session::put("admin", $rows[0]["admin"]);
            Session::put("publicationadmin", $rows[0]["publicationadmin"]);
            Session::put("superadmin", $rows[0]["superadmin"]);

            $dep = $DB->assocQuery("SELECT department, depmask FROM departments "
                    . "WHERE department = ?", $rows[0]["department"])->results();
            if ($DB->error())
            {
                Redirect::error("Cannot verify user's department", "logout.php");
            }
            else if ($DB->count() == 0)
            {
                Redirect::to("userinfo.php");
            }
            else
            {
                Session::put("depmask", $dep[0]["depmask"]);
            }
        }
    }
    
    public function error()
    {
        return $this->_error;
    }
}