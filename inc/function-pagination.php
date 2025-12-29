<?php

/**
 * WordPress Bootstrap Pagination
 *
 * <?php echo wp_bootstrap_pagination(array('custom_query' => $the_query)) ?>
*
* Thêm tham số sau vào WP_Query
* $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
* 'paged' => $paged
*/
function wp_bootstrap_pagination($args = array())
{
	$defaults = array(
		'range' => 4,
		'custom_query' => FALSE,
		'before_output' => '<nav class="wrapper-pagination" aria-label="Pagination"><ul class="pagination">',
		'after_output' => '</ul></nav>'
	);

	$args = wp_parse_args($args, apply_filters('wp_bootstrap_pagination_defaults', $defaults));
	$args['range'] = (int) $args['range'] - 1;

	if (!$args['custom_query']) {
		$args['custom_query'] = @$GLOBALS['wp_query'];
	}

	$count = (int) $args['custom_query']->max_num_pages;
	if ($count <= 1) return FALSE;

	$page = 0;
	if (isset($args['custom_query']->query_vars['paged']) && $args['custom_query']->query_vars['paged'] > 0) {
		$page = intval($args['custom_query']->query_vars['paged']);
	}
	if (!$page) $page = intval(get_query_var('paged'));
	$page = $page ? $page : 1;

	$append_sort = function ($url) {
		if (isset($_GET['sort']) && !empty($_GET['sort'])) {
			return add_query_arg('sort', sanitize_text_field($_GET['sort']), $url);
		}
		return $url;
	};

	$ceil = ceil($args['range'] / 2);

	if ($count > $args['range']) {
		if ($page <= $args['range']) {
			$min = 1;
			$max = $args['range'] + 1;
		} elseif ($page >= ($count - $ceil)) {
			$min = $count - $args['range'];
			$max = $count;
		} elseif ($page >= $args['range'] && $page < ($count - $ceil)) {
			$min = $page - $ceil;
			$max = $page + $ceil;
		}
	} else {
		$min = 1;
		$max = $count;
	}

	$echo = '';
	if (!empty($min) && !empty($max)) {
		for ($i = $min; $i <= $max; $i++) {
			if ($page == $i) {
				$echo .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
			} else {
				$echo .= sprintf(
					'<li class="page-item"><a class="page-link" href="%s">%d</a></li>',
					esc_attr($append_sort(get_pagenum_link($i))),
					$i
				);
			}
		}
	}

	if (isset($echo)) {
		echo $args['before_output'] . $echo . $args['after_output'];
	}
}

function custom_query_vars($vars)
{
	$vars[] = 'sort';
	return $vars;
}
add_filter('query_vars', 'custom_query_vars');