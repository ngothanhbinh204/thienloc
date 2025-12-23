<?php
/**
 * Search Item
 * Dùng trong search loop (WP_Query / main query)
 */

$post_id = get_the_ID();
$title   = get_the_title();
$link    = get_permalink();
$excerpt = get_the_excerpt();
$thumb_id = get_post_thumbnail_id($post_id);
?>

<article class="article-card">
	<a class="article-thumb" href="<?= esc_url($link); ?>">
		<?php if ($thumb_id) : ?>
		<?= get_image_post($post_id, 'image'); ?>
		<?php else : ?>
		<img src="https://via.placeholder.com/400x250" alt="<?= esc_attr($title); ?>">
		<?php endif; ?>
	</a>

	<div class="article-card-body">
		<h3 class="article-title">
			<a href="<?= esc_url($link); ?>">
				<?= esc_html($title); ?>
			</a>
		</h3>

		<?php if ($excerpt) : ?>
		<p class="article-excerpt">
			<?= esc_html(wp_trim_words($excerpt, 20)); ?>
		</p>
		<?php endif; ?>
	</div>

</article>