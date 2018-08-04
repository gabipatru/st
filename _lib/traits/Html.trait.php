<?php

/*
 * This trait provides some useful HTML functions
 */
trait Html
{
    /**
     * When the 2 values are equal, the function must enabled the select option to be selected
     * It displays selected="SELECTED"
     */
    public function selected($currentValue, $test)
    {
        if ($currentValue == $test) {
            echo 'selected="SELECTED"';
        }
    }
}