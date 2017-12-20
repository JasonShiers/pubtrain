<?php
class Token
{
    public static function generate()
    {
        /*
         * Generate token for browser session or return existing token
         */
        if (Session::exists('token'))
        {
            return Session::get('token');
        }
        return Session::put('token', hash('sha256', uniqid()));
    }
    
    public static function check($token)
    {
        /*
         * Check session token against value of $token
         */
        if(Session::exists('token') && $token === Session::get('token'))
        {
            return true;
        }
        return false;
    }
}