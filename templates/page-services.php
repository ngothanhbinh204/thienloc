<?php 
/*
Template name: Page - Services
*/ 
?>
<?= get_header() ?>

<?php get_template_part('modules/common/banner')?>
<?php get_template_part('modules/common/breadcrumb')?>

<section class="services-list">
	<h2 class="section-title">Dịch vụ</h2>
	<div class="services-grid">
		<?php
			$args = array(
				'post_type'      => 'service',
				'posts_per_page' => -1,
				'status'         => 'publish',
			);
			$query = new WP_Query($args);

			if ($query->have_posts()) :
				while ($query->have_posts()) : $query->the_post();
					$image_id = get_post_thumbnail_id();
			?>
		<div class="service-item">
			<div class="service-img">
				<a href="<?php the_permalink(); ?>">
					<?= get_image_attrachment($image_id, 'image'); ?>
				</a>
			</div>
			<div class="service-content">
				<h3 class="service-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
				<div class="service-desc">
					<?php the_excerpt(); ?>
				</div>
			</div>
		</div>
		<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
	</div>
</section>

<?= get_footer() ?>