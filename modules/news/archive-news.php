<?php
$paged = max(1, get_query_var('paged'));
$featured_id = 0;
?>

<section class="section-news-archive section-py">
	<div class="container">

		<?php
// ==========================
// FEATURED POST (PAGE 1 ONLY)
// ==========================
if ($paged === 1) {

	$featured_args = [
		'post_type'      => 'post',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
	];

	if (is_category()) {
		$featured_args['cat'] = get_queried_object_id();
	}

	$featured_query = new WP_Query($featured_args);

	if ($featured_query->have_posts()) :
		$featured_query->the_post();
		$featured_id = get_the_ID();
		?>

		<div class="featured-post">
			<div class="box-news box-news-featured">
				<div class="news-image">
					<a href="<?php the_permalink(); ?>">
						<?php if (has_post_thumbnail()) : ?>
						<?= get_image_post(get_the_ID(), 'image'); ?>
						<?php endif; ?>
					</a>
				</div>

				<div class="news-content">
					<div class="news-date">
						<div class="date-day"><?= get_the_date('d'); ?></div>
						<div class="date-month"><?= get_the_date('m.Y'); ?></div>
					</div>

					<div class="wrapper">
						<h2 class="news-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>

						<div class="news-excerpt body-16">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	endif;
	wp_reset_postdata();
}
?>

		<?php
// ==========================
// MAIN NEWS LIST
// ==========================
$args = [
	'post_type'      => 'post',
	'posts_per_page' => 9,
	'paged'          => $paged,
	'post_status'    => 'publish',
];

if ($featured_id) {
	$args['post__not_in'] = [$featured_id];
}

if (is_category()) {
	$args['cat'] = get_queried_object_id();
}

$news_query = new WP_Query($args);
?>

		<?php if ($news_query->have_posts()) : ?>
		<div class="news-grid">
			<?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
			<div class="box-news">
				<div class="news-image">
					<a href="<?php the_permalink(); ?>">
						<?php if (has_post_thumbnail()) : ?>
						<?= get_image_post(get_the_ID(), 'image'); ?>
						<?php endif; ?>
					</a>
				</div>

				<div class="news-content">
					<div class="news-date">
						<div class="date-day"><?= get_the_date('d'); ?></div>
						<div class="date-month"><?= get_the_date('m.Y'); ?></div>
					</div>

					<div class="wrapper">
						<h3 class="news-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<div class="news-excerpt body-16">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>
			</div>
			<?php endwhile; ?>
		</div>

		<?php if ($news_query->max_num_pages > 1) : ?>
		<div class="news-pagination">
			<?php
			wp_bootstrap_pagination([
				'custom_query' => $news_query
			]);
			?>
		</div>
		<?php endif; ?>

		<?php else : ?>
		<p><?php esc_html_e('Chưa có bài viết nào.', 'canhcamtheme'); ?></p>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</div>
</section>