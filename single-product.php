<?php get_header(); ?>

<main>
	<div class="wrapper-gap-top">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<section class="section-product-detail section-py">
			<div class="container">
				<div class="wrapper">
					<div class="product-detail-left">
						<div class="product-detail-hero">
							<?php
							$gallery = get_field('product_gallery');
							// If no gallery, use featured image as single item
							if (!$gallery && has_post_thumbnail()) {
								$gallery = array(get_post_thumbnail_id());
							}
							?>
							<div class="product-gallery">
								<?php if ($gallery) : ?>
								<div class="gallery-thumbnails">
									<button class="thumb-nav thumb-prev btn-slider-2"><i
											class="fa-solid fa-chevron-up"></i></button>
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
									<button class="thumb-nav thumb-next btn-slider-2"><i
											class="fa-solid fa-chevron-down"></i></button>
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

							<div class="product-info">
								<h1 class="title-36 text-primary-1"><?php the_title(); ?></h1>
								<div class="product-description body-18">
									<?= apply_filters('the_content', get_post_field('post_content', get_the_ID())); ?>
								</div>
								<a class="btn btn-primary" href="#contact-form">Liên hệ tư vấn</a>
							</div>
						</div>

						<?php
						$info_content = get_field('info_content');
						$advantages_content = get_field('advantages_content');
						$usage_content = get_field('usage_content');
						$maintenance_content = get_field('maintenance_content');
						?>
						<div class="product-tabs tabs-wrapper">
							<div class="tabs-navigation">
								<button class="tab-btn active" data-tab="tab-info">Thông tin sản phẩm</button>
								<button class="tab-btn" data-tab="tab-advantages">Ưu đãi và chiết khấu</button>
								<button class="tab-btn" data-tab="tab-usage">Hướng dẫn sử dụng</button>
								<button class="tab-btn" data-tab="tab-maintenance">Hướng dẫn bảo quản</button>
							</div>
							<div class="tabs-content">
								<div class="wrapper-content-tab collapsed">
									<div class="tab-pane active" id="tab-info">
										<h3 class="title-36">Thông tin sản phẩm</h3>
										<div class="tab-content-body body-18">
											<?= $info_content ? $info_content : '<p>Đang cập nhật...</p>'; ?>
										</div>
									</div>
									<div class="tab-pane" id="tab-advantages">
										<h3 class="title-36">Ưu đãi và chiết khấu</h3>
										<div class="tab-content-body body-18">
											<?= $advantages_content ? $advantages_content : '<p>Đang cập nhật...</p>'; ?>
										</div>
									</div>
									<div class="tab-pane" id="tab-usage">
										<h3 class="title-36">Hướng dẫn sử dụng</h3>
										<div class="tab-content-body body-18">
											<?= $usage_content ? $usage_content : '<p>Đang cập nhật...</p>'; ?>
										</div>
									</div>
									<div class="tab-pane" id="tab-maintenance">
										<h3 class="title-36">Hướng dẫn bảo quản</h3>
										<div class="tab-content-body body-18">
											<?= $maintenance_content ? $maintenance_content : '<p>Đang cập nhật...</p>'; ?>
										</div>
									</div>
								</div>
								<div class="view-more-container text-center mt-8">
									<button
										class="btn-view-more text-primary-2 font-normal flex items-center justify-center gap-3 mx-auto"
										type="button"><span>Xem thêm</span><i
											class="fa-solid fa-chevron-down"></i></button>
								</div>
							</div>
						</div>

						<div class="product-related">
							<h2>Sản phẩm gợi ý</h2>
							<div class="wrapper-related-products relative">
								<div class="btn-relative-prev btn-slider-2"><i class="fa-solid fa-chevron-left"></i>
								</div>
								<div class="swiper swiper-related-products">
									<div class="swiper-wrapper">
										<?php
										$related_args = array(
											'post_type'      => 'product',
											'posts_per_page' => 8,
											'post__not_in'   => array(get_the_ID()),
											'orderby'        => 'rand',
										);
										// If has category, filter by category
										$terms = get_the_terms(get_the_ID(), 'product_cat');
										if ($terms && !is_wp_error($terms)) {
											$term_ids = wp_list_pluck($terms, 'term_id');
											$related_args['tax_query'] = array(
												array(
													'taxonomy' => 'product_cat',
													'field'    => 'term_id',
													'terms'    => $term_ids,
												),
											);
										}

										$related_query = new WP_Query($related_args);

										if ($related_query->have_posts()) :
											while ($related_query->have_posts()) : $related_query->the_post();
												$rel_img_id = get_post_thumbnail_id();
												$rel_specs = get_field('product_specs');
										?>
										<div class="swiper-slide">
											<div class="slide-inner">
												<article class="product-card">
													<a class="product-link" href="<?php the_permalink(); ?>">
														<div class="product-image">
															<?= get_image_attrachment($rel_img_id, 'image'); ?>
														</div>
														<div class="product-info">
															<h3 class="product-name"><?php the_title(); ?></h3>
															<?php if ($rel_specs) : ?>
															<div class="product-specs">
																<?php 
																			$count = 0;
																			foreach ($rel_specs as $spec) : 
																				if ($count >= 3) break;
																			?>
																<p><?= esc_html($spec['text']); ?></p>
																<?php 
																				$count++;
																			endforeach; 
																			?>
															</div>
															<?php endif; ?>
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

					<div class="product-detail-right">
						<div class="sidebar-widget widget-latest-posts">
							<h3 class="widget-title">Bài viết mới</h3>
							<div class="widget-content">
								<?php
								$latest_posts = new WP_Query(array(
									'post_type' => 'post',
									'posts_per_page' => 5,
								));
								if ($latest_posts->have_posts()) :
									while ($latest_posts->have_posts()) : $latest_posts->the_post();
								?>
								<div class="post-item">
									<a href="<?php the_permalink(); ?>"
										class="body-16 hover:text-primary-1 transition-colors">
										<?php the_title(); ?>
									</a>
								</div>
								<?php
									endwhile;
									wp_reset_postdata();
								endif;
								?>
							</div>
						</div>
						<div class="sidebar-widget widget-services">
							<h3 class="widget-title">Dịch vụ chính</h3>
							<div class="widget-content">
								<?php
								$services = new WP_Query(array(
									'post_type' => 'service',
									'posts_per_page' => 5,
								));
								if ($services->have_posts()) :
									while ($services->have_posts()) : $services->the_post();
								?>
								<div class="service-item">
									<a href="<?php the_permalink(); ?>"
										class="body-16 hover:text-primary-1 transition-colors">
										<?php the_title(); ?>
									</a>
								</div>
								<?php
									endwhile;
									wp_reset_postdata();
								endif;
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>