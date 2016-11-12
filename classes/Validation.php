<?php
class Validation
{
    private $_passed = false, $_errors = array(), $_db = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules)
        {
            $value = $source[$item];

            if (isset($rules['friendlyname']))
            {
                $itemname = $rules['friendlyname'];
            }
            else
            {
                $itemname = $item;
            }
            
            foreach ($rules as $rule => $rule_value)
            {
                if ($rule === 'required' && $rule_value === true && !isset($value))
                {
                    $this->addError("{$itemname} is required");
                }
                else if (!empty($value))
                {
                    switch ($rule) {
                        case 'minlength':
                            if(strlen($value) < $rule_value)
                            {
                                $this->addError("{$itemname} must be at least {$rule_value} characters.");
                            }
                            break;

                        case 'maxlength':
                            if(strlen($value) > $rule_value)
                            {
                                $this->addError("{$itemname} must be at most {$rule_value} characters.");
                            }
                            break;

                        case 'minval':
                            if($value < $rule_value)
                            {
                                $this->addError("{$itemname} must be at least {$rule_value}.");
                            }
                            break;

                        case 'maxval':
                            if($value > $rule_value)
                            {
                                $this->addError("{$itemname} must be at most {$rule_value}");
                            }
                            break;

                        default:
                            break;
                    }
                }
            }
        }

        if (empty($this->_errors))
        {
            $this->_passed = true;
        }

        return $this;
    }

    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        $errorlist = "";

        foreach ($this->_errors as $error)
        {
            $errorlist .= "{$error}. ";
        }

        return $errorlist;
    }

    public function passed() {
        return $this->_passed;
    }
}