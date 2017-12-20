<?php
class DB
{
    private static $_instance = null;
    private $_pdo, $_query, $_error = false, $_results, $_count = 0;

    private function __construct()
    {
        try
        {
            // connect to database
            $this->_pdo = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

            // ensure that PDO::prepare returns false when passed invalid SQL
            $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch (Exception $e)
        {
            // trigger (big, orange) error
            trigger_error($e->getMessage(), E_USER_ERROR);
            exit;
        }
    }

    public static function getInstance()
    {
        // return current instance of DB() or create new instance if null
        if (!isset(self::$_instance))
        {
            self::$_instance = new DB();
        }

        return self::$_instance;
    }

    public function runQuery($fetch, $class, $sql_args = [])
    {
        /*
         * Runs query on DB instance using prepared SQL statements
         * $fetch: Integer corresponding to Fetch style (e.g. PDO::FETCH_ASSOC)
         * $class: Either - String, name of Class to fetch results into when using PDO::CLASS, 
         *                - or NULL
         * $sql_args: Array of strings. $sql_args[0] = SQL statement with ?
         *                              $sql_args[1...] = parameters
         * 
         * returns: DB instance for further processing (e.g. ->results())
         */
        
        // reset _error value
        $this->_error = false;

        $sql = $sql_args[0];
        
        // treat any other arguments as parameters
        $parameters = array_slice($sql_args, 1);
        
        // prepare SQL statement
        $this->_query = $this->_pdo->prepare($sql);
        if ($this->_query === false)
        {
            // trigger (big, orange) error
            trigger_error($this->_pdo->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        if ($this->_query->execute($parameters))
        {
            if ($class !== null)
            {
                $this->_results = $this->_query->fetchAll($fetch, $class);
            }
            else
            {
                $this->_results = $this->_query->fetchAll($fetch);
            }
            
            $this->_count = $this->_query->rowCount();
        }
        else
        {
            $this->_error = true;
        }

        return $this;
    }
    
    public function objQuery(/* $class, $sql [, ... ] */)
    {
        /*
         * Execute runQuery fetching into objects
         * $class = String, name of class to fetch into
         * $sql = Array of strings. $sql[0] = statement with ?
         *                          $sql[1...] = parameters
         * returns: DB instance for further processing (e.g. ->results())
         */
        return $this->runQuery(PDO::FETCH_CLASS, func_get_args());
    }
    
    public function assocQuery(/* $sql [, ... ] */)
    {
        /*
         * Execute runQuery fetching into associative array
         * $sql = Array of strings. $sql[0] = statement with ?
         *                          $sql[1...] = parameters
         * returns: DB instance for further processing (e.g. ->results())
         */
        return $this->runQuery(PDO::FETCH_ASSOC, null, func_get_args());
    }
    
    public function assocListQuery($statement_pre, $list, $statement_post, $params = [])
    {
        /*
         * Execute runQuery fetching into associative array and matching to a 
         * list of rows (e.g. using SQL WHERE userid IN (...))
         * $statement_pre: String. initial part of SQL statement
         * $list: Array of strings. List of table rows to match for query
         * $statement_post: String. End of SQL statement
         * $params: Array of strings. Parameters 
         * returns: DB instance for further processing (e.g. ->results())
         */
        
        // Create statement by taking _pre joined to comma separated ? 
        // instances for each list item, then join _post
        $statement = $statement_pre 
                . implode(", ", array_map(function (){ return '?'; }, $list)) 
                . $statement_post;
        
        // create query by merging list and params
        $query = array_merge($list, $params);
        
        // prepend statement to beginning of array
        array_unshift($query, $statement);
               
        return $this->runQuery(PDO::FETCH_ASSOC, null, $query);
    }

    public function getDepMask($department)
    {
        /*
         * Run query to get the depmask of a department from the DB
         * $department: String. Department in DB format
         * returns: String(number) depmask or NULL if department not found
         */
        
        if(!isset($department)){
            return NULL;
        }
        
        $dep = self::assocQuery("SELECT department, depmask FROM departments "
                . "WHERE department = ?", $department)->results();
        
        if (self::error() || self::count() == 0)
        {
            return NULL;
        }
        
        return $dep[0]["depmask"];
    }

    public function results()
    {
        // Return results of current query as Array
        return $this->_results;
    }
    
    public function count()
    {
        // Return results count of current query
        return $this->_count;
    }

    public function error()
    {
        // Return error level of current query or false if no error
        return $this->_error;
    }
    
    public function errorMsg()
    {
        // Return error level of current query or false if no error
        return $this->_query->errorInfo()[2];
    }
}
