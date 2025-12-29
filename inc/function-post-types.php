<?php
create_post_type('service', array(
	'name' => 'Dịch vụ',
	'slug' => 'dich-vu',
	'icon' => 'dashicons-hammer',
	'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
	'has_archive' => false,
));

create_post_type('product', array(
	'name' => 'Sản phẩm',
	'slug' => 'san-pham',
	'icon' => 'dashicons-products',
	'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
	'has_archive' => false,
));


create_post_type('customer', array(
	'name' => 'Khách hàng',
	'slug' => 'khach-hang',
	'icon' => 'dashicons-groups',
	'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
	'has_archive' => false,
));

create_taxonomy('product_cat', array(
	'name' => 'Danh mục sản phẩm',
	'object_type' => array('product'),
	'hierarchical' => true,
	'rewrite' => array(
		'slug' => 'product_cat',
		'hierarchical' => true,
		'with_front' => false,
	),
));

create_taxonomy('customer_cat', array(
	'name' => 'Danh mục khách hàng',
	'object_type' => array('customer'),
	'hierarchical' => true,
	'rewrite' => array(
		'slug' => 'customer_cat',
		'hierarchical' => true,
		'with_front' => false,
	),
));

/**
 * Remove base slug from taxonomy permalinks (product_cat and customer_cat)
 */
add_filter('term_link', 'remove_taxonomy_base_from_url', 10, 3);
function remove_taxonomy_base_from_url($term_link, $term, $taxonomy) {
	// Remove '/product_cat/' from product URLs
	if ($taxonomy === 'product_cat') {
		$term_link = str_replace('/product_cat/', '/', $term_link);
	}
	
	// Remove '/customer_cat/' from customer URLs
	if ($taxonomy === 'customer_cat') {
		$term_link = str_replace('/customer_cat/', '/', $term_link);
	}
	
	return $term_link;
}

/**
 * Add rewrite rules for taxonomies without base slug (product_cat and customer_cat)
 */
add_action('init', 'add_taxonomy_rewrite_rules', 99);
function add_taxonomy_rewrite_rules() {
	// Process product_cat
	$product_terms = get_terms(array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
	));
	
	if (!empty($product_terms) && !is_wp_error($product_terms)) {
		foreach ($product_terms as $term) {
			$term_slug = $term->slug;
			
			// Build hierarchical slug
			$hierarchical_slug = array();
			$current_term = $term;
			
			while ($current_term->parent != 0) {
				$hierarchical_slug[] = $current_term->slug;
				$current_term = get_term($current_term->parent, 'product_cat');
				if (is_wp_error($current_term)) {
					break;
				}
			}
			$hierarchical_slug[] = $current_term->slug;
			$hierarchical_slug = array_reverse($hierarchical_slug);
			$full_slug = implode('/', $hierarchical_slug);
			
			// Add rewrite rule for this term
			add_rewrite_rule(
				'^' . $full_slug . '/?$',
				'index.php?product_cat=' . $term_slug,
				'top'
			);
			
			// Add rewrite rule for pagination
			add_rewrite_rule(
				'^' . $full_slug . '/page/?([0-9]{1,})/?$',
				'index.php?product_cat=' . $term_slug . '&paged=$matches[1]',
				'top'
			);
		}
	}
	
	// Process customer_cat
	$customer_terms = get_terms(array(
		'taxonomy' => 'customer_cat',
		'hide_empty' => false,
	));
	
	if (!empty($customer_terms) && !is_wp_error($customer_terms)) {
		foreach ($customer_terms as $term) {
			$term_slug = $term->slug;
			
			// Build hierarchical slug
			$hierarchical_slug = array();
			$current_term = $term;
			
			while ($current_term->parent != 0) {
				$hierarchical_slug[] = $current_term->slug;
				$current_term = get_term($current_term->parent, 'customer_cat');
				if (is_wp_error($current_term)) {
					break;
				}
			}
			$hierarchical_slug[] = $current_term->slug;
			$hierarchical_slug = array_reverse($hierarchical_slug);
			$full_slug = implode('/', $hierarchical_slug);
			
			// Add rewrite rule for this term
			add_rewrite_rule(
				'^' . $full_slug . '/?$',
				'index.php?customer_cat=' . $term_slug,
				'top'
			);
			
			// Add rewrite rule for pagination
			add_rewrite_rule(
				'^' . $full_slug . '/page/?([0-9]{1,})/?$',
				'index.php?customer_cat=' . $term_slug . '&paged=$matches[1]',
				'top'
			);
		}
	}
}

/**
 * Parse request để nhận diện taxonomies khi không có slug base
 */
