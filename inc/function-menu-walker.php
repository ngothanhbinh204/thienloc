<?php

class Header_Menu_Walker extends Walker_Nav_Menu
{
	// Start Level
	public function start_lvl(&$output, $depth = 0, $args = null)
	{
		if ($depth === 0) {
			// Mega menu wrapper
			$output .= '<div class="mega-menu">';
		} elseif ($depth === 1) {
			$output .= '<ul>';
		} else {
			$output .= '<ul class="submenu-2 hidden pl-4 border-l border-neutral-200 ml-2">';
		}
	}

	// End Level
	public function end_lvl(&$output, $depth = 0, $args = null)
	{
		if ($depth === 0) {
			$output .= '</div>';
		} else {
			$output .= '</ul>';
		}
	}

	// Start Element
	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$has_children = in_array('menu-item-has-children', $item->classes);

		// LEVEL 0 – MAIN MENU
		if ($depth === 0) {
			$output .= '<li' . ($has_children ? ' class="has-sub"' : '') . '>';
			$output .= '<a class="item-menu" href="' . esc_url($item->url) . '">';
			$output .= '<span>' . esc_html($item->title) . '</span>';
			$output .= '</a>';
		}

		// LEVEL 1 – MEGA MENU COLUMN
		elseif ($depth === 1) {
			$output .= '<div class="col">';
			$output .= '<div class="label">' . esc_html($item->title) . '</div>';
		}

		// LEVEL 2 – MEGA MENU ITEMS
		elseif ($depth === 2) {
			$output .= '<li>';
			$output .= '<a href="' . esc_url($item->url) . '">';
			$output .= esc_html($item->title);
			$output .= '</a>';
		}
	}

	// End Element
	public function end_el(&$output, $item, $depth = 0, $args = null)
	{
		if ($depth === 0) {
			$output .= '</li>';
		} elseif ($depth === 1) {
			$output .= '</div>';
		} elseif ($depth === 2) {
			$output .= '</li>';
		}
	}
}