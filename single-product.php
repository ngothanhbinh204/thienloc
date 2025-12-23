<?php get_header(); ?>

<main>
	<div class="wrapper-gap-top">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<section class="section-product-detail section-py">
			<div class="container">
				<div class="wrapper">
					<div class="product-detail-left">

						<!-- PRODUCT GALLERY -->
						<div class="product-detail-hero">
							<?php
							$gallery = get_field('product_gallery');
							if (!$gallery && has_post_thumbnail()) {
								$gallery = [get_post_thumbnail_id()];
							}
							?>

							<div class="product-gallery">
								<?php if ($gallery) : ?>
								<div class="gallery-thumbnails">
									<button class="thumb-nav thumb-prev btn-slider-2"
										aria-label="<?php esc_attr_e('Ảnh trước', 'canhcamtheme'); ?>">
										<i class="fa-solid fa-chevron-up"></i>
									</button>

									<div class="swiper thumbs-slider">
										<div class="swiper-wrapper">
											<?php foreach ($gallery as $img_id) : ?>
											<div class="swiper-slide">
												<div class="thumbnail-item">
													<?= get_image_attrachment($img_id, 'image'); ?>
												</div>
											</div>
											<?php endforeach; ?>
										</div>
									</div>

									<button class="thumb-nav thumb-next btn-slider-2"
										aria-label="<?php esc_attr_e('Ảnh tiếp theo', 'canhcamtheme'); ?>">
										<i class="fa-solid fa-chevron-down"></i>
									</button>
								</div>

								<div class="gallery-main">
									<div class="swiper swiper-product-gallery">
										<div class="swiper-wrapper">
											<?php foreach ($gallery as $img_id) : ?>
											<div class="swiper-slide">
												<div class="img-wrapper">
													<?= get_image_attrachment($img_id, 'image'); ?>
												</div>
											</div>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
								<?php endif; ?>
							</div>

							<!-- PRODUCT INFO -->
							<div class="product-info">
								<h1 class="title-36 text-primary-1"><?php the_title(); ?></h1>

								<div class="product-description body-18">
									<?= apply_filters('the_content', get_post_field('post_content', get_the_ID())); ?>
								</div>

								<a class="btn btn-primary" href="#contact-form">
									<?= esc_html__('Liên hệ tư vấn', 'canhcamtheme'); ?>
								</a>
							</div>
						</div>

						<?php
						$info_content        = get_field('info_content');
						$advantages_content  = get_field('advantages_content');
						$usage_content       = get_field('usage_content');
						$maintenance_content = get_field('maintenance_content');
						?>

						<!-- PRODUCT TABS -->
						<div class="product-tabs tabs-wrapper">
							<div class="tabs-navigation">
								<button class="tab-btn active"
									data-tab="tab-info"><?= esc_html__('Thông tin sản phẩm', 'canhcamtheme'); ?></button>
								<button class="tab-btn"
									data-tab="tab-advantages"><?= esc_html__('Ưu đãi và chiết khấu', 'canhcamtheme'); ?></button>
								<button class="tab-btn"
									data-tab="tab-usage"><?= esc_html__('Hướng dẫn sử dụng', 'canhcamtheme'); ?></button>
								<button class="tab-btn"
									data-tab="tab-maintenance"><?= esc_html__('Hướng dẫn bảo quản', 'canhcamtheme'); ?></button>
							</div>

							<div class="tabs-content">
								<div class="wrapper-content-tab collapsed">

									<?php
									$tabs = [
										'tab-info'        => ['label' => __('Thông tin sản phẩm', 'canhcamtheme'), 'content' => $info_content],
										'tab-advantages'  => ['label' => __('Ưu đãi và chiết khấu', 'canhcamtheme'), 'content' => $advantages_content],
										'tab-usage'       => ['label' => __('Hướng dẫn sử dụng', 'canhcamtheme'), 'content' => $usage_content],
										'tab-maintenance' => ['label' => __('Hướng dẫn bảo quản', 'canhcamtheme'), 'content' => $maintenance_content],
									];
									?>

									<?php foreach ($tabs as $id => $tab) : ?>
									<div class="tab-pane <?= $id === 'tab-info' ? 'active' : ''; ?>"
										id="<?= esc_attr($id); ?>">
										<h3 class="title-36"><?= esc_html($tab['label']); ?></h3>
										<div class="tab-content-body body-18">
											<?= $tab['content']
													? $tab['content']
													: '<p>' . esc_html__('Đang cập nhật...', 'canhcamtheme') . '</p>'; ?>
										</div>
									</div>
									<?php endforeach; ?>

								</div>

								<div class="view-more-container text-center mt-8">
									<button
										class="btn-view-more text-primary-2 font-normal flex items-center justify-center gap-3 mx-auto"
										type="button">
										<span><?= esc_html__('Xem thêm', 'canhcamtheme'); ?></span>
										<i class="fa-solid fa-chevron-down"></i>
									</button>
								</div>
							</div>
						</div>

						<!-- RELATED PRODUCTS -->
						<div class="product-related">
							<h2><?= esc_html__('Sản phẩm gợi ý', 'canhcamtheme'); ?></h2>

							<div class="wrapper-related-products relative">
								<div class="btn-relative-prev btn-slider-2"><i class="fa-solid fa-chevron-left"></i>
								</div>

								<div class="swiper swiper-related-products">
									<div class="swiper-wrapper">
										<?php
										$args = [
											'post_type'      => 'product',
											'posts_per_page' => 8,
											'post__not_in'   => [get_the_ID()],
											'orderby'        => 'rand',
										];

										$terms = get_the_terms(get_the_ID(), 'product_cat');
										if ($terms && !is_wp_error($terms)) {
											$args['tax_query'][] = [
												'taxonomy' => 'product_cat',
												'field'    => 'term_id',
												'terms'    => wp_list_pluck($terms, 'term_id'),
											];
										}

										$q = new WP_Query($args);
										if ($q->have_posts()) :
											while ($q->have_posts()) : $q->the_post();
										?>
										<div class="swiper-slide">
											<div class="slide-inner">
												<article class="product-card">
													<a href="<?php the_permalink(); ?>" class="product-link">
														<div class="product-image">
															<?= get_image_attrachment(get_post_thumbnail_id(), 'image'); ?>
														</div>
														<div class="product-info">
															<h3 class="product-title"><?php the_title(); ?></h3>
															<span class="btn btn-primary">
																<?= esc_html__('Xem chi tiết', 'canhcamtheme'); ?>
															</span>
														</div>
													</a>
												</article>
											</div>
										</div>
										<?php
											endwhile;
											wp_reset_postdata();
										endif;
										?>
									</div>
								</div>

								<div class="btn-relative-next btn-slider-2"><i class="fa-solid fa-chevron-right"></i>
								</div>
							</div>
						</div>
					</div>

					<!-- SIDEBAR -->
					<div class="product-detail-right">

						<div class="sidebar-widget">
							<h3 class="widget-title"><?= esc_html__('Bài viết mới', 'canhcamtheme'); ?></h3>
							<div class="widget-content">
								<?php
							$posts = new WP_Query(['post_type' => 'post', 'posts_per_page' => 5]);
							while ($posts->have_posts()) : $posts->the_post();
							?>
								<a href="<?php the_permalink(); ?>"
									class="block post-item body-16 hover:text-primary-1">
									<?php the_title(); ?>
								</a>
								<?php endwhile; wp_reset_postdata(); ?>
							</div>
						</div>

						<div class="sidebar-widget">
							<h3 class="widget-title"><?= esc_html__('Dịch vụ chính', 'canhcamtheme'); ?></h3>
							<div class="widget-content">
								<?php
							$services = new WP_Query(['post_type' => 'service', 'posts_per_page' => 5]);
							while ($services->have_posts()) : $services->the_post();
							?>
								<a href="<?php the_permalink(); ?>"
									class="block post-item body-16 hover:text-primary-1">
									<?php the_title(); ?>
								</a>
								<?php endwhile; wp_reset_postdata(); ?>
							</div>
						</div>

					</div>
				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>