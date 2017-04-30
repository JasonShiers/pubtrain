<?php
class Token
{
    public static function generate()
    {
        if (Session::exists('token')) return Session::get('token');
        return Session::put('token', hash('sha256', uniqid()));
    }
    
    public static function check($token)
    {
        if(Session::exists('token') && $token === Session::get('token'))
        {
            // Session::delete('token');
            return true;
        }
        
        return false;
    }
}