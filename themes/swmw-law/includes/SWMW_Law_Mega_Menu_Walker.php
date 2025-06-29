<?php
/**
 * Custom Nav Walker for the Mega Menu
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SWMW_Law_Mega_Menu_Walker class.
 */
class SWMW_Law_Mega_Menu_Walker extends \Walker_Nav_Menu {

	private $current_item_is_mega_parent = false;
	private $mega_parent_item_title = '';
	private $mega_parent_item_url = ''; // Store URL of the mega parent
	private $mega_parent_item_id = ''; // Store ID of the mega parent for ARIA

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( $depth === 0 && $this->current_item_is_mega_parent ) {
			$trigger_id = 'mega-menu-trigger-' . esc_attr( $this->mega_parent_item_id );
			$output .= "\n" . $indent . "<div class=\"mega-menu-panel\" role=\"region\" aria-labelledby=\"" . $trigger_id . "\" aria-hidden=\"true\">\n";
			$output .= $indent . "\t<div class=\"mega-menu-panel-inner\">\n";
			
			$output .= $indent . "\t\t<div class=\"mega-menu-column mega-menu-column-left\">\n";
			$output .= $indent . "\t\t\t<ul class=\"sub-menu mega-menu-child-list\">\n";
		} else {
			$submenu_level_class = 'sub-menu-level-' . ( $depth + 1 );
			$output .= "\n" . $indent . "<ul class=\"sub-menu " . $submenu_level_class . "\">\n";
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( $depth === 0 && $this->current_item_is_mega_parent ) {
			$output .= $indent . "\t\t\t</ul>\n"; 
			$output .= $indent . "\t\t</div>\n";   
			$output .= $indent . "\t\t<div class=\"mega-menu-column mega-menu-column-right\">\n";
			$output .= $indent . "\t\t\t<div class=\"mega-menu-column-right-content\"></div>\n";
			$output .= $indent . "\t\t</div>\n";
			$output .= $indent . "\t</div>\n";       
			$output .= $indent . "</div>\n";       

			$this->current_item_is_mega_parent = false; 
			$this->mega_parent_item_title = '';
			$this->mega_parent_item_url = '';
			$this->mega_parent_item_id = ''; 
		} else {
			$output .= $indent . "</ul>\n";
		}
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ($depth === 0) { 
            $this->current_item_is_mega_parent = false;
            $this->mega_parent_item_title = '';
			$this->mega_parent_item_url = '';
			$this->mega_parent_item_id = '';
        }

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		if ( $depth === 0 && $this->has_children ) { 
			$classes[] = 'menu-item-has-mega-menu';
			$this->current_item_is_mega_parent = true;
			$this->mega_parent_item_title = $item->title;
			$this->mega_parent_item_url = $item->url;
			$this->mega_parent_item_id = $item->ID; 
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$current_item_id_attr_val = 'menu-item-'. $item->ID;
		$current_item_id_attr = apply_filters( 'nav_menu_item_id', $current_item_id_attr_val, $item, $args, $depth );
		$current_item_id_attr = $current_item_id_attr ? ' id="' . esc_attr( $current_item_id_attr ) . '"' : '';

		$output .= $indent . '<li' . $current_item_id_attr . $class_names . '>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '#';

		if ( $depth === 0 && $this->has_children && $this->current_item_is_mega_parent && $item->ID === $this->mega_parent_item_id) {
			$atts['aria-haspopup'] = 'true'; 
			$atts['aria-expanded'] = 'false'; 
			$atts['id'] = 'mega-menu-trigger-' . $item->ID;
		}
		elseif ($depth > 0 && $this->has_children) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) || $attr === 'aria-expanded' ) {
				$value_processed = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value_processed . '"';
			}
		}
		
		$item_output = isset($args->before) ? $args->before : '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= isset($args->link_before) ? $args->link_before : '';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= isset($args->link_after) ? $args->link_after : '';
		$item_output .= '</a>';
		$item_output .= isset($args->after) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}