add_filter('request', 'custom_taxonomy_request', 10, 1);
function custom_taxonomy_request($query_vars) {
	// Chỉ xử lý nếu là main query và không phải admin
	if (is_admin()) {
		return $query_vars;
	}
	
	// Nếu đã có taxonomy trong query, không cần xử lý thêm
	if (isset($query_vars['product_cat']) || isset($query_vars['customer_cat'])) {
		return $query_vars;
	}
	
	// Kiểm tra nếu có pagename hoặc name
	$slug = '';
	if (!empty($query_vars['pagename'])) {
		$slug = $query_vars['pagename'];
	} elseif (!empty($query_vars['name'])) {
		$slug = $query_vars['name'];
	}
	
	if (empty($slug)) {
		return $query_vars;
	}
	
	// Kiểm tra xem slug có phải là term của taxonomies không
	$slug_parts = explode('/', trim($slug, '/'));
	$term_slug = end($slug_parts); // Lấy phần cuối cùng
	
	// Try product_cat first
	$term = get_term_by('slug', $term_slug, 'product_cat');
	$taxonomy = 'product_cat';
	
	// If not found, try customer_cat
	if (!$term || is_wp_error($term)) {
		$term = get_term_by('slug', $term_slug, 'customer_cat');
		$taxonomy = 'customer_cat';
	}
	
	if ($term && !is_wp_error($term)) {
		// Xác nhận đây là term hợp lệ với đường dẫn đúng
		// Kiểm tra parent hierarchy nếu có nhiều parts
		if (count($slug_parts) > 1) {
			$parent_slugs = array_slice($slug_parts, 0, -1);
			$current_term = $term;
			
			// Verify parent hierarchy
			foreach (array_reverse($parent_slugs) as $parent_slug) {
				if ($current_term->parent == 0) {
					return $query_vars; // Không match hierarchy
				}
				$parent_term = get_term($current_term->parent, $taxonomy);
				if (!$parent_term || $parent_term->slug !== $parent_slug) {
					return $query_vars; // Không match hierarchy
				}
				$current_term = $parent_term;
			}
		}
		
		// Đây là term hợp lệ, set query vars
		unset($query_vars['pagename']);
		unset($query_vars['name']);
		unset($query_vars['page']);
		
		$query_vars[$taxonomy] = $term->slug;
		$query_vars['taxonomy'] = $taxonomy;
		$query_vars['term'] = $term->slug;
	}
	
	return $query_vars;
}

/**
 * Include child categories when viewing parent category
 * (Khi click vào danh mục cha có con, hiển thị cả sản phẩm/khách hàng của danh mục con)
 */
add_action('pre_get_posts', 'include_children_in_taxonomy_query');
function include_children_in_taxonomy_query($query) {
	// Chỉ áp dụng cho main query, không phải admin
	if (is_admin() || !$query->is_main_query()) {
		return;
	}
	
	// Xử lý cho product_cat
	if (is_tax('product_cat')) {
		$term = get_queried_object();
		
		if ($term && !is_wp_error($term)) {
			$term_children = get_term_children($term->term_id, 'product_cat');
			
			if (!empty($term_children) && !is_wp_error($term_children)) {
				$term_ids = array_merge(array($term->term_id), $term_children);
				
				$query->set('tax_query', array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $term_ids,
						'operator' => 'IN'
					)
				));
			}
		}
	}
	
	// Xử lý cho customer_cat
	if (is_tax('customer_cat')) {
		$term = get_queried_object();
		
		if ($term && !is_wp_error($term)) {
			$term_children = get_term_children($term->term_id, 'customer_cat');
			
			if (!empty($term_children) && !is_wp_error($term_children)) {
				$term_ids = array_merge(array($term->term_id), $term_children);
				
				$query->set('tax_query', array(
					array(
						'taxonomy' => 'customer_cat',
						'field'    => 'term_id',
						'terms'    => $term_ids,
						'operator' => 'IN'
					)
				));
			}
		}
	}
}

// TEMPORARY: Flush rewrite rules once, then REMOVE this line
// Uncomment the line below, reload any page, then comment it back
flush_rewrite_rules();

/**
 * Auto-assign default category "Khách hàng" to new customers
 */
add_action('save_post_customer', 'auto_assign_default_customer_category', 10, 3);
function auto_assign_default_customer_category($post_id, $post, $update) {
	// Chỉ xử lý khi customer được publish
	if ($post->post_status !== 'publish') {
		return;
	}
	
	// Kiểm tra xem customer đã có category chưa
	$terms = wp_get_object_terms($post_id, 'customer_cat');
	
	// Nếu chưa có category nào, gắn vào "Khách hàng"
	if (empty($terms) || is_wp_error($terms)) {
		// Tìm hoặc tạo danh mục "Khách hàng"
		$default_term = get_term_by('slug', 'khach-hang', 'customer_cat');
		
		if (!$default_term) {
			// Tạo term nếu chưa tồn tại
			$default_term = wp_insert_term(
				'Khách hàng',
				'customer_cat',
				array('slug' => 'khach-hang')
			);
			
			if (!is_wp_error($default_term)) {
				$default_term = get_term($default_term['term_id'], 'customer_cat');
			}
		}
		
		// Gắn customer vào danh mục
		if ($default_term && !is_wp_error($default_term)) {
			wp_set_object_terms($post_id, $default_term->term_id, 'customer_cat');
		}
	}
}

/**
 * Register custom query var 'pagenumber'
 * Helps with custom pagination on static pages
 */
add_filter('query_vars', 'register_custom_pagenumber_var');
function register_custom_pagenumber_var($vars) {
	$vars[] = 'pagenumber';
	return $vars;
}