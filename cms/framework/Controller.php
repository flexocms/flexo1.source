<?php

/**
 * The Controller class should be the parent class of all of your Controller sub classes
 * that contain the business logic of your application (render a blog post, log a user in,
 * delete something and redirect, etc).
 *
 * In the Frog class you can define what urls / routes map to what Controllers and
 * methods. Each method can either:
 *
 * - return a string response
 * - redirect to another method
 */
class Controller
{
    protected $layout = false;
    protected $layout_vars = array();
    
    public function execute($action, $params)
    {
        // it's a private method of the class or action is not a method of the class
        if (substr($action, 0, 1) == '_' || ! method_exists($this, $action)) {
            throw new Exception("Action '{$action}' is not valid!");
        }
        call_user_func_array(array($this, $action), $params);
    }
    
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    
    public function assignToLayout($var, $value)
    {
        if (is_array($var)) {
            array_merge($this->layout_vars, $var);
        } else {
            $this->layout_vars[$var] = $value;
        }
    }
    
    public function render($view, $vars=array())
    {
        if ($this->layout) {
            $this->layout_vars['content_for_layout'] = new View($view, $vars);
            return new View('../layouts/'.$this->layout, $this->layout_vars);
        } else {
            return new View($view, $vars);
        }
    }
    
    public function display($view, $vars=array(), $exit=true)
    {
        echo $this->render($view, $vars);
        
        if ($exit) exit;
    }

    public function renderJSON($data_to_encode)
    {
        if( function_exists('json_encode') )
            return json_encode($data_to_encode);
        elseif( class_exists('JSON') )
            return JSON::encode($data_to_encode);
        else
            throw new Exception('No function or class found to render JSON.');
    }
    
}
