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
'previous_string' => '<i class="fa-solid fa-chevron-left"></i>',
'next_string' => '<i class="fa-solid fa-chevron-right"></i>',
'first_string' => '<i class="fa-solid fa-angles-left"></i>',
'last_string' => '<i class="fa-solid fa-angles-right"></i>',
'before_output' => '<nav class="wrapper-pagination" aria-label="Pagination">
	<ul class="pagination">',
		'after_output' => '</ul>
</nav>'
);

$args = wp_parse_args(
$args,
apply_filters('wp_bootstrap_pagination_defaults', $defaults)
);

$args['range'] = (int) $args['range'] - 1;
if (!$args['custom_query'])
$args['custom_query'] = @$GLOBALS['wp_query'];
$count = (int) $args['custom_query']->max_num_pages;
// Fix: Correctly detect current page number
$page = 0;
if (isset($args['custom_query']->query_vars['paged']) && $args['custom_query']->query_vars['paged'] > 0) {
$page = intval($args['custom_query']->query_vars['paged']);
}
if (!$page) $page = intval(get_query_var('paged'));
if (!$page) $page = intval(get_query_var('page'));
$page = $page ? $page : 1;

$ceil = ceil($args['range'] / 2);

if ($count <= 1) return FALSE; if ($count> $args['range']) {
	if ($page <= $args['range']) { $min=1; $max=$args['range'] + 1; } elseif ($page>= ($count - $ceil)) {
		$min = $count - $args['range'];
		$max = $count;
		} elseif ($page >= $args['range'] && $page < ($count - $ceil)) { $min=$page - $ceil; $max=$page + $ceil; } }
			else { $min=1; $max=$count; } $echo='' ; $firstpage=esc_attr(get_pagenum_link(1)); if ($firstpage && (1
			!=$page)) { $echo .='<li class="page-item first"><a class="page-link" href="' . $firstpage . '" title="' .
			__('Đầu tiên', 'text-domain' ) . '">' . (isset($args['first_string']) ? $args['first_string'] : 'First' )
			. '</a></li>' ; } $previous=intval($page) - 1; $previous=esc_attr(get_pagenum_link($previous)); if
			($previous && (1 !=$page)) { $echo .='<li class="page-item prev"><a class="page-link" href="' . $previous
			. '" title="' . __('Trước', 'text-domain' ) . '">' . $args['previous_string'] . '</a></li>' ; } if
			(!empty($min) && !empty($max)) { for ($i=$min; $i <=$max; $i++) { if ($page==$i) { $echo
			.='<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>' ; } else { $echo
			.=sprintf('<li class="page-item"><a class="page-link" href="%s">%d</a></li>',
			esc_attr(get_pagenum_link($i)), $i);
			}
			}
			}


			$next = intval($page) + 1;
			$next = esc_attr(get_pagenum_link($next));
			if ($next && ($count != $page)) {
			$echo .= '<li class="page-item next"><a class="page-link" href="' . $next . '"
					title="' . __('Kế tiếp', 'text-domain') . '">' . $args['next_string'] . '</a></li>';
			}

			$lastpage = esc_attr(get_pagenum_link($count));
			if ($lastpage && ($count != $page)) {
			$echo .= '<li class="page-item last"><a class="page-link" href="' . $lastpage . '"
					title="' . __('Cuối cùng', 'text-domain') . '">' . (isset($args['last_string']) ?
					$args['last_string'] : 'Last') . '</a></li>';
			}

			if (isset($echo)) echo $args['before_output'] . $echo . $args['after_output'];
			}

			/**
			* Fix pagination 404/redirect on static pages using custom queries
			*/
			function fix_static_page_pagination_redirect($redirect_url) {
			if (is_page_template('templates/page-products.php')) {
			$page = get_query_var('page');
			if ($page > 1) {
			return false;
			}
			}
			return $redirect_url;
			}
			add_filter('redirect_canonical', 'fix_static_page_pagination_redirect');