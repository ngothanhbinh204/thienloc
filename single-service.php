<?php get_header(); ?>

<main>
	<div class="wrapper-gap-top">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<?php
		$image_id = get_post_thumbnail_id();
		?>
		<section class="section-services-detail-1">
			<div class="services-detail-wrapper">
				<div class="services-detail-content">
					<div class="sub-title">Dịch vụ</div>
					<h1 class="title"><?php the_title(); ?></h1>
				</div>
				<div class="services-detail-image">
					<?= get_image_attrachment($image_id, 'image'); ?>
				</div>
			</div>
		</section>

		<?php
		$detail_desc_top    = get_the_content();
		?>
		<section class="section-services-detail-2 section-py">
			<div class="container">
				<div class="row">
					<div class="grid-layout">
						<div class="content-wrapper">
							
							<?php if ($detail_desc_top) : ?>
								<div class="desc">
									<?= $detail_desc_top; ?>
								</div>
							<?php endif; ?>

						</div>
					</div>
				</div>
			</div>
		</section>

		<?php
		$gallery = get_field('service_gallery');
		if ($gallery) :
		?>
			<section class="section-services-detail-3 section-py">
				<div class="swiper-services-detail">
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php foreach ($gallery as $img_id) : ?>
								<div class="swiper-slide">
									<div class="img">
										<?= get_image_attrachment($img_id, 'image'); ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="swiper-navigation">
						<div class="swiper-button-deGallery-prev swiper-button-vuong"><i class="fa-solid fa-chevron-left"></i></div>
						<div class="swiper-button-deGallery-next"><i class="fa-solid fa-chevron-right swiper-button-vuong"></i></div>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="section-services-detail-4 section-py">
			<div class="container">
				<h2>Dịch vụ khác</h2>
				<div class="swiper-services-other">
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php
							$related_args = array(
								'post_type'      => 'service',
								'posts_per_page' => 8,
								'post__not_in'   => array(get_the_ID()),
								'orderby'        => 'rand',
							);
							$related_query = new WP_Query($related_args);

							if ($related_query->have_posts()) :
								while ($related_query->have_posts()) : $related_query->the_post();
									$rel_img_id = get_post_thumbnail_id();
							?>
									<div class="swiper-slide">
										<div class="item">
											<div class="img">
												<a href="<?php the_permalink(); ?>">
													<?= get_image_attrachment($rel_img_id, 'image'); ?>
												</a>
											</div>
											<h3 class="title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
										</div>
									</div>
							<?php
								endwhile;
								wp_reset_postdata();
							endif;
							?>
						</div>
					</div>
					<div class="swiper-navigation-center-y">
						<div class="swiper-button-other-prev swiper-button-vuong"><i class="fa-solid fa-chevron-left"></i></div>
						<div class="swiper-button-other-next swiper-button-vuong"><i class="fa-solid fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>