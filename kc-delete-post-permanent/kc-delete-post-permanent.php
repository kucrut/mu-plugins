<?php

/*
Plugin Name: _KC :: Delete post permanently
Author: Dzikri Aziz
Author URI: http://kucrut.org/
Description: Add a <strong>Delete</strong> link to post actions row for <em>administrators</em>
License: GPL v2
Version: 0.1
*/


/**
 *
 */
function _kc_add_delete_permanent_post_link( $actions, $post ) {
	if ( current_user_can('administrator') && empty($actions['delete']) ) {
		$actions['delete'] = sprintf(
			'<a class="submitdelete" title="%1$s" href="%2$s">%3$s</a>',
			esc_attr( __( 'Delete this item permanently' ) ),
			get_delete_post_link( $post->ID, '', true ),
			__( 'Delete Permanently' )
		);
	}

	return $actions;
}
add_filter( 'page_row_actions', '_kc_add_delete_permanent_post_link', 10, 2 );
add_filter( 'post_row_actions', '_kc_add_delete_permanent_post_link', 10, 2 );
