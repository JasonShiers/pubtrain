<?php
class User
{
    private $_db;
    
    public function __construct($user = NULL)
    {
        $this->_db = DB::getInstance();
    }
    
    public function create($fields = array())
    {
        if (!$this->_db->insert('users', $fields))
        {
            throw new Exception('Error creating new user account');
        }
    }
}