<?php
require_once 'Dotenv.php';
require_once 'Loader.php';
require_once 'Validator.php';

if (!function_exists('env')) {
    // Define function `env` if it doesn't already exists

    /**
     * Gets the environment varaible if set or returns the second argument
     * 
     * @param string    $varname
     * The variable. null will return all environment varaibles
     * 
     * @param mixed     $default_value
     * The value to return if $varname is not set
     * 
     * @return array|mixed
     */
    function env($varname = null, $default_value = "") {
        if (is_null($varname)) {
            // Return merged environment arrays for full context
            return array_merge($_ENV, $_SERVER);
        }

        // Check _ENV and _SERVER first, then getenv
        if (array_key_exists($varname, $_ENV)) {
            $value = $_ENV[$varname];
        } elseif (array_key_exists($varname, $_SERVER)) {
            $value = $_SERVER[$varname];
        } else {
            $value = getenv($varname);
            // getenv returns false when not set; treat false as not found
            if ($value === false) {
                $value = null;
            }
        }

        // If variable not found, return default. Important: allow falsy values like "0" or empty string.
        return ($value === null) ? $default_value : $value;
    }
}
