<?php
function log_dump($data)
{
	ob_start();
	var_dump($data);
	$dump = ob_get_clean();
	$highlighted = highlight_string("<?php\n" . $dump . "\n?>", true);
$formatted = '
<pre>' . substr($highlighted, 27, -8) . '</pre>';
$custom_css = 'pre {position: static;
background: #ffffff80;
// max-height: 50vh;
width: 100vw;
}
pre::-webkit-scrollbar{
width: 1rem;}';

$formatted_css = '<style>
' . $custom_css . '
</style>';
echo ($formatted_css . $formatted);
}

function empty_content($str)
{
return trim(str_replace('&nbsp;', '', strip_tags($str, '<img>'))) == '';
}
add_theme_support('custom-logo', [
'height' => 80,
'width' => 240,
'flex-height' => true,
'flex-width' => true,
]);



/**
 * Get Breadcrumbs as array for the frontend
 */
function canhcam_get_breadcrumbs()
{
	$breadcrumbs = [];
	$breadcrumbs[] = [
		'label' => __('Trang chủ', 'canhcamtheme'),
		'url'   => home_url('/')
	];

	if (is_front_page()) {
		return [];
	}

	// 1. PRODUCTS (Single Product or Category)
	if (is_singular('product') || is_tax('product_cat') || is_page_template('templates/page-products.php')) {
		$product_page_id = get_page_id_by_template('templates/page-products.php');
		if ($product_page_id) {
			$breadcrumbs[] = [
				'label' => get_the_title($product_page_id),
				'url'   => get_permalink($product_page_id)
			];
		}

		if (is_tax('product_cat')) {
			$term = get_queried_object();
			$ancestors = get_ancestors($term->term_id, 'product_cat');
			if (!empty($ancestors)) {
				$ancestors = array_reverse($ancestors);
				foreach ($ancestors as $ancestor_id) {
					$ancestor = get_term($ancestor_id, 'product_cat');
					$breadcrumbs[] = [
						'label' => $ancestor->name,
						'url'   => get_term_link($ancestor)
					];
				}
			}
			$breadcrumbs[] = [
				'label' => $term->name,
				'url'   => get_term_link($term)
			];
		} elseif (is_singular('product')) {
			$terms = get_the_terms(get_the_ID(), 'product_cat');
			if ($terms && !is_wp_error($terms)) {
				// Use primary category if Rank Math is active
				$main_term = $terms[0];
				if (class_exists('RankMath')) {
					$primary_term_id = get_post_meta(get_the_ID(), 'rank_math_primary_product_cat', true);
					if ($primary_term_id) {
						$main_term = get_term($primary_term_id, 'product_cat');
					}
				}

				// Add ancestors
				$ancestors = get_ancestors($main_term->term_id, 'product_cat');
				if (!empty($ancestors)) {
					$ancestors = array_reverse($ancestors);
					foreach ($ancestors as $ancestor_id) {
						$ancestor = get_term($ancestor_id, 'product_cat');
						$breadcrumbs[] = [
							'label' => $ancestor->name,
							'url'   => get_term_link($ancestor)
						];
					}
				}
				// Add main term
				$breadcrumbs[] = [
					'label' => $main_term->name,
					'url'   => get_term_link($main_term)
				];
			}
			$breadcrumbs[] = [
				'label' => get_the_title(),
				'url'   => ''
			];
		}
	}
	// 2. NEWS / POSTS
	elseif (is_home() || is_singular('post') || is_category() || is_tag()) {
		$news_page_id = get_option('page_for_posts');
		if ($news_page_id) {
			$breadcrumbs[] = [
				'label' => get_the_title($news_page_id),
				'url'   => get_permalink($news_page_id)
			];
		}

		if (is_category() || is_tag()) {
			$term = get_queried_object();
			$breadcrumbs[] = [
				'label' => $term->name,
				'url'   => get_term_link($term)
			];
		} elseif (is_singular('post')) {
			$categories = get_the_category();
			if (!empty($categories)) {
				$main_cat = $categories[0];
				$breadcrumbs[] = [
					'label' => $main_cat->name,
					'url'   => get_category_link($main_cat)
				];
			}
			$breadcrumbs[] = [
				'label' => get_the_title(),
				'url'   => ''
			];
		}
	}
	// 3. SERVICE / CUSTOMER / OTHER CPTs
	elseif (is_singular(['service', 'customer'])) {
		$post_type = get_post_type();
		$post_type_obj = get_post_type_object($post_type);
		
		// Try to find a page matching the post type name or archive
		// For this site, we might need specific templates
		$template_map = [
			'service' => 'templates/page-services.php',
			'customer' => 'templates/page-customers.php'
		];

		if (isset($template_map[$post_type])) {
			$parent_id = get_page_id_by_template($template_map[$post_type]);
			if ($parent_id) {
				$breadcrumbs[] = [
					'label' => get_the_title($parent_id),
					'url'   => get_permalink($parent_id)
				];
			}
		}

		$breadcrumbs[] = [
			'label' => get_the_title(),
			'url'   => ''
		];
	}
	// 4. PAGES
	elseif (is_page()) {
		$post = get_queried_object();
		if ($post->post_parent) {
			$ancestors = array_reverse(get_post_ancestors($post->ID));
			foreach ($ancestors as $ancestor_id) {
				$breadcrumbs[] = [
					'label' => get_the_title($ancestor_id),
					'url'   => get_permalink($ancestor_id)
				];
			}
		}
		$breadcrumbs[] = [
			'label' => get_the_title(),
			'url'   => ''
		];
	}
	// 5. SEARCH / 404 / ARCHIVES
	elseif (is_search()) {
		$breadcrumbs[] = ['label' => __('Tìm kiếm: ', 'canhcamtheme') . get_search_query(), 'url' => ''];
	} elseif (is_404()) {
		$breadcrumbs[] = ['label' => __('404 Không tìm thấy', 'canhcamtheme'), 'url' => ''];
	} else {
		$breadcrumbs[] = ['label' => get_the_title(), 'url' => ''];
	}

	return $breadcrumbs;
}

/**
 * AJAX News Filtering
 */
function canhcam_ajax_filter_news()
{
	$category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
	$per_page = isset($_POST['per_page']) ? (int)$_POST['per_page'] : 5;
	$cat_ids  = isset($_POST['cat_ids']) ? array_map('intval', (array)$_POST['cat_ids']) : [];

	ob_start();
	get_template_part('modules/home/news-items-loop', null, [
		'category' => $category,
		'per_page' => $per_page,
		'cat_ids'  => $cat_ids
	]);
	$html = ob_get_clean();

	wp_send_json_success($html);
}
add_action('wp_ajax_canhcam_filter_news', 'canhcam_ajax_filter_news');
add_action('wp_ajax_nopriv_canhcam_filter_news', 'canhcam_ajax_filter_news');