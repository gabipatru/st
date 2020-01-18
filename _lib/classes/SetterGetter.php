<?php

/*
 * This class implements simple setter and getter 
 */

class SetterGetter {
    
    use Messages;
    use Translation;
    
    public function __call($function, $args) {
        $prefix = substr($function, 0, 3);
        $property = strtolower(substr($function, 3));
        // for setter
        if ($prefix == 'set' && count($args) == 1) {
            $this->$property = $args[0];
            return;
        }
    
        // for getter
        if ($prefix == 'get' && count($args) == 0) {
            if (isset($this->$property)) {
                return $this->$property;
            }
            else {
                return null;
            }
        }
    }

    /**
     * Get all the values of some fields. The fields for which values will be
     * returned will be found in the array passed to the function. Thiese values
     * can come directly from DB Data Model, so we have to strip _
     */
    public function allFieldsByArray($arrFields)
    {
        if (! $arrFields || ! is_array($arrFields)) {
            return false;
        }

        $arr = [];
        foreach ($arrFields as $field) {
            $property = str_replace('_', '', $field);
            $arr[$field] = $this->$property;
        }

        return $arr;
    }
}
