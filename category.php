<?php

/**
 * Category Archive Template
 *
 * ASSUMPTIONS (ACF Field Names):
 * - 'banner_select_page' : ACF field used by banner.php to select a banner post
 * - ACF fields are registered for taxonomy terms (category)
 * - Fallback uses the WordPress "Posts page" (Settings > Reading)
 */

get_header();

/**
 * --- CONDITIONAL BANNER LOGIC ---
 * Priority:
 * 1. Category's own banner data
 * 2. Fallback to Posts page (blog page) banner
 * 3. No banner (add wrapper-gap-top class)
 */

$current_term = get_queried_object();
$term_context_id = $current_term instanceof WP_Term
	? "{$current_term->taxonomy}_{$current_term->term_id}"
	: null;

$term_has_banner = false;
if ($term_context_id) {
	$term_banner = get_field('banner_select_page', $term_context_id);
	$term_has_banner = !empty($term_banner);
}
$posts_page_id = get_option('page_for_posts');

$posts_page_has_banner = false;
if ($posts_page_id) {
	$posts_page_banner = get_field('banner_select_page', $posts_page_id);
	$posts_page_has_banner = !empty($posts_page_banner);
}

$should_render_banner = $term_has_banner || $posts_page_has_banner;

if (!$term_has_banner && $posts_page_has_banner && $posts_page_id) {
	$original_queried_object = $GLOBALS['wp_query']->queried_object;
	$original_queried_object_id = $GLOBALS['wp_query']->queried_object_id;

	$GLOBALS['wp_query']->queried_object = get_post($posts_page_id);
	$GLOBALS['wp_query']->queried_object_id = $posts_page_id;
}

if ($should_render_banner) {
	get_template_part('modules/common/banner');
}

if (!$term_has_banner && $posts_page_has_banner && $posts_page_id) {
	$GLOBALS['wp_query']->queried_object = $original_queried_object;
	$GLOBALS['wp_query']->queried_object_id = $original_queried_object_id;
}
$main_extra_class = !$should_render_banner ? ' wrapper-gap-top' : '';
?>

<section class="<?= esc_attr(trim($main_extra_class)) ?>">
	<?php get_template_part('modules/common/breadcrumb'); ?>
	<?php get_template_part('modules/news/archive-news'); ?>
</section>

<?php get_footer(); ?>