<?php
/**
 * Class for handling CSS.
 *
 * @author Petr Snobl
 * @version 1.0
 * @uses Kohana_Config
 *
 */
class Css extends Filemerge{

    protected $config_key = 'css';

    public function render($compress = false) {
        $result = parent::render($compress);

        if($compress) {
            $result = cssmin::minify($result);
        }

        return $result;
    }

    /**
     * Frontend print css
     * @return Core_Css
     */
    public static function fePrint()
    {
        return self::instance('print');
    }

    /**
     * Backend instance of print
     * @return Core_Css
     */
    public static function bePrint()
    {
        return self::instance('backend_print');
    }

    /**
     * Renders the style for <style> tag.
     * @param string $attributes - full media type
     * @return type
     */
    public function renderInline($attributes = ''){
        $res = $this->render();
        return "<style type=\"text/css\" $attributes>$res</style>";
    }

}