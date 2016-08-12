<?php

/**
 * Css controller class to join and handle CSS.
 *
 * @author Petr Snobl
 * @property Request $request
 * @property Cache $cache
 */
class Controller_Css extends Controller {

    public $compress = true;

    /**
     * Should be css file cached?
     * @var boolean
     */
    public $cache;
    public $cached;
    public $cachekey;
    protected $from_cache;

    public function before()
    {

        parent::before();
        $this->cached = Kohana::$config->load('main.css-cached');
        $this->response->headers('Content-type', 'text/css; charset: UTF-8;');
        //$this->request->headers['Cache-Control'] = 'max-age;';
        if ($this->cached)
        {
            if (!$this->cachekey)
            {
                $this->cachekey = Arr::get($_SERVER, 'SERVER_NAME') . '/' . $this->request->uri();
            }
            $offset = Date::DAY;
            $this->response->headers('Expires', gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
            $this->cache = Cache::instance(Kohana::$config->load('main.cache-driver'));
            $css = $this->cache->get($this->cachekey);

            if ($css)
            {
                $this->response->body((string)$css);

                $this->check_cache($this->response->generate_etag());
                $this->request->action('dummy');
            }
        }
    }

    /**
     * blank action, should not be matched
     */
    public function action_dummy()
    {

    }

    public function after()
    {
        if ($this->cached)
        {
            $this->cache->set($this->cachekey, $this->response->body(), 2 * Date::HOUR);
            $this->response->headers('etag', $this->response->generate_etag());
        }
    }

    public function action_index()
    {
        $this->response->body(Css::fe()->render());
    }

    public function action_admin()
    {
        $this->response->body(Css::be()->render());
    }

    public function action_print()
    {
        $this->response->body(Css::fePrint()->render());
    }

    public function action_get()
    {
        $name = $this->request->param('id');
        if ($name)
        {
            $instance = Css::instance($name);
            if (!$instance->isEmpty())
            {
                $this->response->body($instance->render());
            }
            else
            {
                Throw new HTTP_Exception_404("Css [:name] not found", array(':name' => $name));
            }
        }
        else
        {
            Throw new HTTP_Exception_404("Unknown CSS");
        }
    }


}