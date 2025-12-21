<?php get_header(); ?>

<main>
	<div class="wrapper-gap-top">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<section class="section-news-detail section-py">
			<div class="container">
				<div class="news-detail-main">
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="header-news">
						<div class="news-date">
							<div class="date-day"><?= get_the_date('d'); ?></div>
							<div class="date-month"><?= get_the_date('m.Y'); ?></div>
						</div>
						<h1 class="news-title title-36"><?php the_title(); ?></h1>
					</div>
					<div class="news-body space-y-5">
						<div class="content-detail">
							<?php the_content(); ?>
						</div>
					</div>
					<div class="news-share">
						<span class="share-label">Chia sẻ:</span>
						<div class="share-buttons">
							<a class="share-btn share-facebook"
								href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(get_permalink()); ?>"
								target="_blank"><i class="fa-brands fa-facebook"></i></a>
							<a class="share-btn share-linkedin"
								href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(get_permalink()); ?>"
								target="_blank"><i class="fa-brands fa-linkedin"></i></a>
							<a class="share-btn share-youtube" href="#" target="_blank"><i
									class="fa-brands fa-youtube"></i></a>
						</div>
					</div>
					<?php endwhile; endif; ?>
				</div>
			</div>
		</section>

		<div class="section-related-news">
			<div class="container">
				<div class="related-news section-py">
					<h2 class="section-title title-36 text-center text-primary">Tin tức liên quan</h2>
					<div class="related-news relative">
						<div class="swiper-column-auto auto-3-column swiper-loop">
							<div class="swiper swiper-related-news">
								<div class="swiper-wrapper">
									<?php
									$related_args = array(
										'post_type'      => 'post',
										'posts_per_page' => 6,
										'post__not_in'   => array(get_the_ID()),
										'orderby'        => 'date',
										'order'          => 'DESC'
									);
									// Filter by category if possible
									$categories = get_the_category();
									if ($categories) {
										$cat_ids = array();
										foreach($categories as $individual_category) $cat_ids[] = $individual_category->term_id;
										$related_args['category__in'] = $cat_ids;
									}

									$related_query = new WP_Query($related_args);

									if ($related_query->have_posts()) :
										while ($related_query->have_posts()) : $related_query->the_post();
									?>
									<div class="swiper-slide">
										<div class="box-news">
											<div class="news-image">
												<a href="<?php the_permalink(); ?>">
													<?php if (has_post_thumbnail()) : ?>
													<?= get_image_attrachment(get_post_thumbnail_id(), 'image'); ?>
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
									</div>
									<?php
										endwhile;
										wp_reset_postdata();
									endif;
									?>
								</div>
							</div>
							<div class="swiper-navigation">
								<div class="btn-prev btn-slider-1"><i class="fa-solid fa-chevron-left"></i></div>
								<div class="btn-next btn-slider-1"><i class="fa-solid fa-chevron-right"></i></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>