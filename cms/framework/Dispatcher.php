<?php

if (!defined('DEFAULT_CONTROLLER')) define('DEFAULT_CONTROLLER', 'index');
if (!defined('DEFAULT_ACTION'))     define('DEFAULT_ACTION', 'index');

/**
 * The Dispatcher main Core class is responsible for mapping urls/routes to Controller methods.
 * 
 * Each route that has the same number of directory components as the current
 * requested url is tried, and the first method that returns a response with a
 * non false/non null value will be returned via the Dispatcher::dispatch() method.
 *
 * For example:
 *
 * A route string can be a literal url such as '/pages/about' or can contain
 * wildcards (:any or :num) and/or regex like '/blog/:num' or '/page/:any'.
 *
 * <code>Dispatcher::addRoute(array(
 *  '/' => 'page/index',
 *  '/about' => 'page/about,
 *  '/blog/:num' => 'blog/post/$1',
 *  '/blog/:num/comment/:num/delete' => 'blog/deleteComment/$1/$2'
 * ));</code>
 *
 * Visiting /about/ would call PageController::about(),
 * visiting /blog/5 would call BlogController::post(5)
 * visiting /blog/5/comment/42/delete would call BlogController::deleteComment(5,42)
 *
 * The dispatcher is used by calling Dispatcher::addRoute() to setup the route(s),
 * and Dispatcher::dispatch() to handle the current request and get a response.
 */
final class Dispatcher
{
    private static $routes = array();
    private static $params = array();
    private static $status = array();
    private static $requested_url = '';
    
    public static function addRoute($route, $destination=null)
    {
        if ($destination != null && !is_array($route)) {
            $route = array($route => $destination);
        }
        self::$routes = array_merge(self::$routes, $route);
    }
    
    public static function splitUrl($url)
    {
        return preg_split('/\//', $url, -1, PREG_SPLIT_NO_EMPTY);
    }
    
    public static function dispatch($requested_url = null, $default = null)
    {
        // If no url passed, we will get the first key from the _GET array
        // that way, index.php?/controller/action/var1&email=example@example.com
        // requested_url will be equal to: /controller/action/var1
        if ($requested_url === null)
        {
            $pos = strpos($_SERVER['QUERY_STRING'], '&');
            if ($pos !== false)
            {
                $requested_url = substr($_SERVER['QUERY_STRING'], 0, $pos);
            }
            else
            {
                $requested_url = $_SERVER['QUERY_STRING'];
            }
        }

        // If no URL is requested (due to someone accessing admin section for the first time)
        // AND $default is setAllow for a default tab
        if ($requested_url == null && $default != null)
        {
            $requested_url = $default;
        }
        
        // Requested url MUST start with a slash (for route convention)
        if (strpos($requested_url, '/') !== 0)
        {
            $requested_url = '/' . $requested_url;
        }
        
        self::$requested_url = $requested_url;
        
        // This is only trace for debugging
        self::$status['requested_url'] = $requested_url;
        
        // Make the first split of the current requested_url
        self::$params = self::splitUrl($requested_url);
        
        // Do we even have any custom routing to deal with?
        if (count(self::$routes) === 0)
        {
            return self::executeAction(self::getController(), self::getAction(), self::getParams());
        }
        
        // Is there a literal match? If so we're done
        if (isset(self::$routes[$requested_url]))
        {
            self::$params = self::splitUrl(self::$routes[$requested_url]);
            return self::executeAction(self::getController(), self::getAction(), self::getParams());
        }
        
        // Loop through the route array looking for wildcards
        foreach (self::$routes as $route => $uri)
        {
            // Convert wildcards to regex
            if (strpos($route, ':') !== false)
            {
                $route = str_replace(':any', '(.+)', str_replace(':num', '([0-9]+)', $route));
            }
            
            // Does the regex match?
            if (preg_match('#^'.$route.'$#', $requested_url))
            {
                // Do we have a back-reference?
                if (strpos($uri, '$') !== false && strpos($route, '(') !== false)
                {
                    $uri = preg_replace('#^'.$route.'$#', $uri, $requested_url);
                }
                self::$params = self::splitUrl($uri);
                // We found it, so we can break the loop now!
                break;
            }
        }
        
        return self::executeAction(self::getController(), self::getAction(), self::getParams());
    } // Dispatch
    
    public static function getCurrentUrl()
    {
        return self::$requested_url;
    }
    
    public static function getController()
    {
        // Check for settable default controller
        // if it's a plugin and not activated, revert to Frog hardcoded default
        if (isset(self::$params[0]) && self::$params[0] == 'plugin' )
        {
            $loaded_plugins = Plugin::$plugins;
            if (isset(self::$params[1]) && !isset($loaded_plugins[self::$params[1]]))
            {
                unset(self::$params[0]);
                unset(self::$params[1]);
            }
        }        

        return isset(self::$params[0]) ? self::$params[0]: DEFAULT_CONTROLLER;
    }
        
    public static function getAction()
    {
        return isset(self::$params[1]) ? self::$params[1]: DEFAULT_ACTION;
    }
    
    public static function getParams()
    {
        return array_slice(self::$params, 2);
    }
    
    public static function getStatus($key=null)
    {
        return ($key === null) ? self::$status: (isset(self::$status[$key]) ? self::$status[$key]: null);
    }
    
    public static function executeAction($controller, $action, $params)
    {
        self::$status['controller'] = $controller;
        self::$status['action'] = $action;
        self::$status['params'] = implode(', ', $params);
        
        $controller_class = Inflector::camelize($controller);
        $controller_class_name = $controller_class . 'Controller';
        
        // Get an instance of that controller
        if (class_exists($controller_class_name))
        {
            $controller_object = new $controller_class_name();
            
            if (!$controller_object instanceof Controller)
            {
                throw new Exception('Class '.$controller_class_name.' does not extends Controller class!');
            }

            // Execute the action
            $controller_object->execute($action, $params);
        }
        else
        {
            throw new Exception('Class '.$controller_class_name.' not found!');
        }
    }

}
