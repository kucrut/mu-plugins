<?php

/*
Plugin Name: KC No View Term Links
Description: Remove <strong><em>View</em></strong> links on term list table for <em>non-public</em> taxonomies.
Author: Dzikri Aziz
Author URI: http://kucrut.org/
License: GPL v2
*/

final class KC_No_View_Term_Links {

	public static function setup() {
		$screen   = get_current_screen();
		$taxonomy = get_taxonomy( $screen->taxonomy );
		if ( $taxonomy->public )
			return false;

		add_filter( "{$screen->taxonomy}_row_actions", array(__CLASS__, '_remove_view_link'), 10, 2 );
	}


	public static function _remove_view_link( $actions, $tag ) {
		unset( $actions['view'] );

		return $actions;
	}
}
add_action( 'load-edit-tags.php', array('KC_No_View_Term_Links', 'setup') );
