<?php
class Config 
{
    public static function get($path = null) 
    {
        if ($path && isset($GLOBALS["config"]))
        {
            $config = $GLOBALS["config"];
            $path = explode("/", $path);
            
            foreach ($path as $bit) 
            {
                if (isset($config[$bit])) 
                {
                    $config = $config[$bit];
                } 
                else 
                {
                    return null;
                }
            }
            
            return $config;
        }
        return null;
    }
}
