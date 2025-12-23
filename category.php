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

// Get current category term object
$current_term = get_queried_object();
$term_context_id = $current_term instanceof WP_Term 
	? "{$current_term->taxonomy}_{$current_term->term_id}" 
	: null;

// Check if current category has banner data
$term_has_banner = false;
if ($term_context_id) {
	$term_banner = get_field('banner_select_page', $term_context_id);
	$term_has_banner = !empty($term_banner);
}

// Get Posts page (blog page) ID dynamically
$posts_page_id = get_option('page_for_posts');

// Check if Posts page has banner data (for fallback)
$posts_page_has_banner = false;
if ($posts_page_id) {
	$posts_page_banner = get_field('banner_select_page', $posts_page_id);
	$posts_page_has_banner = !empty($posts_page_banner);
}

// Determine if we should render banner and which source to use
$should_render_banner = $term_has_banner || $posts_page_has_banner;

// If term doesn't have banner but Posts page does, temporarily set globals for fallback
if (!$term_has_banner && $posts_page_has_banner && $posts_page_id) {
	// Store original queried object
	$original_queried_object = $GLOBALS['wp_query']->queried_object;
	$original_queried_object_id = $GLOBALS['wp_query']->queried_object_id;
	
	// Temporarily override to Posts page for banner template
	$GLOBALS['wp_query']->queried_object = get_post($posts_page_id);
	$GLOBALS['wp_query']->queried_object_id = $posts_page_id;
}

// Render banner if available
if ($should_render_banner) {
	get_template_part('modules/common/banner');
}

// Restore original queried object if it was overridden
if (!$term_has_banner && $posts_page_has_banner && $posts_page_id) {
	$GLOBALS['wp_query']->queried_object = $original_queried_object;
	$GLOBALS['wp_query']->queried_object_id = $original_queried_object_id;
}

// Determine extra class for main when no banner
$main_extra_class = !$should_render_banner ? ' wrapper-gap-top' : '';
?>

<section class="<?= esc_attr(trim($main_extra_class)) ?>">
	<?php get_template_part('modules/common/breadcrumb'); ?>
	<?php get_template_part('modules/news/archive-news'); ?>
</section>

<?php get_footer(); ?>