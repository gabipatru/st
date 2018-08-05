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
    
    /**
     * Transsform from large number to KB, MB
     */
    public function displayBytes($iBytes)
    {
        $temp = $iBytes;
        $sBytes = $iBytes . ' B';
        if ($temp >= 1024) {
            $temp = $temp / 1024;
            $sBytes = sprintf('%10.2f', $temp);
            $sBytes  = $sBytes . ' KB';
            $temp = (int) $temp;
        }
        if ($temp >= 1024) {
            $temp = $temp / 1024;
            $sBytes = sprintf('%10.2f', $temp);
            $sBytes = $sBytes . ' MB';
            $temp = (int) $temp;
        }
        return $sBytes;
    }
}