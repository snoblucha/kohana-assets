<?php

/**
 * Description of scripts
 *
 * $GLOBALS['scripts_data'] will be passed into javascript file
 *
 * @author snb
 * @property Request $request
 * @property Cache $cache
 * @property boolean $cached
 * @property string $cachekey
 */
class Controller_Scripts extends Controller {

	public $compress = false;
	public $cache;
	public $cached;
	public $cachekey;

	public function before() {

		//parent::before();
		$this->cached = Kohana::$config->load( 'main.scripts-cached' );
		$this->response->headers( 'Content-type', 'text/javascript; charset: UTF-8;' );
		//$this->request->headers['Cache-Control'] = 'max-age;';

		if ( $this->cached ) {
			if ( !$this->cachekey ) {
				$this->cachekey = $_SERVER['SERVER_NAME'] . '/' . $this->request->uri();
			}

			$offset = Date::HOUR;
			$this->request->headers( 'Expires', gmdate( "D, d M Y H:i:s", time() + $offset ) . " GMT" );

			$scripts = Cache::instance( Kohana::$config->load( 'main.cache-driver' ) )->get( $this->cachekey );

			if ( $scripts || is_string( $scripts ) ) {
				$this->response->body( (string) $scripts . ' ' );
				$this->check_cache( $this->response->generate_etag() );
				$this->request->action( 'dummy' );
			}
		}
	}

	public function after() {
		if ( $this->cached ) {
			Cache::instance( Kohana::$config->load( 'main.cache-driver' ) )->set( $this->cachekey, $this->response->body(), 2 * Date::HOUR );
			$this->response->headers( 'etag', $this->response->generate_etag() );
		}
	}

	public function action_index() {
		$fields = $this->request->param( 'id', 'frontend' );
		$script = JS::instance( $fields )->render();
		$this->response->body( $script ); //$this->render($scripts));
	}

	public function action_dummy() {

	}


}
