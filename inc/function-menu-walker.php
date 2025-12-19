<?php

class Header_Desktop_Menu_Walker extends Walker_Nav_Menu
{
	public function start_lvl(&$output, $depth = 0, $args = null)
	{
		if ($depth === 0) {
			$output .= '<div class="mega-menu" role="group">';
		} elseif ($depth === 1) {
			$output .= '<ul role="menu">';
		}
	}

	public function end_lvl(&$output, $depth = 0, $args = null)
	{
		if ($depth === 0) {
			$output .= '</div>';
		} else {
			$output .= '</ul>';
		}
	}

	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$has_children = in_array('menu-item-has-children', $item->classes);
		$is_active = in_array('current-menu-item', $item->classes) || in_array('current-menu-ancestor', $item->classes);

		$active_class = $is_active ? ' is-active' : '';

		// LEVEL 0
		if ($depth === 0) {
			$output .= '<li class="' . ($has_children ? 'has-sub' : '') . $active_class . '" role="none">';
			$output .= '<a 
				class="item-menu"
				href="' . esc_url($item->url) . '"
				role="menuitem"
				aria-haspopup="' . ($has_children ? 'true' : 'false') . '"
				aria-expanded="false"
			>';
			$output .= '<span>' . esc_html($item->title) . '</span>';
			$output .= '</a>';
		}

		// LEVEL 1 – COLUMN
		elseif ($depth === 1) {
			$output .= '<div class="col" role="none">';
			$output .= '<div class="label" role="presentation">';
			$output .= esc_html($item->title);
			$output .= '</div>';
		}

		// LEVEL 2 – LINKS
		elseif ($depth === 2) {
			$output .= '<li role="none">';
			$output .= '<a 
				href="' . esc_url($item->url) . '" 
				role="menuitem"
				class="' . ($is_active ? 'is-active' : '') . '"
			>';
			$output .= esc_html($item->title);
			$output .= '</a>';
		}
	}

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
	

class Header_Mobile_Menu_Walker extends Walker_Nav_Menu
{
	public function start_lvl(&$output, $depth = 0, $args = null)
	{
		// LEVEL 0 -> submenu
		if ($depth === 0) {
			$output .= '<div class="submenu hidden pl-4">';
			$output .= '<ul class="flex flex-col gap-2 py-2">';
		}

		// LEVEL 1 -> submenu-2
		if ($depth === 1) {
			$output .= '<div class="submenu-2 hidden pl-4 border-l border-neutral-200 ml-2">';
			$output .= '<ul class="flex flex-col gap-2 py-2">';
		}
	}

	public function end_lvl(&$output, $depth = 0, $args = null)
	{
		if ($depth === 0 || $depth === 1) {
			$output .= '</ul></div>';
		}
	}

	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$classes = is_array($item->classes) ? $item->classes : [];
		$has_children = in_array('menu-item-has-children', $classes);

		// ===== LEVEL 0 =====
		if ($depth === 0) {
			$output .= '<li' . ($has_children ? ' class="has-sub"' : '') . '>';

			if ($has_children) {
				$output .= '<div class="flex items-center justify-between border-b border-neutral-100">';
			}

			$output .= '<a class="text-lg font-bold text-utility-2929 block py-2 flex-1" href="' . esc_url($item->url) . '">';
			$output .= esc_html($item->title);
			$output .= '</a>';

			if ($has_children) {
				$output .= '<i class="fa-solid fa-chevron-down text-sm text-neutral-500 transition-transform duration-300 w-8 h-8 flex items-center justify-center cursor-pointer"></i>';
				$output .= '</div>';
			}
		}

		// ===== LEVEL 1 =====
		elseif ($depth === 1) {
			$output .= '<li' . ($has_children ? ' class="has-sub-2"' : '') . '>';

			if ($has_children) {
				$output .= '<div class="flex items-center justify-between">';
			}

			$output .= '<a class="text-base text-neutral-600 block py-2 flex-1" href="' . esc_url($item->url) . '">';
			$output .= esc_html($item->title);
			$output .= '</a>';

			if ($has_children) {
				$output .= '<i class="fa-solid fa-chevron-down text-xs text-neutral-400 transition-transform duration-300 w-6 h-6 flex items-center justify-center cursor-pointer"></i>';
				$output .= '</div>';
			}
		}

		// ===== LEVEL 2 =====
		elseif ($depth === 2) {
			$output .= '<li>';
			$output .= '<a class="text-sm text-neutral-500 block py-1" href="' . esc_url($item->url) . '">';
			$output .= esc_html($item->title);
			$output .= '</a>';
		}
	}

	public function end_el(&$output, $item, $depth = 0, $args = null)
	{
		$output .= '</li>';
	}
}