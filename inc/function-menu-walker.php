<?php

class Header_Desktop_Menu_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '<div class="mega-menu" role="group">';
		} elseif ( $depth === 1 ) {
			$output .= '<ul role="menu">';
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '</div>';
		} else {
			$output .= '</ul>';
		}
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Get standard WP classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Custom logic existing in file
		$has_children = in_array( 'menu-item-has-children', $classes );
		$is_active    = in_array( 'current-menu-item', $classes ) || in_array( 'current-menu-ancestor', $classes );

		// Custom CPT Active State
		if ( ! $is_active ) {
			if ( is_singular( 'product' ) ) {
				$product_page_id = get_page_id_by_template( 'templates/page-products.php' );
				if ( (int) $item->object_id === (int) $product_page_id ) {
					$is_active = true;
				}
			} elseif ( is_singular( 'service' ) ) {
				$service_page_id = get_page_id_by_template( 'templates/page-services.php' );
				if ( (int) $item->object_id === (int) $service_page_id ) {
					$is_active = true;
				}
			} elseif ( is_singular( 'customer' ) ) {
				$customer_page_id = get_page_id_by_template( 'templates/page-customers.php' );
				if ( (int) $item->object_id === (int) $customer_page_id ) {
					$is_active = true;
				}
			} elseif ( is_singular( 'post' ) ) {
				$news_page_id = get_option( 'page_for_posts' );
				if ( (int) $item->object_id === (int) $news_page_id ) {
					$is_active = true;
				}
			}
		}

		// Add custom classes
		if ( $has_children ) {
			$classes[] = 'has-sub';
		}
		if ( $is_active ) {
			$classes[] = 'is-active';
		}

		// Filter classes (Standard WP behavior)
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$is_first_item = ( $item->menu_order === 1 );

		// ===== LEVEL 0 =====
		if ( $depth === 0 ) {
			$output .= '<li' . $class_names . ' role="none">';
			$output .= '<a class="item-menu" href="' . esc_url( $item->url ) . '" role="menuitem" aria-haspopup="' . ( $has_children ? 'true' : 'false' ) . '" aria-expanded="false">';
			
			// Home icon
			if ( $is_first_item ) {
				$output .= '<i class="fa-solid fa-house" aria-hidden="true"></i>';
				$output .= '<span class="menu-text screen-reader-text">' . esc_html( $item->title ) . '</span>';
			} else {
				$output .= '<span class="menu-text">' . esc_html( $item->title ) . '</span>';
			}

			$output .= '</a>';
		}

		// ===== LEVEL 1 (Column) =====
		elseif ( $depth === 1 ) {
			if ( ! in_array( 'col', $classes ) ) {
				$classes[] = 'col';
			}
			$class_names_col = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			
			$output .= '<div class="' . esc_attr( $class_names_col ) . '" role="none">';
			$output .= '<a href="' . esc_url( $item->url ) . '" class="label" role="presentation">';
			$output .= esc_html( $item->title );
			$output .= '</a>';

			// $output .= '<div class="' . esc_attr( $class_names_col ) . '" role="none">';
			// $output .= '<div class="label" role="presentation">';
			// $output .= esc_html( $item->title );
			// $output .= '</div>';
		}

		// ===== LEVEL 2 (Links) =====
		elseif ( $depth === 2 ) {
			$output .= '<li' . $class_names . ' role="none">';
			$output .= '<a href="' . esc_url( $item->url ) . '" role="menuitem" class="' . ( $is_active ? 'is-active' : '' ) . '">';
			$output .= esc_html( $item->title );
			$output .= '</a>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '</li>';
		} elseif ( $depth === 1 ) {
			$output .= '</div>';
		} elseif ( $depth === 2 ) {
			$output .= '</li>';
		}
	}
}
	
class Header_Mobile_Menu_Walker extends Walker_Nav_Menu
{
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		// LEVEL 0 -> submenu
		if ( $depth === 0 ) {
			$output .= '<div class="submenu hidden pl-4">';
			$output .= '<ul class="flex flex-col gap-2 py-2">';
		}

