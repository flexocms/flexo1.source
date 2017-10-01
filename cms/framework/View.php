<?php

/**
 * The template object takes a valid path to a template file as the only argument
 * in the constructor. You can then assign properties to the template, which
 * become available as local variables in the template file. You can then call
 * display() to get the output of the template, or just call print on the template
 * directly thanks to PHP 5's __toString magic method.
 * 
 * echo new View('my_template',array(
 *  'title' => 'My Title',
 *  'body' => 'My body content'
 * ));
 * 
 * my_template.php might look like this: 
 * 
 * <html>
 * <head>
 *  <title><?php echo $title;?></title>
 * </head>
 * <body>
 *  <h1><?php echo $title;?></h1>
 *  <p><?php echo $body;?></p>
 * </body>
 * </html>
 * 
 * Using view helpers:
 * 
 * use_helper('HelperName', 'OtherHelperName');
 */
class View
{
    private $file;           // String of template file
    private $vars = array(); // Array of template variables

    /**
    * Assign the template path
    *
    * @param string $file Template path (absolute path or path relative to the templates dir)
    * @return void
    */
    public function __construct($file, $vars=false)
    {
        $this->file = APP_PATH.'/views/'.ltrim($file, '/').'.php';
        
        if ( ! file_exists($this->file)) {
            throw new Exception("View '{$this->file}' not found!");
        }
        
        if ($vars !== false) {
            $this->vars = $vars;
        }
    }

    /**
    * Assign specific variable to the template
    *
    * @param mixed $name Variable name
    * @param mixed $value Variable value
    * @return void
    */
    public function assign($name, $value=null)
    {
        if (is_array($name)) {
            array_merge($this->vars, $name);
        } else {
            $this->vars[$name] = $value;
        }
    } // assign

    /**
    * Display template and return output as string
    *
    * @return string content of compiled view template
    */
    public function render()
    {
        ob_start();
        
        extract($this->vars, EXTR_SKIP);
        include $this->file;
        
        $content = ob_get_clean();
        return $content;
    }

    /**
    * Display the rendered template
    */
    public function display() {
        echo $this->render();
    }

    /**
    * Render the content and return it
    * ex: echo new View('blog', array('title' => 'My title'));
    *
    * @return string content of the view
    */
    public function __toString() {
        return $this->render();
    }

}
