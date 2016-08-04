<?php

class InstapageApiCallException extends Exception {}

class InstapageApi extends instapage {

	public function instapageApiCall( $service, $data = null, $endpoint = '' )
	{
		if ( $service === null )
		{
			$service_parts = parse_url( $_SERVER[ 'REQUEST_URI' ] );
			parse_str( $url_parts[ 'query' ], $service_parts );

			if ( isset( $service_parts[ 'url' ] ) )
			{
				$service = $service_parts[ 'url' ];
			}
		}

		$url = self::endpoint . '/ajax/services/' . $endpoint . $service;
		InstapageIO::writeDiagnostics( $url, 'API call URL' );
		$current_ver = self::getInstance()->includes[ 'service' ]->pluginGet( 'Version' );

		$body = array
		(
			'service-type' => 'Wordpress',
			'service' => $_SERVER[ 'SERVER_NAME' ],
			'version' => $current_ver,
			'user_id' => get_option( 'instapage.user_id' ),
			'data' => $data
		);
		InstapageIO::writeDiagnostics( $body, 'API call message body' );

		$response = wp_remote_post
		(
			$url,
			array
			(
				'method' => 'POST',
				'timeout' => 70,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => $body,
				'cookies' => array()
			)
		);

		InstapageIO::writeDiagnostics( $response, 'API Response' );

		if ( is_wp_error( $response ) )
		{
			$error_message = $response->get_error_message();
			InstapageIO::writeDiagnostics( $error_message, 'API error message' );

			if ( !empty( $error_message ) )
			{
				throw new InstapageApiCallException( $error_message );
			}
			else
			{
				throw new InstapageApiCallException( '500 Internal Server Error' );
			}
		}

		$res = json_decode( $response[ 'body' ], true );

		if ( !is_array( $res ) && !is_object( $res ) )
		{
			InstapageIO::writeDiagnostics( 'json_decode( $response[ \'body\' ], true ) unsuccessfull - instapageApiCall aborted' );

			throw new InstapageApiCallException( 'Instapage Services returned empty response.' );
		}

		$data = new stdClass();

		foreach ( $res as $key => $val )
		{
			$data->$key = $val;
		}

		if ( $service == 'update-check' )
		{
			set_site_transient( 'instapage_latest_version', $data, 60 * 60 * 12 );
		}

		return $data;
	}

	public function fixHtmlHead( $html )
	{
		$cross_origin_proxy_services = get_option( 'instapage.cross_origin_proxy_services' );

		if ( $cross_origin_proxy_services )
		{
			$html = str_replace( 'PROXY_SERVICES', str_replace( array( 'http://', 'https://' ), array( '//', '//' ), site_url() ) ."/instapage-proxy-services?url=", $html );
		}

		return $html;
	}

	public function getInstapageById( $page_id, $cookies = false )
	{
		$url = self::endpoint .'/server/view-by-id/'. $page_id;

		if( $cookies )
		{
			$cookies_we_need = array( "instapage-variant-{$page_id}" );

			foreach( $cookies as $key => $value )
			{
				if( !in_array( $key, $cookies_we_need ) )
				{
					unset( $cookies[ $key ] );
				}
			}
		}

		InstapageIO::writeDiagnostics( $url, 'API url' );

		$response = wp_remote_post
		(
			$url,
			array
			(
				'method' => 'POST',
				'timeout' => 70,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array
				(
					'useragent' => $_SERVER[ 'HTTP_USER_AGENT' ],
					'ip' => $_SERVER[ 'REMOTE_ADDR' ],
					'cookies' => $cookies,
					'custom' => isset( $_GET[ 'custom' ] ) ? $_GET[ 'custom' ] : null,
					'variant' => isset( $_GET[ 'variant' ] ) ? $_GET[ 'variant' ] : null,
					'tags' => $_GET
				),
				'cookies' => array()
			)
		);

		InstapageIO::writeDiagnostics( $response, 'API Response' );

		if ( is_wp_error( $response ) )
		{
			throw new InstapageApiCallException( $response->get_error_message(), 500 );
		}

		if ( $response[ 'response' ][ 'code' ] == '500' )
		{
			throw new InstapageApiCallException( null, 500 );
		}

		if( $response[ 'headers' ][ 'instapage-variant' ] )
		{
			setcookie
			(
				"instapage-variant-{$page_id}",
				$response[ 'headers' ][ 'instapage-variant' ],
				strtotime( '+12 month' )
			);
		}

		return $response;
	}

	public function getPageHtml( $id )
	{
		$cache = get_site_transient( 'instapage_page_html_cache_' . $id );
		InstapageIO::writeDiagnostics( $cache, 'instapage_page_html_cache_' . $id );

		if ( $cache && !is_user_logged_in() )
		{
			return $this->fixHtmlHead( $cache );
		}

		try
		{
			$page = $this->getInstapageById( $id, $_COOKIE );
		}
		catch( InstapageApiCallException $e )
		{
			return array
			(
				'body' => self::getInstance()->includes[ 'admin' ]->formatError( $e->getMessage(), $e->getCode() ),
				'status' => $page[ 'response' ][ 'code' ]
			);


		}

		if ( $page === false )
		{
			return array
			(
				'body' => self::getInstance()->includes[ 'admin' ]->formatError( __( 'Page not found!' ), 404 ),
				'status' => $page[ 'response' ][ 'code' ]
			);
		}

		return array
		(
			'body' => $this->fixHtmlHead( $page[ 'body' ] ),
			'status' => $page[ 'response' ][ 'code' ]
		);
	}
}