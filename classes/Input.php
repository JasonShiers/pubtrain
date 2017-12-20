<?php
class Input
{
    public static function get($item, $blank = "")
    {
        /*
         * Get $item, first looking in $_POST, then $_GET
         * Post will return either an array or a string
         * Get will return only a string
         * Returns: value of item or $blank if not set in either location
         */
        
        // See if item can be returned as array (no filtering in this case)
        $postArray = filter_input(INPUT_POST, $item, FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);
        if (isset($postArray) && (is_array($postArray)))
        {
            return $postArray;
        }
        
        // See if item can be returned as string (no filtering)
        $postString = filter_input(INPUT_POST, $item);
        if (isset($postString) && is_string($postString) && strlen($postString) > 0)
        {
            return $postString;
        }
        
        $getString = filter_input(INPUT_GET, $item);
        if (isset($getString) && strlen($getString) > 0)
        {
            return $getString;
        }
        
        // If both inputs are NULL treat value as $blank
        return $blank;
    }
}

