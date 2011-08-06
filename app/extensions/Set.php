<?php

namespace app\extensions;

class Set extends \lithium\util\Set {
    /**
     * Replaces matched keys from $data with their association in $replacementArray
     * If $removeNonmatching is set to true (default) it'll only return the keys which
     * matched the associations in $replacementArray.
     *
     * example: $data = array('apple' => 'red', 'banana' => 'yellow');
     *          $replacementArray = array('apple' => 'tomato');
     *          $removeNonMatching = false;
     *
     * Result: array('tomato' => 'red', 'banana' => 'yellow);
     *
     * with $removeNonmatching = true the result would be:
     * array('tomato' => 'red')
     *
     * @param array $data the data to be filtered
     * @param array $replacementArray an array to check the data keys against
     * @param boolean $removeNonMatching removes any keys that don't exist in $replacementArray
     * @return array filtered array returns result of filter
     */
    static function filterArrayKeys($data, $replacementArray, $removeNonMatching = true){
        $output = array();

        foreach($data as $key => $entry){
            if(isset($replacementArray[$key])){
                $output[$replacementArray[$key]] = $entry;
                continue;
            }else if($removeNonMatching){
                continue;
            }

            $output[$key] = $entry;
        }

        return $output;
    }

    /**
     * Removes any array keys from $data that aren't in $checkForArray
     * Useful for scrubbing input data to ensure only the data you want is in the array.
     *
     * Example: $data = array('users' => ..., 'password' => ..., 'cat' => 'cheeseburger');
     *  $checkForArray = array('users', 'password');
     *
     * Result: array('users' => ..., 'password' => ...);
     *
     * @param array $data
     * @param array $checkForArray
     * @return array
     */
    static function removeNonMatching($data, $checkForArray){
        $output = array();

        foreach($data as $key => $entry){
            if(in_array($key, $checkForArray)){
                $output[$key] = $entry;
            }
        }

        return $output;
    }

    /**
     * a non-recursive mass 'isset' check for array keys.
     *
     * useful way of ensuring all required data is available in a single shot
     *
     * example: $data = array('a' => 'b', 'c' => 'd');
     * $checkForArray = ('a','c','e');
     *
     * result: false because the key 'e' does not exist
     *
     * @param array $data
     * @param array $checkForArray
     * @param boolean $checkEmpty
     * @return boolean
     */
    static function arrayKeysSet($data,$checkForArray,$checkEmpty = false){
        foreach($checkForArray as $checkFor){
            if(!$checkEmpty && !isset($data[$checkFor])){
                return false;
            }else if($checkEmpty && empty($data[$checkFor])){
                return false;
            }
        }
        return true;
    }
}

?>