<?php

class InstapagePage extends instapage
{
	const RANDOM_PREFIX = 'random-url-';
	const RANDOM_SUFIX_SET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const RANDOM_SUFIX_LENGTH = 10;

	var $page_stats;

	public function init()
	{
		add_filter( 'the_posts', array( &$this, 'checkCustomUrl' ), 1 );
		add_action( 'init', array( &$this, 'isProxy' ), 1 );
		add_action( 'wp', array( &$this, 'checkRoot' ), 1 );
		add_action( 'template_redirect', array( &$this, 'check404Page' ), 1 );
		add_action( 'init', array( &$this, 'refreshPageScreenshot' ), 1 );
	}

	public function isProxy()
	{
		if( self::getInstance()->includes[ 'service' ]->isServicesRequest() )
		{
			try
			{
				self::getInstance()->includes[ 'service' ]->processProxyServices();

				return;
			}
			catch( InstapageApiCallException $e )
			{
				InstapageIO::addNotice( __( 'Proxy service error: ' ) . $e->getMessage(), 'error' );
			}
		}
	}

	public function parseRequest()
	{
		$posts = $this->getAllPosts();

		if ( !is_array( $posts ) )
		{
			InstapageIO::writeDiagnostics( '!is_array( $posts ) - parseRequest not executed' );

			return false;
		}

		// get current url
		$current = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
		InstapageIO::writeDiagnostics( $current, 'current url' );

		// calculate the path
		$pattern = '/^www\./';
		$home_url = preg_replace( $pattern, '', str_replace( array( 'https://', 'http://' ), '', home_url() ) );
		$current = preg_replace( $pattern, '', $current );
		$part = substr( $current, strlen( $home_url ) );
		$part = rtrim( $part, '/' );

		if ( substr( $part, 0, 1 ) === '/' )
		{
			$part = substr( $part, 1 );
		}

		if ( strpos( $part, '?' ) !== false )
		{
			$part = explode( '?', $part );
			$part = $part[0];
		}

		$part = trim( $part, '/' );
		InstapageIO::writeDiagnostics( $part, 'part' );

		if ( array_key_exists( $part, $posts ) )
		{
			if ( $part == '' )
			{
				InstapageIO::writeDiagnostics( '$part == false - parseRequest not executed' );

				return false;
			}

			return $posts[ $part ];
		}

		return false;
	}

	public static function getFrontInstapage()
	{
		$v = get_option( 'instapage_front_page_id', false );
		return ( $v == '' ) ? false : $v;
	}

	public static function get404Instapage()
	{
		$v = get_option( 'instapage_404_page_id', false );
		return ( $v == '' ) ? false : $v;
	}

	public function isFrontPage( $id )
	{
		$front = $this->getFrontInstapage();
		return ($id == $front && $front !== false);
	}

	public function is404Page( $id )
	{
		$not_found = $this->get404Instapage();
		return ( $id == $not_found && $not_found !== false );
	}

	public function isLoginPage()
	{
		$pagenow = InstapageIO::getVar( 'pagenow', 'undefined', 'globals' );

		return in_array( $pagenow, array( 'wp-login.php', 'wp-register.php' ) );
	}

	public function getMyPosts()
	{
		global $wpdb;

		$sql = "SELECT {$wpdb->posts}.ID, {$wpdb->postmeta}.meta_key, {$wpdb->postmeta}.meta_value FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id) WHERE ({$wpdb->posts}.post_type = %s) AND ({$wpdb->posts}.post_status = 'publish') AND ({$wpdb->postmeta}.meta_key IN ('instapage_my_selected_page', 'instapage_name', 'instapage_my_selected_page', 'instapage_slug'))";

		$rows = $wpdb->get_results( $wpdb->prepare( $sql, 'instapage_post' ) );
		$posts = array();

		foreach ( $rows as $k => $row )
		{
			if ( !array_key_exists( $row->ID, $posts ) )
			{
				$posts[ $row->ID ] = array();
			}

			$posts[ $row->ID ][ $row->meta_key ] = $row->meta_value;
		}

		return $posts;
	}

