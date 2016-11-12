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
        if (!isset(self::$_instance))
        {
            self::$_instance = new DB();
        }

        return self::$_instance;
    }

    public function runQuery($fetch, $class, $sql_args = [])
    {
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
        return $this->runQuery(PDO::FETCH_CLASS, func_get_args());
    }
    
    public function assocQuery(/* $sql [, ... ] */)
    {
        return $this->runQuery(PDO::FETCH_ASSOC, null, func_get_args());
    }
    
    public function assocListQuery($statement_pre, $list, $statement_post, $params)
    {
        $statement = $statement_pre 
                . implode(", ", 
                        array_map(function (){
                            return '?';
                        }, $list)) 
                . $statement_post;
        $query = array_merge($list, $params);
        array_unshift($query, $statement);
               
        return $this->runQuery(PDO::FETCH_ASSOC, null, $query);
    }

    public function results()
    {
        return $this->_results;
    }
    
    public function count()
    {
        return $this->_count;
    }

    public function error()
    {
        return $this->_error;
    }
}
