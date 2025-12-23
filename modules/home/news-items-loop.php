<?php
/**
 * Loop template for home news filtering
 */
$category = $args['category'] ?? 'all';
$per_page = $args['per_page'] ?? 5;
$cat_ids  = $args['cat_ids'] ?? [];

$query_args = [
	'post_type'      => 'post',
	'posts_per_page' => $per_page,
	'post_status'    => 'publish',
];

if ($category !== 'all') {
	$query_args['category_name'] = $category;
} elseif (!empty($cat_ids)) {
	$query_args['category__in'] = $cat_ids;
}

$query = new WP_Query($query_args);

if ($query->have_posts()) : ?>
	<div class="news-grid">
		<?php
		$index = 0;
		while ($query->have_posts()) : $query->the_post();
			$post_id      = get_the_ID();
			$terms        = get_the_terms($post_id, 'category');
			$term         = (!empty($terms) && !is_wp_error($terms)) ? $terms[0] : null;
			$is_primary   = ($index === 0);
			$is_secondary = ($index === 1);

			// Map to existing SASS classes + User requested logic classes
			$item_class = 'news-item';
			if ($is_primary) {
				$item_class .= ' news-item-featured featured-primary';
				$card_class = 'news-card-featured';
			} else {
				$item_class .= ' news-item-small';
				$card_class = 'news-card-small';
				
				if ($is_secondary) {
					$item_class .= ' featured-secondary';
				} else {
					$item_class .= ' remaining-posts';
				}
			}
			?>
			<div class="<?= esc_attr($item_class); ?>" <?= $term ? 'data-category="' . esc_attr($term->slug) . '"' : ''; ?>>
				<div class="news-card <?= esc_attr($card_class); ?>">
					<a class="news-card-img" href="<?= esc_url(get_permalink()); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<?= get_image_post(get_the_ID(), 'image') ?>
						<?php endif; ?>
					</a>

					<div class="news-card-content">
						<div class="news-card-meta">
							<span class="news-date"><?= esc_html(get_the_date('d.m.Y')); ?></span>
							<?php if ($term) : ?>
								<span class="news-category cat-<?= esc_attr($term->slug); ?>">
									<?= esc_html($term->name); ?>
								</span>
							<?php endif; ?>
						</div>

						<h3 class="news-card-title">
							<a href="<?= esc_url(get_permalink()); ?>">
								<?= esc_html(get_the_title()); ?>
							</a>
						</h3>

						<?php if ($is_primary) : ?>
							<p class="news-card-desc">
								<?= esc_html(wp_trim_words(get_the_excerpt(), 30)); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
			$index++;
		endwhile;
		wp_reset_postdata();
		?>
	</div>
<?php else : ?>
	<div class="no-posts container text-center py-10">
		<p><?= esc_html__('Không tìm thấy bài viết nào.', 'canhcamtheme'); ?></p>
	</div>
<?php endif; ?>
