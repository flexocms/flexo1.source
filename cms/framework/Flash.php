<?php

/**
 * Flash service
 *
 * Purpose of this service is to make some data available across pages. Flash
 * data is available on the next page but deleted when execution reach its end.
 *
 * Usual use of Flash is to make it possible for the current page to pass some data
 * to the next one (for instance success or error message before HTTP redirect).
 *
 * Flash::set('errors', 'Blog not found!');
 * Flass::set('success', 'Blog has been saved with success!');
 * Flash::get('success');
 *
 * Flash service as a concept is taken from Rails. This thing is really useful!
 */
final class Flash
{
    const SESSION_KEY = 'framework_flash';
    
    private static $_previous = array(); // Data that prevous page left in the Flash

    /**
    * Return specific variable from the flash. If value is not found NULL is
    * returned
    *
    * @param string $var Variable name
    * @return mixed
    */
    public static function get($var)
    {
        return isset(self::$_previous[$var]) ? self::$_previous[$var] : null;
    }

    /**
    * Add specific variable to the flash. This variable will be available on the
    * next page unless removed with the removeVariable() or clear() method
    *
    * @param string $var Variable name
    * @param mixed $value Variable value
    * @return void
    */
    public static function set($var, $value)
    {
        $_SESSION[self::SESSION_KEY][$var] = $value;
    } // set

    /**
    * Call this function to clear flash. Note that data that previous page
    * stored will not be deleted - just the data that this page saved for
    * the next page
    *
    * @param none
    * @return void
    */
    public static function clear()
    {
        $_SESSION[self::SESSION_KEY] = array();
    } // clear

    /**
    * This function will read flash data from the $_SESSION variable
    * and load it into $this->previous array
    *
    * @param none
    * @return void
    */
    public static function init()
    {
        // Get flash data...
        if( !empty($_SESSION[self::SESSION_KEY]) && is_array($_SESSION[self::SESSION_KEY]) )
            self::$_previous = $_SESSION[self::SESSION_KEY];

        $_SESSION[self::SESSION_KEY] = array();
    }

}
