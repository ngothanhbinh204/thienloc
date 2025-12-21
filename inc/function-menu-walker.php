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
		$classes      = is_array( $item->classes ) ? $item->classes : [];
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$is_active    = header_menu_is_active( $item );

		$active_class = $is_active ? ' is-active current-item' : '';

		// ===== LEVEL 0 =====
		if ( $depth === 0 ) {
			$output .= '<li class="' . esc_attr( ( $has_children ? 'has-sub' : '' ) . $active_class ) . '" role="none">';
			$output .= '<a class="item-menu" href="' . esc_url( $item->url ) . '" role="menuitem" aria-haspopup="' . ( $has_children ? 'true' : 'false' ) . '">';
			$output .= '<span>' . esc_html( $item->title ) . '</span>';
			$output .= '</a>';
		}

		// ===== LEVEL 1 (Column) =====
		elseif ( $depth === 1 ) {
			$output .= '<div class="col" role="none">';
			$output .= '<div class="label">';
			$output .= '<a href="' . esc_url( $item->url ) . '" class="' . ( $is_active ? 'is-active current-item' : '' ) . '" role="menuitem">';
			$output .= esc_html( $item->title );
			$output .= '</a>';
			$output .= '</div>';
		}

		// ===== LEVEL 2 =====
		elseif ( $depth === 2 ) {
			$output .= '<li role="none">';
			$output .= '<a href="' . esc_url( $item->url ) . '" class="' . ( $is_active ? 'is-active current-item' : '' ) . '" role="menuitem">';
			$output .= esc_html( $item->title );
			$output .= '</a>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '</li>';
		} elseif ( $depth === 1 ) {
			$output .= '</div>';
		} else {
			$output .= '</li>';
		}
	}
}

class Header_Mobile_Menu_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="submenu hidden pl-4">';
		$output .= '<ul class="flex flex-col gap-2 py-2">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul></div>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes      = is_array( $item->classes ) ? $item->classes : [];
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$is_active    = header_menu_is_active( $item );

		$active_class = $is_active ? ' is-active current-item' : '';

		$output .= '<li class="' . esc_attr( $active_class ) . '">';

		if ( $has_children ) {
			$output .= '<div class="flex items-center justify-between">';
		}

		$output .= '<a href="' . esc_url( $item->url ) . '" class="block flex-1 py-2">';
		$output .= esc_html( $item->title );
		$output .= '</a>';

		if ( $has_children ) {
			$output .= '<i class="fa-solid fa-chevron-down w-6 h-6 flex items-center justify-center cursor-pointer"></i>';
			$output .= '</div>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

/**
 * Helper function to check if a menu item should be marked as active for the current page
 * Updated with logic from class-header-walker.php
 */
function header_menu_is_active( $item ) {
	$classes = is_array( $item->classes ) ? $item->classes : [];

	// 1. Native WP active check
	if (
		in_array( 'current-menu-item', $classes, true ) ||
		in_array( 'current-menu-parent', $classes, true ) ||
		in_array( 'current-menu-ancestor', $classes, true ) ||
		in_array( 'current_page_item', $classes, true ) ||
		in_array( 'current_page_parent', $classes, true ) ||
		in_array( 'current_page_ancestor', $classes, true )
	) {
		return true;
	}

	// 2. Custom Logic for Singular Pages (CPT, Post, Product)
	if ( is_singular() ) {
		$post_type = get_post_type();

		// Service
		if ( $post_type === 'service' ) {
			$service_link = get_page_link_by_template( 'templates/page-services.php' );
			if ( $service_link && is_menu_item_url_match( $item->url, $service_link ) ) {
				return true;
			}

			$archive_link = get_post_type_archive_link( 'service' );
			if ( $archive_link && is_menu_item_url_match( $item->url, $archive_link ) ) {
				return true;
			}
		}

		// Customer
		if ( $post_type === 'customer' ) {
			$customer_link = get_page_link_by_template( 'templates/page-customers.php' );
			if ( $customer_link && is_menu_item_url_match( $item->url, $customer_link ) ) {
				return true;
			}

			$archive_link = get_post_type_archive_link( 'customer' );
			if ( $archive_link && is_menu_item_url_match( $item->url, $archive_link ) ) {
				return true;
			}
		}

		// Post (Blog)
		if ( $post_type === 'post' ) {
			$posts_page_id = get_option( 'page_for_posts' );
			if ( $posts_page_id ) {
				$blog_link = get_permalink( $posts_page_id );
				if ( $blog_link && is_menu_item_url_match( $item->url, $blog_link ) ) {
					return true;
				}
			}
		}

		// Product (WooCommerce)
		if ( $post_type === 'product' ) {
			if ( function_exists( 'wc_get_page_id' ) ) {
				$shop_page_id = wc_get_page_id( 'shop' );
				if ( $shop_page_id ) {
					$shop_link = get_permalink( $shop_page_id );
					if ( $shop_link && is_menu_item_url_match( $item->url, $shop_link ) ) {
						return true;
					}
				}
			}
		}
	}

	// 3. Taxonomy Archives
	if ( is_tax() || is_category() || is_tag() ) {
		$taxonomy = get_queried_object();
		if ( $taxonomy && isset( $taxonomy->term_id ) ) {
			$term_link = get_term_link( $taxonomy );
			if ( $term_link && ! is_wp_error( $term_link ) && is_menu_item_url_match( $item->url, $term_link ) ) {
				return true;
			}
		}

		// Special case for Product Category -> Active Shop Page menu item
		if ( is_tax( 'product_cat' ) ) {
			if ( function_exists( 'wc_get_page_id' ) ) {
				$shop_page_id = wc_get_page_id( 'shop' );
				if ( $shop_page_id ) {
					$shop_link = get_permalink( $shop_page_id );
					if ( $shop_link && is_menu_item_url_match( $item->url, $shop_link ) ) {
						return true;
					}
				}
			}
		}
	}

	// 4. WooCommerce Shop Page
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		if ( $shop_page_id ) {
			$shop_link = get_permalink( $shop_page_id );
			if ( $shop_link && is_menu_item_url_match( $item->url, $shop_link ) ) {
				return true;
			}
		}
	}

	// 5. Blog Archive
	if ( is_home() || ( is_archive() && get_post_type() === 'post' ) ) {
		$posts_page_id = get_option( 'page_for_posts' );
		if ( $posts_page_id ) {
			$blog_link = get_permalink( $posts_page_id );
			if ( $blog_link && is_menu_item_url_match( $item->url, $blog_link ) ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Helper function to compare menu URL with actual link
 */
function is_menu_item_url_match( $menu_url, $link ) {
	if ( empty( $menu_url ) || empty( $link ) ) {
		return false;
	}

	$menu_url_parsed = wp_parse_url( $menu_url );
	$link_parsed     = wp_parse_url( $link );

	$menu_path = isset( $menu_url_parsed['path'] ) ? rtrim( $menu_url_parsed['path'], '/' ) : '';
	$link_path = isset( $link_parsed['path'] ) ? rtrim( $link_parsed['path'], '/' ) : '';

	return $menu_path === $link_path;
}

if ( ! function_exists( 'get_page_link_by_template' ) ) {
	function get_page_link_by_template( $template_name ) {
		$pages = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template_name,
			'number'     => 1,
		) );
		if ( ! empty( $pages ) ) {
			return get_permalink( $pages[0]->ID );
		}

		return false;
	}
}