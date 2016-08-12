<?php

/**
 * Class for merging files.
 *
 * @author Petr Snobl
 * @version 1.0
 * @uses Kohana_Config
 *
 */
class Filemerge {

    protected $config_key = 'merge';
    protected $files = array();
    protected $key;
    protected $params = array();
    protected $instance_config;
    protected static $instances = array();
    protected $config = null;

    protected $dirs = array(

    );

    /**
     * Get the instance - default keys are 'frontend', 'backend'
     * @param string $key
     * @return Filemerge
     */
    public static function instance($key)
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class][$key]))
        {

            self::$instances[$class][$key] = new $class($key);
        }

        return self::$instances[$class][$key];
    }

    public function __construct($tag)
    {

        $this->config = Kohana::$config->load($this->config_key);
        $this->tag = $tag;
        $this->instance_config = array_merge(Arr::get($this->config, 'default', array()), Arr::get($this->config, $tag, array()));
        $dir = Arr::get($this->instance_config, 'dir');
        $this->dirs[] = DOCROOT . $dir;
        $this->dirs[] = APPPATH . $dir;
        $this->dirs[] = MODPATH .'core/'. $dir;

    }

    /**
     *
     * @return array directories to search
     */
    public function getDirectories()
    {
       return $this->dirs;
    }

    /**
     * Add dir to search path
     * @param string $directory absolute path to search for file
     * @return \Filemerge
     */
    public function addDir($directory)
    {
        $this->dirs[] = $directory;
        return $this;
    }

    /**
     * Find the file, from config parameters
     * @param string $file
     * @return string
     * @throws Kohana_Exception
     */
    protected function findFile($file)
    {

        foreach ($this->getDirectories() as $dir)
        {
            
            if (file_exists($dir . $file))
            {
                return $dir . $file;
            }
        }

        throw new Kohana_Exception('File :file not found', array(':file' => $file));
    }

    /**
     * Add an array of files
     * @param array $files
     */
    public function addFiles(array $files)
    {
        foreach ($files as $key => $file)
        {
            if (is_numeric($key))
            {
                $this->add($file);
            }
            else
            {
                $this->add($file, $tag);
            }
        }
    }

    /**
     * Add file to the stack, optionally tag it
     * @param string $file
     * @param string $tag Tag can not be prepended
     * @param boolean $prepend Prepend file instead of appending
     * @return Filemerge
     * @throws Kohana_Exception
     */
    public function add($file, $tag = null, $prepend = false)
    {

        if ($file === null || is_array($file) || is_object($file))
        {
            throw new Kohana_Exception('Illegal argument :argument. String expected.', array('argument' => Debug::dump($file)));
        }

        if ($this->index($file) !== false)
        {
            return $this; //already there
        }

        if ($tag)
        {
            $this->files[$tag] = $file;
        }
        else
        {
            if ($prepend)
            {
                array_unshift($this->files, $file);
            }
            else
            {
                array_push($this->files, $file);
            }
        }
        return $this;
    }

    /**
     * Get the keys of added files
     * @return array
     */
    public function keys()
    {
        return array_keys($this->files);
    }

    /**
     * Find the index of the files
     * @param type $file
     * @return int/string
     */
    public function index($file)
    {
        return array_search($file, $this->files);
    }

    /**
     * Remove file from stack
     * @param mixed $position - index in the array (int or tag)
     * @return \Filemerge
     */
    public function remove($position)
    {
        if (isset($this->files[$position]))
        {
            unset($this->files[$position]);
        }
        return $this;
    }

    /**
     * Clears the stack
     * @return \Filemerge
     */
    public function clear()
    {
        $this->files = array();
        return $this;
    }

    /**
     * Backend instance
     * @return Filemerge
     */
    public static function be()
    {
        return self::instance('backend');
    }

    /**
     * Frontend instance
     * @return Filemerge
     */
    public static function fe()
    {
        return self::instance('frontend');
    }

    /**
     * Set the parameters - will be replaced in file -  #key to value
     * @param string $key
     * @param string $value
     */
    protected function param($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Set the params
     * @param array $params
     * @return Filemerge
     */
    public function params(array $params)
    {
        foreach ($params as $key => $value)
        {
            $this->param($key, $value);
        }
        return $this;
    }

    /**
     * Render the Merge the file
     * @return string
     */
    public function render($compress = false)
    {
        $result = '';
        $replace = array();

        //prepare replacement pairs
        foreach ($this->params as $key => $val)
        {
            if (is_array($val))
            {
                foreach ($val as $subkey => $value)
                {
                    $replace["#$key#$subkey"] = $value;
                }
            }
            else
            {
                $replace["#$key"] = $val;
            }
        }


        foreach ($this->files as $file)
        {
            ob_start();
            include $this->findFile($file);
            $result .= ob_get_clean() . "\n\n";
        }

        $result = trim(strtr($result, $replace)); //replace params
        if ($compress)
        {

        }
        return $result;
    }

    /**
     * Check if the instance is empty
     * @return boolean
     */
    public function isEmpty()
    {
        return count($this->files) == 0;
    }

}