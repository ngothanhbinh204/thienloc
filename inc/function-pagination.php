<?php

/**
 * WordPress Bootstrap Pagination - For Archive/Taxonomy pages
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
        'before_output' => '<nav class="wrapper-pagination" aria-label="Pagination"><ul class="pagination">',
        'after_output' => '</ul></nav>'
    );

    $args = wp_parse_args($args, apply_filters('wp_bootstrap_pagination_defaults', $defaults));
    $args['range'] = (int) $args['range'] - 1;
    
    if (!$args['custom_query']) {
        $args['custom_query'] = @$GLOBALS['wp_query'];
    }
    
    $count = (int) $args['custom_query']->max_num_pages;
    
    // Detect current page
    $page = 0;
    if (isset($args['custom_query']->query_vars['paged']) && $args['custom_query']->query_vars['paged'] > 0) {
        $page = intval($args['custom_query']->query_vars['paged']);
    }
    if (!$page) $page = intval(get_query_var('paged'));
    if (!$page) $page = intval(get_query_var('page'));
    $page = $page ? $page : 1;

    // Helper to append sort param
    $append_sort = function($url) {
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            return add_query_arg('sort', sanitize_text_field($_GET['sort']), $url);
        }
        return $url;
    };

    $ceil = ceil($args['range'] / 2);

    if ($count <= 1) return FALSE;

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

    // First page
    // $firstpage = esc_attr($append_sort(get_pagenum_link(1)));
    // if ($firstpage && (1 != $page)) {
    //     $echo .= '<li class="page-item first"><a class="page-link" href="' . $firstpage . '" title="' . __('Đầu tiên', 'text-domain') . '">' . $args['first_string'] . '</a></li>';
    // }

    // Previous page
    // $previous = intval($page) - 1;
    // $previous = esc_attr($append_sort(get_pagenum_link($previous)));
    // if ($previous && (1 != $page)) {
    //     $echo .= '<li class="page-item prev"><a class="page-link" href="' . $previous . '" title="' . __('Trước', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';
    // }

    // Page numbers
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

    // Next page
    // $next = intval($page) + 1;
    // $next = esc_attr($append_sort(get_pagenum_link($next)));
    // if ($next && ($count != $page)) {
    //     $echo .= '<li class="page-item next"><a class="page-link" href="' . $next . '" title="' . __('Kế tiếp', 'text-domain') . '">' . $args['next_string'] . '</a></li>';
    // }

    // Last page
    // $lastpage = esc_attr($append_sort(get_pagenum_link($count)));
    // if ($lastpage && ($count != $page)) {
    //     $echo .= '<li class="page-item last"><a class="page-link" href="' . $lastpage . '" title="' . __('Cuối cùng', 'text-domain') . '">' . $args['last_string'] . '</a></li>';
    // }

    if (isset($echo)) echo $args['before_output'] . $echo . $args['after_output'];
}

/**
 * WordPress Bootstrap Pagination - For Page Templates (using ?page=X)
 */
function wp_bootstrap_pagination_page_template($args = array())
{
    $defaults = array(
        'range' => 4,
        'custom_query' => FALSE,
        'current_page' => 1,
        'previous_string' => '<i class="fa-solid fa-chevron-left"></i>',
        'next_string' => '<i class="fa-solid fa-chevron-right"></i>',
        'first_string' => '<i class="fa-solid fa-angles-left"></i>',
        'last_string' => '<i class="fa-solid fa-angles-right"></i>',
        'before_output' => '<nav class="wrapper-pagination" aria-label="Pagination"><ul class="pagination">',
        'after_output' => '</ul></nav>'
    );

    $args = wp_parse_args($args, $defaults);
    $args['range'] = (int) $args['range'] - 1;
    
    if (!$args['custom_query']) {
        return FALSE;
    }
    
    $count = (int) $args['custom_query']->max_num_pages;
    $page = (int) $args['current_page'];

    // Helper to build URL with query string
    $build_url = function($page_num) {
        $base_url = get_permalink(get_queried_object_id());
        $params = array();
        
        // Add sort param if exists
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $params['sort'] = sanitize_text_field($_GET['sort']);
        }
        
        // Add page param if not page 1
        if ($page_num > 1) {
            $params['page'] = $page_num;
        }
        
        return !empty($params) ? add_query_arg($params, $base_url) : $base_url;
    };

    $ceil = ceil($args['range'] / 2);

    if ($count <= 1) return FALSE;

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

    // First page
    if (1 != $page) {
        $echo .= '<li class="page-item first"><a class="page-link" href="' . esc_url($build_url(1)) . '" title="' . __('Đầu tiên', 'text-domain') . '">' . $args['first_string'] . '</a></li>';
    }

    // Previous page
    if (1 != $page) {
        $previous = $page - 1;
        $echo .= '<li class="page-item prev"><a class="page-link" href="' . esc_url($build_url($previous)) . '" title="' . __('Trước', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';
    }

    // Page numbers
    if (!empty($min) && !empty($max)) {
        for ($i = $min; $i <= $max; $i++) {
            if ($page == $i) {
                $echo .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
            } else {
                $echo .= '<li class="page-item"><a class="page-link" href="' . esc_url($build_url($i)) . '">' . $i . '</a></li>';
            }
        }
    }

    // Next page
    if ($count != $page) {
        $next = $page + 1;
        $echo .= '<li class="page-item next"><a class="page-link" href="' . esc_url($build_url($next)) . '" title="' . __('Kế tiếp', 'text-domain') . '">' . $args['next_string'] . '</a></li>';
    }

    // Last page
    if ($count != $page) {
        $echo .= '<li class="page-item last"><a class="page-link" href="' . esc_url($build_url($count)) . '" title="' . __('Cuối cùng', 'text-domain') . '">' . $args['last_string'] . '</a></li>';
    }

    if (isset($echo)) echo $args['before_output'] . $echo . $args['after_output'];
}

/**
 * Allow 'page' query var for page templates
 */
function custom_query_vars($vars) {
    $vars[] = 'page';
    $vars[] = 'sort';
    return $vars;
}
add_filter('query_vars', 'custom_query_vars');

/**
 * Fix pagination redirect on page templates
 */
function fix_page_template_pagination_redirect($redirect_url) {
    // Check if we're on a page template with pagination
    if (is_page() && get_query_var('page') > 1) {
        return false;
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'fix_page_template_pagination_redirect');