	public function getAllPosts()
	{
		if ( $this->posts === false )
		{
			$front = $this->getFrontInstapage();
			$p = $this->getMyPosts();
			$res = array();

			foreach ( $p as $k => $v )
			{
				if ( $front == $k )
				{
					continue;
				}

				$res[ $v[ 'instapage_slug' ] ] = array
				(
					'id' => $v[ 'instapage_my_selected_page' ],
					'name' => $v[ 'instapage_name' ]
				);
			}

			$this->posts = $res;
		}

		return $this->posts;
	}

	public function checkCustomUrl( $posts )
	{
		global $post, $wpdb;

		InstapageIO::writeDiagnostics( $_SERVER[ 'REQUEST_URI' ], 'Request URI' );

		if( is_admin() || $this->isLoginPage() )
		{
			InstapageIO::writeDiagnostics( 'is_admin || isLoginPage - checkCustomUrl not executed' );

			return $posts;
		}

		if( self::getInstance()->includes[ 'service' ]->isServicesRequest() )
		{
			InstapageIO::writeDiagnostics( 'processProxyServices executed' );

			try
			{
				self::getInstance()->includes[ 'service' ]->processProxyServices();
			}
			catch( InstapageApiCallException $e )
			{
				InstapageIO::addNotice( __( 'Proxy service error. Request cannot be executed.' ), 'error' );
			}
		}

		if( isset( $_GET[ 'instapage_post' ] ) )
		{
			// draft mode
			$post_id = $wpdb->get_var( "SELECT ID FROM `{$wpdb->posts}` WHERE post_name = '". $wpdb->escape( $_GET[ 'instapage_post' ] ) ."' LIMIT 1" );
			$instapage_id = get_post_meta( (int)  $post_id, 'instapage_my_selected_page' );
			$page_html = self::getInstance()->includes[ 'api' ]->getPageHtml( $instapage_id[ 0 ] );
			$html = $page_html[ 'body' ];
		}
		else
		{
			// Determine if request should be handled by this plugin
			$requested_page = $this->parseRequest();

			if( $requested_page == false )
			{
				InstapageIO::writeDiagnostics( '$requested_page == false - checkCustomUrl not executed' );

				return $posts;
			}

			$page_html = self::getInstance()->includes[ 'api' ]->getPageHtml( $requested_page[ 'id' ] );
			$html = $page_html[ 'body' ];
		}

		InstapageIO::writeDiagnostics( 'checkCustomUrl executed' );

		if ( ob_get_length() > 0 )
		{
			ob_end_clean();
		}

		status_header( $page_html[ 'status' ] );

		header( 'Access-Control-Allow-Origin: *' );

		print $html;
		die();
	}

	public function loadMyPages()
	{
		static $this_plugin;

		if ( self::getInstance()->my_pages === false )
		{
			$response = self::getInstance()->includes[ 'api' ]->instapageApiCall
			(
				'my-pages',
				array
				(
					'user_id' => get_option( 'instapage.user_id' ),
					'plugin_hash' => get_option( 'instapage.plugin_hash' )
				)
			);

			if( !$response )
			{
				throw new Exception( 'Error connecting to Instapage' );
			}

			if( $response->error )
			{
				if( $response->error_message == 'User not found' )
				{
					$response->error_message .= '. Please <a href="' . INSTAPAGE_PLUGIN_SETTINGS_URI . '">relogin</a>';
				}

				throw new Exception( $response->error_message );
			}

			$pages = array();
			$pages_array_response = $response->data[ 'pages' ];

			if( $pages_array_response )
			{
				foreach( $pages_array_response as $page_array )
				{
					$page = new stdClass();

					foreach( $page_array as $key => $value )
					{
						$page->$key = $value;
					}

					$pages[] = $page;
				}
			}

			$this->my_pages = $pages;
		}

		return $this->my_pages;
	}

	public static function set404Instapage( $id )
	{
		update_option( 'instapage_404_page_id', $id );
	}

	public function getMyPage( $id )
	{
		try
		{
			if( $this->loadMyPages() )
			{
				foreach( $this->loadMyPages() as $page )
				{
					if( $page->id == $id )
					{
						return $page;
					}
				}
			}
		}
		catch( Exception $e )
		{
			echo $e->getMessage();

			if( is_admin() )
			{
				InstapageIO::addNotice( __( 'Page cannot be retrieved.' ) . ' ' . $e->getMessage() , 'error' );
			}
		}

		return false;
	}