		// LEVEL 1 -> submenu-2
		if ( $depth === 1 ) {
			$output .= '<div class="submenu-2 hidden pl-4 border-l border-neutral-200 ml-2">';
			$output .= '<ul class="flex flex-col gap-2 py-2">';
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 || $depth === 1 ) {
			$output .= '</ul></div>';
		}
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

		// ===== STANDARD WP CLASSES =====
		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );
		if ( $has_children ) {
			$classes[] = 'has-sub';
		}
		
		$is_active = (
			in_array( 'current-menu-item', $classes, true ) ||
			in_array( 'current-menu-parent', $classes, true ) ||
			in_array( 'current-menu-ancestor', $classes, true ) ||
			in_array( 'current_page_item', $classes, true )
		);

		// Custom CPT Active State
		if ( ! $is_active ) {
			if ( is_singular( 'product' ) ) {
				$product_page_id = get_page_id_by_template( 'templates/page-products.php' );
				if ( (int) $item->object_id === (int) $product_page_id ) {
					$is_active = true;
				}
			} elseif ( is_singular( 'service' ) ) {
				$service_page_id = get_page_id_by_template( 'templates/page-services.php' );
				if ( (int) $item->object_id === (int) $service_page_id ) {
					$is_active = true;
				}
			} elseif ( is_singular( 'customer' ) ) {
				$customer_page_id = get_page_id_by_template( 'templates/page-customers.php' );
				if ( (int) $item->object_id === (int) $customer_page_id ) {
					$is_active = true;
				}
			} elseif ( is_singular( 'post' ) ) {
				$news_page_id = get_option( 'page_for_posts' );
				if ( (int) $item->object_id === (int) $news_page_id ) {
					$is_active = true;
				}
			}
		}

		if ( $is_active ) {
			$classes[] = 'is-active';
		}

		// Filter giống WP core
		$class_names = implode(
			' ',
			apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth )
		);
		$class_attr = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		// Home icon (item đầu tiên)
		$is_first_item = ( $depth === 0 && (int) $item->menu_order === 1 );

		// ================= LEVEL 0 =================
		if ( $depth === 0 ) {

			$output .= '<li' . $class_attr . '>';

			if ( $has_children ) {
				$output .= '<div class="flex items-center justify-between border-b border-neutral-100">';
			}

			$output .= '<a href="' . esc_url( $item->url ) . '" class="flex items-center gap-2 flex-1 py-2 text-lg font-bold text-utility-2929" aria-label="' . esc_attr( $item->title ) . '">';

			if ( $is_first_item ) {
				$output .= '<i class="fa-solid fa-house" aria-hidden="true"></i>';
				$output .= '<span class="screen-reader-text">' . esc_html( $item->title ) . '</span>';
			} else {
				$output .= '<span>' . esc_html( $item->title ) . '</span>';
			}

			$output .= '</a>';

			if ( $has_children ) {
				$output .= '<i class="fa-solid fa-chevron-down text-sm text-neutral-500 transition-transform duration-300 w-8 h-8 flex items-center justify-center cursor-pointer"></i>';
				$output .= '</div>';
			}
		}

		// ================= LEVEL 1 =================
		elseif ( $depth === 1 ) {

			$output .= '<li' . $class_attr . '>';

			if ( $has_children ) {
				$output .= '<div class="flex items-center justify-between">';
			}

			$output .= '<a href="' . esc_url( $item->url ) . '" class="flex-1 py-2 body-18">';

			$output .= esc_html( $item->title );

			$output .= '</a>';

			if ( $has_children ) {
				$output .= '<i class="fa-solid fa-chevron-down text-xs text-neutral-400 transition-transform duration-300 w-6 h-6 flex items-center justify-center cursor-pointer"></i>';
				$output .= '</div>';
			}
		}

		// ================= LEVEL 2 =================
		elseif ( $depth === 2 ) {

			$output .= '<li' . $class_attr . '>';

			$output .= '<a href="' . esc_url( $item->url ) . '" class="block py-1 text-sm text-neutral-500">';

			$output .= esc_html( $item->title );

			$output .= '</a>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}