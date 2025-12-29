<?php get_header(); ?>

<main>
	<div class="wrapper-gap-top">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<section class="section-manufacturer-detail section-py">
			<div class="container">
				<div class="manufacturer-detail-left">

					<div class="manufacturer-header">
						<div class="manufacturer-logo">
							<?php if (has_post_thumbnail()) : ?>
							<?= get_image_attrachment(get_post_thumbnail_id(), 'image'); ?>
							<?php endif; ?>
						</div>

						<div class="manufacturer-info">
							<div class="manufacturer-meta">
								<h1 class="title-36 text-primary-1"><?php the_title(); ?></h1>
								<?php
								$customer_code = get_field('customer_code');
								if ($customer_code) :
								?>
								<div class="meta-item">
									<span class="meta-label">
										<?php _e('Mã sản phẩm:', 'canhcamtheme'); ?>
									</span>
									<span class="meta-value"><?= esc_html($customer_code); ?></span>
								</div>
								<?php endif; ?>
							</div>

							<div class="manufacturer-description body-18">
								<?php the_content(); ?>
							</div>

							<?php
							$specs = get_field('customer_specs');
							if ($specs) :
							?>
							<div class="manufacturer-specs">
								<?php foreach ($specs as $spec) : ?>
								<div class="spec-row">
									<div class="spec-item <?= isset($spec['full_width']) && $spec['full_width'] ? 'spec-full' : '' ?>">
										<div class="spec-label"> <?= esc_html($spec['spec_label']); ?></div>
										<div class="spec-value"><?= esc_html($spec['spec_value']); ?></div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php
		$products_made = get_field('products_made');
		$product_images = get_field('product_images');
		$usage_guide = get_field('usage_guide');
		$maintenance_guide = get_field('maintenance_guide');
		?>
		<section class="section-tabs-customer section-py">
			<div class="manufacturer-tabs tabs-wrapper">
				<div class="tabs-navigation">
					<button class="tab-btn active"
						data-tab="tab-products"><?php _e('Sản phẩm đã làm', 'canhcamtheme'); ?></button>
					<button class="tab-btn"
						data-tab="tab-images"><?php _e('Hình ảnh sản phẩm', 'canhcamtheme'); ?></button>
					<button class="tab-btn"
						data-tab="tab-usage"><?php _e('Hướng dẫn sử dụng', 'canhcamtheme'); ?></button>
					<button class="tab-btn"
						data-tab="tab-maintenance"><?php _e('Hướng dẫn bảo quản', 'canhcamtheme'); ?></button>
				</div>
			</div>
			<div class="container">
				<div class="tabs-content">
					<!-- Tab 1: Products Made -->
					<div class="tab-pane active" id="tab-products">
						<div class="wrapper-content-tab collapsed" data-collapsed-height="500">
							<h3 class="title-36 mb-4"><?php _e('Sản phẩm đã làm', 'canhcamtheme'); ?></h3>
							<div class="tab-content-body body-18">
								<?= $products_made ? $products_made : '<p>' . __('Đang cập nhật...', 'canhcamtheme') . '</p>'; ?>
							</div>
						</div>

						<div class="view-more-container text-center mt-8">
							<button
								class="btn-view-more text-primary-2 font-normal flex items-center justify-center gap-3 mx-auto"
								type="button" aria-expanded="false"
								aria-label="<?php _e('Xem thêm nội dung', 'canhcamtheme'); ?>">
								<span><?php _e('Xem thêm', 'canhcamtheme'); ?></span>
								<i class="fa-solid fa-chevron-down"></i>
							</button>
						</div>
					</div>

					<!-- Tab 2: Product Images -->
					<div class="tab-pane" id="tab-images">
						<div class="wrapper-content-tab collapsed" data-collapsed-height="500">
							<h3 class="title-36 mb-4"><?php _e('Hình ảnh sản phẩm', 'canhcamtheme'); ?></h3>
							<div class="tab-content-body body-18">
								<?php if ($product_images) : ?>
								<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
									<?php foreach ($product_images as $img_id) : ?>
									<div class="gallery-item">
										<?= get_image_attrachment($img_id, 'image'); ?>
									</div>
									<?php endforeach; ?>
								</div>
								<?php else : ?>
								<p><?php _e('Đang cập nhật...', 'canhcamtheme'); ?></p>
								<?php endif; ?>
							</div>
						</div>
						<div class="view-more-container text-center mt-8">
							<button
								class="btn-view-more text-primary-2 font-normal flex items-center justify-center gap-3 mx-auto"
								type="button" aria-expanded="false"
								aria-label="<?php _e('Xem thêm nội dung', 'canhcamtheme'); ?>">
								<span><?php _e('Xem thêm', 'canhcamtheme'); ?></span>
								<i class="fa-solid fa-chevron-down"></i>
							</button>
						</div>
					</div>

					<!-- Tab 3: Usage Guide -->
					<div class="tab-pane" id="tab-usage">
						<div class="wrapper-content-tab collapsed" data-collapsed-height="500">
							<h3 class="title-36 mb-4"><?php _e('Hướng dẫn sử dụng', 'canhcamtheme'); ?></h3>
							<div class="tab-content-body body-18">
								<?= $usage_guide ? $usage_guide : '<p>' . __('Đang cập nhật...', 'canhcamtheme') . '</p>'; ?>
							</div>
						</div>
						<div class="view-more-container text-center mt-8">
							<button
								class="btn-view-more text-primary-2 font-normal flex items-center justify-center gap-3 mx-auto"
								type="button" aria-expanded="false"
								aria-label="<?php _e('Xem thêm nội dung', 'canhcamtheme'); ?>">
								<span><?php _e('Xem thêm', 'canhcamtheme'); ?></span>
								<i class="fa-solid fa-chevron-down"></i>
							</button>
						</div>
					</div>

					<!-- Tab 4: Maintenance -->
					<div class="tab-pane" id="tab-maintenance">
						<div class="wrapper-content-tab collapsed" data-collapsed-height="500">
							<h3 class="title-36 mb-4"><?php _e('Hướng dẫn bảo quản', 'canhcamtheme'); ?></h3>
							<div class="tab-content-body body-18">
								<?= $maintenance_guide ? $maintenance_guide : '<p>' . __('Đang cập nhật...', 'canhcamtheme') . '</p>'; ?>
							</div>
						</div>
						<div class="view-more-container text-center mt-8">
							<button
								class="btn-view-more text-primary-2 font-normal flex items-center justify-center gap-3 mx-auto"
								type="button" aria-expanded="false"
								aria-label="<?php _e('Xem thêm nội dung', 'canhcamtheme'); ?>">
								<span><?php _e('Xem thêm', 'canhcamtheme'); ?></span>
								<i class="fa-solid fa-chevron-down"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="section-related-customers section-py">
			<div class="container">
				<h2 class="title-36 text-center text-primary-1 mb-10"><?php _e('Khách hàng liên quan', 'canhcamtheme'); ?>
				</h2>
				<div class="related-customers-slider relative">
					<div class="swiper swiper-related-customers">
						<div class="swiper-wrapper">
							<?php
							$related_args = array(
								'post_type'      => 'customer',
								'posts_per_page' => 8,
								'post__not_in'   => array(get_the_ID()),
								'orderby'        => 'rand',
							);
							$related_query = new WP_Query($related_args);

							if ($related_query->have_posts()) :
								while ($related_query->have_posts()) : $related_query->the_post();
							?>
							<div class="swiper-slide">
								<div class="customer-card ">
									<div class="card-img">
										<?php if (has_post_thumbnail()) : ?>
										<a href="<?php the_permalink(); ?>">
											<?= get_image_attrachment(get_post_thumbnail_id(), 'image'); ?>
										</a>
										<?php endif; ?>
									</div>
									<div class="card-body">
										<h4 class="card-title">
											<a href="<?php the_permalink(); ?>"
												class="hover:text-primary-1 transition-colors">
												<?= the_title(); ?></a>
										</h4>
										<a class="btn btn-primary"
											href="<?php the_permalink(); ?>"><?php _e('Xem chi tiết', 'canhcamtheme'); ?></a>
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
					<div class="swiper-navigation center-y">
						<div class="btn-prev btn-slider-2"><i class="fa-solid fa-chevron-left"></i></div>
						<div class="btn-next btn-slider-2"><i class="fa-solid fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>