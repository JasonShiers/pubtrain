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
    
    public static function flash($name, $string = '')
    {
        if (self::exists($name))
        {
            $session = self::get($name);
            self::delete($name);
            
            return $session;
        }
        else
        {
            self::put($name, $string);
        }
    }
    
    public static function logout()
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
}