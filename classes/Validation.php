<?php
class Validation
{
    private $_passed = false, $_errors = [];

    public function check($source, $items = [])
    {
        /*
         * Check $source against array of rule $items
         * $source: Associative array of [$sourcekey => $sourcevalue]
         * $items: Associative array of [$sourcevalue => $rules]
         * $rules: Associative Array of [$rule => $criteria]
         * Returns: Validation instance for further processing (e.g. ->passed())
         */

        // Iterate over keys that need to be checked
        foreach ($items as $item => $rules)
        {
            // $item is key to check in $source
            $value = NULL;
            if (isset($source[$item]))
            {
            $value = $source[$item];
            }

            // Set description of $item for error messages
            $itemName = $item;
            if (isset($rules['friendlyname']))
            {
                $itemName = $rules['friendlyname'];
            }
            
            // Iterate over criteria for key
            foreach ($rules as $rule => $ruleValue)
            {
                $this->checkRule($value, $rule, $ruleValue, $itemName);
            }
        }
            
        // Set passed to true if there are no errors, else false
        $this->_passed = empty($this->_errors);

        return $this;
    }
    
    private function checkRule($value, $rule, $ruleValue, $itemName)
            {
        /*
         * Check $value meets a criterion
         * $value: String value to check
         * $rule: String name of rule (e.g. 'required' or 'minlength')
         * $ruleValue: Boolean or String criterion (e.g. TRUE, '9')
         * $itemName: String name of criterion for error log
         * 
         * No return value. Any rules not passed will be added to errorlist
         */
        
        // If required but not set
        if ($rule === 'required' && $ruleValue === true && 
                (!isset($value) || $value === "" || $value === " "))
                {
            $this->addError("{$itemName} is required");
                }
        
        // If not set, skip rest of checking
        if (!isset($value))
                {
            return;
        }
        
        // If value is set, check other rules
                    switch ($rule) {
                        case 'minlength':
                if(strlen($value) < $ruleValue)
                            {
                    $this->addError("{$itemName} must be at least {$ruleValue} "
                    . "characters");
                            }
                            break;

                        case 'maxlength':
                if(strlen($value) > $ruleValue)
                            {
                    $this->addError("{$itemName} must be at most {$ruleValue} "
                    . "characters");
                            }
                            break;

                        case 'minval':
                if($value < $ruleValue)
                            {
                    $this->addError("{$itemName} must be at least {$ruleValue}");
                            }
                            break;

                        case 'maxval':
                if($value > $ruleValue)
                            {
                    $this->addError("{$itemName} must be at most {$ruleValue}");
                            }
                            break;

                        default:
                return;
                    }
                }

    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        /*
         * Returns list of errors as sentences
         */
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