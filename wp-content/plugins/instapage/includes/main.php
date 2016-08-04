<?php

class InstapageMain extends instapage
{
	public function init()
	{
		add_action( 'init', array( &$this, 'instapagePostRegister' ) );
		add_filter( 'display_post_states', array( &$this, 'customPostState' ) );
		add_filter( 'posts_join', array( &$this, 'instapageSearchJoin' ) );
		add_filter( 'posts_where', array( &$this, 'instapageSearchWhere' ) );
		add_filter( 'posts_orderby', array( &$this, 'instapageSearchOrderBy' ) );
		add_filter( 'posts_distinct', array( &$this, 'instapageSearchDistinct' ) );
	}

	public function instapagePostRegister()
	{
		instapage::getInstance()->includes[ 'service' ]->silentUpdateCheck();

		$labels = array
		(
			'name'					=> _x( 'Instapage', 'Post type general name', 'instapage' ),
			'singular_name'			=> _x( 'Instapage', 'Post type singular name', 'instapage' ),
			'add_new'				=> _x( 'Add New', 'instapage', 'instapage' ),
			'add_new_item'			=> __( 'Add New Instapage', 'instapage' ),
			'edit_item'				=> __( 'Edit Instapage', 'instapage' ),
			'new_item'				=> __( 'New Instapage', 'instapage' ),
			'view_item'				=> __( 'View Instapage', 'instapage' ),
			'search_items'			=> __( 'Search Instapage', 'instapage' ),
			'not_found'				=> __( 'Nothing found', 'instapage' ),
			'not_found_in_trash'	=> __( 'Nothing found in Trash', 'instapage' ),
			'parent_item_colon'		=> ''
		);

		$capabilities = array
		(
			''
		);

		$args = array
		(
			'labels'				=> $labels,
			'description'			=> __( 'Allows you to have Instapage on your WordPress site.', 'instapage' ),
			'public'				=> false,
			'publicly_queryable'	=> true,
			'show_ui'				=> true,
			'query_var'				=> true,
			'menu_icon'				=> INSTAPAGE_PLUGIN_URI . 'assets/img/instapage-logo-16x16.png',
			'capability_type'		=> 'page',
			'menu_position'			=> null,
			'rewrite'				=> false,
			'can_export'			=> false,
			'hierarchical'			=> false,
			'has_archive'			=> false,
			'supports'				=> array( 'instapage_my_selected_page', 'instapage_slug', 'instapage_name', 'instapage_url' ),
			'register_meta_box_cb'	=> array( &instapage::getInstance()->includes[ 'edit' ], 'removeMetaBoxes' )
		);

		register_post_type( 'instapage_post', $args );
	}

	public static function getUserId()
	{
		return get_option( 'instapage.user_id' );
	}

	public function customPostState( $states )
	{
		global $post;

		$show_custom_state = null !== get_post_meta( $post->ID, 'instapage_my_selected_page' );

		if ( $show_custom_stat )
		{
			$states = array();
		}

		return $states;
	}

	public static function isPageModeActive( $new_edit = null )
	{
		global $pagenow;

		// make sure we are on the backend
		if ( !is_admin() )
		{
			return false;
		}

		if ( $new_edit == "edit" )
		{
			return in_array( $pagenow, array( 'post.php' ) );
		}
		elseif ( $new_edit == "new" )
		{
			// check for new post page
			return in_array( $pagenow, array( 'post-new.php' ) );
		}
		else
		{
			// check for either new or edit
			return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
		}
	}

	function instapageSearchJoin( $join )
	{
		global $pagenow, $wpdb;

		if ( is_admin() && $pagenow == 'edit.php' && $_GET[ 'post_type' ] == 'instapage_post' && $_GET[ 's' ] != '' )
		{
			$join .='LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
		}

		return $join;
	}

	function instapageSearchWhere( $where )
	{
		global $pagenow, $wpdb;
		$keyword = filter_var( $_GET[ 's' ], FILTER_SANITIZE_STRING );

		if ( is_admin() && $pagenow == 'edit.php' && $_GET[ 'post_type' ] == 'instapage_post' && $_GET[ 's' ] != '' )
		{
			$pattern = "/\(wp_posts\.post_title\s+LIKE\s+(\'[^\']+\')\)\s+OR\s+\(wp_posts\.post_content\s+LIKE\s+(\'[^\']+\')\)/";
			$new_where = "( ( {$wpdb->postmeta}.meta_key = 'instapage_name' AND {$wpdb->postmeta}.meta_value LIKE '%{$keyword}%' ) OR ( {$wpdb->postmeta}.meta_key = 'instapage_slug' AND {$wpdb->postmeta}.meta_value LIKE '%{$keyword}%' ) OR {$wpdb->posts}.ID = '{$keyword}' )";
			$where = preg_replace( $pattern, $new_where, $where );
		}

		return $where;
	}

	function instapageSearchOrderBy( $orderby )
	{
		global $pagenow, $wpdb;

		if ( is_admin() && $pagenow == 'edit.php' && $_GET[ 'post_type' ] == 'instapage_post' && $_GET[ 's' ] != '' )
		{
			$orderby = "{$wpdb->posts}.post_date DESC";
		}

		return $orderby;
	}

	function instapageSearchDistinct()
	{
		return "DISTINCT";
	}
}