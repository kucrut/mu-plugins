<?php

/*
Plugin Name: KC Dropdownn Menu
Description: Display menu as a dropdown, useful for small screen devices
Author: Dzikri Aziz
Author URI: http://kucrut.org
Version: 0.1
*/

if ( is_admin() )
	return;

class kcMU_Dropdown_Menu {
	public static function get_menu( $menu_id, $args = array() ) {
		$menu = wp_get_nav_menu_object( $menu_id );
		if ( !$menu )
			return;

		$items = wp_get_nav_menu_items( $menu_id );
		if ( !$items || is_wp_error($items) )
			return;

		$args = wp_parse_args( $args, array(
			'depth'       => 0,
			'pad'         => '&mdash;',
			'echo'        => true,
			'submit_text' => __('Submit'),
			'select_text' => '',
			'js'          => false,
			'menu_class'  => '',
			'menu_id'     => ''
		) );

		$walk = new kcMS_Walker_Menu;
		$walk->pad = $args['pad'];
		$menu_items = $walk->walk( $items, $args['depth'], $args );

		$class = 'kcform';
		if ( $args['menu_class'] )
			$class .= " {$args['menu_class']}";

		$f_attr = 'class="'.$class.'" method="post"';
		if ( $args['menu_id'] )
			$f_attr .= ' id="'.$args['menu_id'].'"';

		$out  = '<form '.$f_attr.'>' . PHP_EOL;
		$out .= '<select name="kcform[menu-id]">' . PHP_EOL;
		if ( $args['select_text'] )
			$out .= '<option value="">'.$args['select_text'].'</option>' . PHP_EOL;
		foreach( $menu_items as $_id => $_title )
			$out .= '<option value="'.$_id.'">'.$_title.'</option>' . PHP_EOL;
		$out .= '</select>' . PHP_EOL;
		$out .= '<button type="submit" name="kcform[action]" value="menu">'.$args['submit_text'].'</button>' . PHP_EOL;
		$out .= '</form>' . PHP_EOL;

		if ( $args['echo'] )
			echo $out;
		else
			return $out;
	}


	public static function _catch() {
		if (
			!isset( $_POST['kcform'] )
			|| !isset( $_POST['kcform']['action'] )
			|| $_POST['kcform']['action'] !== 'menu'
		)
			return;

		if ( isset( $_POST['kcform']['menu-id'] ) && $_POST['kcform']['menu-id'] ) {
			$m = wp_setup_nav_menu_item( get_post( $_POST['kcform']['menu-id'] ) );
			wp_redirect( $m->url );
			exit;
		}
	}
}
add_action( 'init', array('kcMU_Dropdown_Menu', '_catch') );


class kcMS_Walker_Menu extends Walker {
	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
	var $pad = '';

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth && !empty($this->pad) ) ? str_repeat( $this->pad, $depth ) .'&nbsp;' : '';
		$output[$item->ID] = $indent . apply_filters( 'the_title', $item->title, $item->ID );
	}
}
