<?php
/*
Plugin Name: Instapage
Description: Instapage Wordpress Plugin
Version: 2.33
Plugin URI: http://www.instapage.com/
Author: instapage
Author URI: http://www.instapage.com/
License: GPLv2
*/

define( 'INSTAPAGE_PLUGIN_CLASS_NAME', 'instapage' );
define( 'INSTAPAGE_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'INSTAPAGE_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'INSTAPAGE_ACF_USER_GROUP', 46 );
define( 'INSTAPAGE_PLUGIN_SETTINGS_URI', 'options-general.php?page=instapage/instapage.php' );
define( 'INSTAPAGE_PLUGIN_FILE', __FILE__ );
define( 'INSTAPAGE_ADMIN_URL', INSTAPAGE_PLUGIN_URI . 'assets/' );

function files_to_include()
{
	$files_to_include = array
	(
		'admin',
		'api',
		'edit',
		'index',
		'main',
		'page',
		'service',
		'view',
		'log'
	);

	return $files_to_include;
}

class instapage
{
	protected $_vars = array();
	protected static $_instance;

	const wp_version_required = '3.4';
	const php_version_required = '5.2';
	const endpoint = 'http://app.instapage.com';
	const cached_service_lifetime = 86400;
	const instapage_support_link = 'https://help.instapage.com';
	const instapage_dashboard_link = 'https://app.instapage.com/dashboard';

	protected $my_pages = false;
	protected $plugin_details = false;
	protected $posts = false;
	protected $message = false;

	public static function getInstance()
	{
		if ( !isset( self::$_instance ) )
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function &__get( $name )
	{
		return $this->_vars[ $name ];
	}

	public function __set( $name, $value )
	{
		$this->_vars[ $name ] = $value;
	}

	public function init()
	{
		$this->include_required();
		$compat_status = self::getInstance()->includes[ 'service' ]->compatibility();

		if( function_exists( 'w3tc_fragmentcache_flush_group' ) )
		{
			w3tc_fragmentcache_flush_group( 'instapage' );
		}

		if ( $compat_status !== true )
		{
			self::getInstance()->includes[ 'admin' ]->showMessage( false, $compat_status );
			return;
		}

		if ( get_option( 'permalink_structure' ) == '' )
		{
			self::getInstance()->includes[ 'admin' ]->showMessage
			(
				false,
				__( 'instapage plugin needs <a href="options-permalink.php">permalinks</a> enabled!', 'instapage' )
			);

			return;
		}

		if( is_admin() )
		{
			self::getInstance()->includes[ 'service' ]->init();
			self::getInstance()->includes[ 'main' ]->init();
			self::getInstance()->includes[ 'edit' ]->init();
			self::getInstance()->includes[ 'index' ]->init();
			self::getInstance()->includes[ 'admin' ]->init();
		}

		self::getInstance()->includes[ 'page' ]->init();
	}

	public function ajax()
	{
		global $current_user;

		if ( !isset( $_POST[ 'data' ] ) )
		{
			die(1);
		}

		$ajax_data = $_POST[ 'data' ];

		if ( !isset( $ajax_data[ 'action' ] ) || empty( $ajax_data[ 'action' ] ) || !isset( $ajax_data[ 'method' ] ) || empty( $ajax_data[ 'method' ] ) || !isset( $ajax_data[ 'params' ] ) )
		{
			die(2);
		}

		if ( !isset( self::getInstance()->includes[ $ajax_data[ 'action' ] ] ) || !method_exists( self::getInstance()->includes[ $ajax_data[ 'action' ] ], $ajax_data[ 'method' ] ) )
		{
			die(3);
		}

		$ajax_data[ 'params' ] = $this->_parse_params( $ajax_data[ 'params' ] );

		$result[ 'data' ] = self::getInstance()->includes[ $ajax_data['action'] ]->$ajax_data[ 'method' ]( $ajax_data[ 'params' ] );

		die( json_encode( $result ) );
	}

	public function shortcode( $atts )
	{
		global $current_user;

		$shortcode_atts = shortcode_atts
		(
			array
			(
				'action' => '',
				'method' => '',
				'params' => ''
			),
			$atts
		);

		$shortcode_atts[ 'params' ] = explode( ',', $shortcode_atts[ 'params' ] );

		if ( empty( $shortcode_atts[ 'action' ] ) || empty( $shortcode_atts[ 'method' ] ) )
		{
			return false;
		}

		$result = self::getInstance()->includes[ $shortcode_atts[ 'action' ] ]->$shortcode_atts[ 'method' ]( $shortcode_atts[ 'params' ] );

		return $result;
	}

	private function include_required()
	{
		$files_to_include = files_to_include();

		foreach( $files_to_include as $file_to_include )
		{
			require_once( INSTAPAGE_PLUGIN_DIR . '/includes/' . $file_to_include . '.php' );

			$class_name = 'Instapage' . str_replace( ' ', '', ucwords( str_replace( array( '-', '.php' ), array( ' ', '' ), $file_to_include ) ) );
			$class_name_short = strtolower( str_replace( 'Instapage', '', $class_name ) );
			$this->includes[ $class_name_short ] = new $class_name();
		}

		//include static helpers
		require_once( INSTAPAGE_PLUGIN_DIR . '/includes/helpers.php' );
	}
}

$instapage = instapage::getInstance();
$instapage->init();
