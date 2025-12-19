<?php
$paged = max(1, get_query_var('paged'));

$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'post_status'    => 'publish',
);

// Category filter
if (is_category()) {
    $args['cat'] = get_queried_object_id();
}

$news_query = new WP_Query($args);
?>

<div class="wrapper-gap-top">
	<?php get_template_part('modules/common/breadcrumb'); ?>

	<section class="section-news-archive section-py">
		<div class="container">

			<?php if ($news_query->have_posts()) : ?>

			<?php if ($paged === 1 && !is_category()) : ?>
			<?php $news_query->the_post(); ?>
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
							<h2 class="news-title title-20">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<div class="news-excerpt body-16">
								<?php the_excerpt(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

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
							<h3 class="news-title title-20">
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
                wp_bootstrap_pagination(array(
                    'custom_query' => $news_query
                ));
                ?>
			</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

			<?php else : ?>
			<p>Chưa có bài viết nào.</p>
			<?php endif; ?>

		</div>
	</section>
</div>