	public function checkRoot()
	{

		InstapageIO::writeDiagnostics( $_SERVER[ 'REQUEST_URI' ], 'Request URI' );

		if( is_admin() || $this->isLoginPage() )
		{
			InstapageIO::writeDiagnostics( 'is_admin || isLoginPage - checkRoot not executed' );
			return;
		}

		// current for front page override
		$front = $this->getFrontInstapage();

		if ( $front === false )
		{
			InstapageIO::writeDiagnostics( 'getFrontInstapage is empty - checkRoot not executed' );

			return;
		}

		// check if WP search results page OR praview page should be displayed instead of landing page
		$excluded_params = array
		(
			's' => null,
			'post_type' => null,
			'preview' => 'true'
		);

		$custom_params_option = explode( '|', stripslashes( get_option( 'instapage.custom_params' ) ) );
		$custom_params = array();
		$param_arr = null;
		$key = null;
		$value = null;

		foreach( $custom_params_option as $param )
		{
			$param_arr = explode( '=', $param );
			$key = isset( $param_arr[ 0 ] ) ? $param_arr[ 0 ] : null;
			$value = isset( $param_arr[ 1 ] ) ? str_replace( '"', '', $param_arr[ 1 ] ) : null;
			$custom_params[ $key ] = $value;
		}

		if( count( $custom_params ) )
		{
			$excluded_params = array_merge( $excluded_params, $custom_params );
		}

		foreach( $excluded_params as $key => $value )
		{
			if(
				( $key !== null && $value == null && isset( $_GET[ $key ] )  ) ||
				( $key !== null && $value !== null && isset( $_GET[ $key ] ) && $_GET[ $key ] == $value )
			)
			{
				InstapageIO::writeDiagnostics( 'selected GET params are not empty - checkRoot not executed' );
				return;
			}
		}

		$home_url = str_replace( array( 'http://', 'https://' ), '', rtrim( get_home_url(), '/' ) );
		$home_url_segments = explode( '/', $home_url );
		$uri_segments = explode( '?', $_SERVER[ 'REQUEST_URI' ] );
		$uri_segments = explode( '/', rtrim( $uri_segments[0], '/' ) );
		InstapageIO::writeDiagnostics( $home_url_segments, 'home_url_segments' );
		InstapageIO::writeDiagnostics( $uri_segments, 'uri_segments' );

		if ( count( $uri_segments ) == count( $home_url_segments ) )
		{
			if ( count( $home_url_segments ) > 1 && $home_url_segments[1] != $uri_segments[1] )
			{
				InstapageIO::writeDiagnostics( 'count( $home_url_segments ) > 1 && $home_url_segments[1] != $uri_segments[1] - checkRoot not executed' );

				return false;
			}

			if ( $front !== false )
			{
				$mp = $this->getPageById( $front );

				if ( $mp !== false && $mp->post_status == 'publish' )
				{
					// get and display the page at root
					$page_html = self::getInstance()->includes[ 'api' ]->getPageHtml( $mp->lp_id );
					InstapageIO::writeDiagnostics( 'checkRoot executed' );
					$html = $page_html[ 'body' ];

					if ( ob_get_length() > 0 )
					{
						ob_end_clean();
					}

					// flush previous cache
					if ( !substr_count( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ], 'gzip' ) )
					{
						ob_start();
					}

					status_header( $page_html[ 'status' ] );
					print $html;
					ob_end_flush();
					die();
				}
			}
		}
	}

	public function getPageById( $post_id )
	{
		$res = get_post( $post_id );

		if ( empty( $res ) )
		{
			return false;
		}

		$url = get_post_meta( $post_id, 'instapage_url', true );
		$slug = get_post_meta( $post_id, 'instapage_slug', true );
		$id = get_post_meta( $post_id, 'instapage_my_selected_page', true );
		$res->lp_id = $id;
		$res->lp_url = $url;
		$res->slug = $slug;

		return $res;
	}

	public function displayCustom404( $id_404 )
	{
		// show the instapage
		$mp = $this->getPageById( $id_404 );

		if ( $mp !== false && $mp->post_status == 'publish' )
		{
			$page_html = self::getInstance()->includes[ 'api' ]->getPageHtml( $mp->lp_id );
			$html = $page_html[ 'body' ];

			if ( ob_get_length() > 0 )
			{
				ob_end_clean();
			}

			status_header( '404' );
			print $html;
			die();
		}

		return;
	}

	public function check404Page()
	{
		InstapageIO::writeDiagnostics( $_SERVER[ 'REQUEST_URI' ], 'Request URI' );
		$id_404 = $this->get404Instapage();

		if( $id_404 === false )
		{
			InstapageIO::writeDiagnostics( 'get404Instapage == false - check404Page not executed' );

			return;
		}

		if( is_404() && !is_admin() && !$this->isLoginPage() )
		{
			InstapageIO::writeDiagnostics( 'check404Page executed' );
			$this->displayCustom404( $id_404 );
		}
	}

	public function getPageUrl( $post_id, $instapage_slug = null )
	{
		if( !$post_id && $instapage_slug !== null )
		{
			$path = esc_html( $instapage_slug );
		}
		else
		{
			$path = esc_html( get_post_meta( $post_id, 'instapage_slug', true ) );
		}

		return site_url() . '/' . $path;
	}

	public function getRandomSlug( $add_prefix = true )
	{
		$randomString = '';
		$random_sufix_set = self::RANDOM_SUFIX_SET;

		for ( $i = 0; $i < self::RANDOM_SUFIX_LENGTH; $i++ )
		{
			$randomString .= $random_sufix_set[ rand( 0, strlen( $random_sufix_set ) - 1 ) ];
		}

		return $add_prefix ? self::RANDOM_PREFIX . $randomString : $randomString;
	}

	public function getPageEditUrl( $post_id )
	{
		return get_edit_post_link( $post_id );
	}

	public function getPageScreenshot( $post_id )
	{
		$page_screenshot_url = get_post_meta( $post_id, 'instapage_page_screenshot_url', true );
		$page_screenshot_url_parts = parse_url( $page_screenshot_url );
		parse_str( $page_screenshot_url_parts[ 'query' ], $page_screenshot_url_query );

		if ( empty( $page_screenshot_url_query ) )
		{
			$page_screenshot_url = urldecode( $page_screenshot_url );
		}

		return $page_screenshot_url;
	}

	public function refreshPageScreenshot()
	{
		$current = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
		$part = substr( $current, strlen( site_url() ) );

		if( strpos( $part, 'instapage_refresh_screenshot' ) === false )
		{
			return false;
		}

		$post_keys = array( 'page_id', 'page_screenshot_url', 'plugin_hash' );

		foreach( $post_keys as $post_key )
		{
			if ( !isset( $_POST[ $post_key ] ) || empty( $_POST[ $post_key ] ) )
			{
				return false;
			}
		}

		if ( $_POST[ 'plugin_hash' ] != get_option( 'instapage.plugin_hash' ) )
		{
			return false;
		}

		self::getInstance()->includes[ 'edit' ]->updateMetaValueByInstapagePageId( $_POST[ 'page_id' ], 'instapage_page_screenshot_url', $_POST[ 'page_screenshot_url' ] );

		die( json_encode( array( 'Page screenshot updated' ) ) );
	}

	public function getPageName( $post_id )
	{
		return get_post_meta( $post_id, 'instapage_name', true );
	}

	public function calcAvarageConversion( $visits, $conversions )
	{
		$average_conversion_rate = ( $visits > 0 ) ? round( ( $conversions / $visits ) * 100, 1 ) : 0;

		if ( $average_conversion_rate > 100 )
		{
			$average_conversion_rate = 100;
		}

		return $average_conversion_rate;
	}

	public function getPageStats( $post_id )
	{
		if ( isset( $this->page_stats[ $post_id ] ) )
		{
			return $this->page_stats[ $post_id ];
		}

		$page = $this->getPageById( $post_id );

		if ( !$page )
		{
			return false;
		}

		$data = array
		(
			'page_id' => $page->lp_id
		);

		$stats = self::getInstance()->includes[ 'api' ]->instapageApiCall
		(
			'my_stats/' . get_option( 'instapage.user_id' ) . '/' . get_option( 'instapage.plugin_hash' ),
			$data
		);

		if ( !$stats->stats )
		{
			return false;
		}

		$stats = $stats->stats;

		$variant_defaults = array
		(
			'visits' => 0,
			'conversions' => 0,
			'conversion_rate' => 0,
			'class' => ''
		);

		$page_stats = array
		(
			'visits' => $stats[ 'stats' ][ 'visits' ],
			'conversions' => $stats[ 'stats' ][ 'conversions' ],
			'conversion_rate' => $this->calcAvarageConversion( $stats[ 'stats' ][ 'visits' ], $stats[ 'stats' ][ 'conversions' ] ),
			'variants' => array()
		);

		$max = 0;
		$min = 999999;
		$max_variant_name = false;
		$min_variant_name = false;
		$zero_visits_added = false;

		if ( empty( $stats[ 'all_variants' ] ) )
		{
			return false;
		}

		foreach( $stats[ 'all_variants' ] as $variant_name )
		{
			$page_stats[ 'variants' ][ $variant_name ] = $variant_defaults;

			if ( !isset( $stats[ 'stats' ][ 'variant' ][ $variant_name ] ) )
			{
				$page_stats[ 'variants' ][ $variant_name ][ 'class' ] .= ' red';
				$zero_visits_added = true;
				continue;
			}

			$page_stats[ 'variants' ][ $variant_name ] = $stats[ 'stats' ][ 'variant' ][ $variant_name ];
			$page_stats[ 'variants' ][ $variant_name ][ 'conversion_rate' ] = $this->calcAvarageConversion( $stats[ 'stats' ][ 'variant' ][ $variant_name ][ 'visits' ], $stats[ 'stats' ][ 'variant' ][ $variant_name ][ 'conversions' ] );

			if ( $page_stats[ 'variants' ][ $variant_name ][ 'conversion_rate' ] > $max )
			{
				$max = $page_stats[ 'variants' ][ $variant_name ][ 'conversion_rate' ];
				$max_variant_name = $variant_name;
			}

			if ( $page_stats[ 'variants' ][ $variant_name ][ 'conversion_rate' ] < $min )
			{
				$min = $page_stats[ 'variants' ][ $variant_name ][ 'conversion_rate' ];
				$min_variant_name = $variant_name;
			}

			if ( $stats[ 'stats' ][ 'variant' ][ $variant_name ][ 'paused' ] )
			{
				$page_stats[ 'variants' ][ $variant_name ][ 'class' ] = 'paused';
			}
		}

		if ( $min_variant_name && !$zero_visits_added )
		{
			$page_stats[ 'variants' ][ $min_variant_name ][ 'class' ] .= ' red';
		}

		if ( $max_variant_name )
		{
			$page_stats[ 'variants' ][ $max_variant_name ][ 'class' ] = str_replace( 'red', '', $page_stats[ 'variants' ][ $max_variant_name ][ 'class' ] );
			$page_stats[ 'variants' ][ $max_variant_name ][ 'class' ] .= ' green';
		}

		foreach( $page_stats[ 'variants' ] as $variant_name => $variant )
		{
			if ( $min_variant_name )
			{
				if ( $variant_name !== $min_variant_name && $variant[ 'conversion_rate' ] === $page_stats[ 'variants' ][ $min_variant_name ][ 'conversion_rate' ] && !$zero_visits_added )
				{
					$page_stats[ 'variants' ][ $variant_name ][ 'class' ] .= ' red';
				}
			}

			if ( $max_variant_name )
			{
				if ( $variant_name !== $max_variant_name && $variant[ 'conversion_rate' ] === $page_stats[ 'variants' ][ $max_variant_name ][ 'conversion_rate' ] )
				{
					$page_stats[ 'variants' ][ $variant_name ][ 'class' ] .= ' green';
				}
			}
		}

		$this->page_stats[ $post_id ] = $page_stats;
		return $page_stats;
	}

	public function getPostIdsByInstapagePageId( $instapage_page_id )
	{
		global $wpdb;

		if ( empty( $instapage_page_id ) )
		{
			return false;
		}

		$instapage_page_id = $wpdb->escape( $instapage_page_id );

		$post_ids = $wpdb->get_results( "select post_id from {$wpdb->postmeta} where meta_key = 'instapage_my_selected_page' and meta_value = '$instapage_page_id'" );

		if ( !empty( $post_ids ) )
		{
			return $post_ids;
		}

		return false;
	}
}