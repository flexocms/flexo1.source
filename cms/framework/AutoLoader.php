<?php

/**
 * The AutoLoader class is an object oriented hook into PHP's __autoload functionality. You can add
 * 
 * - Single Files AutoLoader::addFile('Blog','/path/to/Blog.php');
 * - Multiple Files AutoLoader::addFile(array('Blog'=>'/path/to/Blog.php','Post'=>'/path/to/Post.php'));
 * - Whole Folders AutoLoader::addFolder('path');
 *
 * When adding a whole folder each file should contain one class named the same as the file without ".php" (Blog => Blog.php)
 */
class AutoLoader
{
    protected static $files = array();
    protected static $folders = array();
    
    /**
    * AutoLoader::addFile('Blog','/path/to/Blog.php');
    * AutoLoader::addFile(array('Blog'=>'/path/to/Blog.php','Post'=>'/path/to/Post.php'));
    * @param mixed $class_name string class name, or array of class name => file path pairs.
    * @param mixed $file Full path to the file that contains $class_name.
    */
    public static function addFile($class_name, $file=null)
    {
        if ($file == null && is_array($class_name))
            array_merge(self::$files, $class_name);
        else
            self::$files[$class_name] = $file;
    }
    
    /**
    * AutoLoader::addFolder('/path/to/my_classes/');
    * AutoLoader::addFolder(array('/path/to/my_classes/','/more_classes/over/here/'));
    * @param mixed $folder string, full path to a folder containing class files, or array of paths.
    */
    public static function addFolder($folder)
    {
        if ( ! is_array($folder))
            $folder = array($folder);
        
        self::$folders = array_merge(self::$folders, $folder);
    }
    
    public static function load($class_name)
    {
        if (isset(self::$files[$class_name]))
        {
            if (file_exists(self::$files[$class_name]))
            {
                require self::$files[$class_name];
                return;
            }
        }
        else
        {
            foreach (self::$folders as $folder)
            {
                $folder = rtrim($folder, DIRECTORY_SEPARATOR);
                $file = $folder.DIRECTORY_SEPARATOR.$class_name.'.php';
                
                if (file_exists($file))
                {
                    require($file);
                    return;
                }
            }
        }
        
        /*if( DEBUG )
            throw new Exception("AutoLoader did not find file for '{$class_name}'!");
        else*/
            return false;
    }
    
}
