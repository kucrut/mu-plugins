<?php

/*
Plugin Name: KC Post List Table Thumbnails
Description: Display post thumbnails on post list table
Plugin URI: http://kucrut.org/post-list-table-thumbnails/
Author: Dzikri Aziz
Author URI: http://kucrut.org/
Version: 0.1
License: GPL v2
*/

final class KC_Post_List_Table_Thumbnail {

	public static function setup() {
		if ( !current_theme_supports('post-thumbnails') ) {
			error_log( "Current theme doesn't support post thumbnails" );
			return false;
		}

		$screen = get_current_screen();
		if ( !post_type_supports($screen->post_type, 'thumbnail') )
			return false;

		add_filter( "manage_{$screen->post_type}_posts_columns", array(__CLASS__, '_add_thumbnail_column'), 10, 2 );
		add_action( "manage_{$screen->post_type}_posts_custom_column", array(__CLASS__, '_display_thumbnail_column'), 10, 2 );
		add_action( 'admin_print_styles', array(__CLASS__, '_style') );
	}


	public static function _add_thumbnail_column( $posts_columns, $post_type ) {
		$new = array( '_thumb' => __('Thumbnail') );
		$pos = array_search( 'cb', array_keys($posts_columns) );

		if ( false !== $pos ) {
			$posts_columns = array_merge(
				array_slice( $posts_columns, $pos, ( $pos + 1 ), true ),
				$new,
				array_slice( $posts_columns, ( $pos + 1 ), ( count($posts_columns) - ($pos + 1) ), true )
			);
		}
		else {
			$posts_columns = array_merge( $posts_columns, $new );
		}

		return $posts_columns;
	}


	public static function _display_thumbnail_column( $column_name, $post_id ) {
		if ( '_thumb' === $column_name )
			echo has_post_thumbnail($post_id) ? get_the_post_thumbnail( $post_id ) : '&mdash;';
	}


	public static function _style() {
?>
<style>
	#wpbody-content .column-_thumb {
		width: 80px;
		text-align: center;
	}
	#wpbody-content td.column-_thumb img {
		max-width: 60px;
		height: auto;
	}
</style>
<?php
	}
}
add_action( 'load-edit.php', array('KC_Post_List_Table_Thumbnail', 'setup') );
