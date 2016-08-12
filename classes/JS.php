<?php

class JS extends Filemerge {
	protected $config_key = 'script';

	public function renderInline() {
		$res = $this->render();
		return '<script type="text/javascript">' . $res . '</script>';
	}

	/**
	 * Scripts at footer
	 * @return Filemerge
	 */
	public static function footer() {
		return self::instance( 'footer' );
	}

}