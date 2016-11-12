<?php
class Input
{
    public static function get($item, $blank = "")
    {
        if (isset($_POST[$item]) 
                && (is_array($_POST[$item]) 
                        ||  (is_string($_POST[$item])
                                && strlen($_POST[$item]) > 0
                            )
                    )
            )
        {
            return $_POST[$item];
        }
        else if (isset($_GET[$item]) && strlen($_GET[$item]) > 0)
        {
            return $_GET[$item];
        }
        return $blank;
    }
}

