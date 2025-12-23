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
									<span class="meta-label">Mã đối tác:</span>
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
									<div class="spec-item">
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
					<button class="tab-btn active" data-tab="tab-products">Sản phẩm đã làm</button>
					<button class="tab-btn" data-tab="tab-images">Hình ảnh sản phẩm</button>
					<button class="tab-btn" data-tab="tab-usage">Hướng dẫn sử dụng</button>
					<button class="tab-btn" data-tab="tab-maintenance">Hướng dẫn bảo quản</button>
				</div>
			</div>
			<div class="container">
				<div class="tabs-content">
					<div class="wrapper-content-tab collapsed">
						<div class="tab-pane active" id="tab-products">
							<h3 class="title-36 mb-4">Sản phẩm đã làm</h3>
							<div class="tab-content-body body-18">
								<?= $products_made ? $products_made : '<p>Đang cập nhật...</p>'; ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-images">
							<h3 class="title-36 mb-4">Hình ảnh sản phẩm</h3>
							<div class="tab-content-body body-18">
								<?php if ($product_images) : ?>
								<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
									<?php foreach ($product_images as $img_id) : ?>
									<div class="gallery-item">
										<?= get_image_attrachment($img_id, 'medium'); ?>
									</div>
									<?php endforeach; ?>
								</div>
								<?php else : ?>
								<p>Đang cập nhật...</p>
								<?php endif; ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-usage">
							<h3 class="title-36 mb-4">Hướng dẫn sử dụng</h3>
							<div class="tab-content-body body-18">
								<?= $usage_guide ? $usage_guide : '<p>Đang cập nhật...</p>'; ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-maintenance">
							<h3 class="title-36 mb-4">Hướng dẫn bảo quản</h3>
							<div class="tab-content-body body-18">
								<?= $maintenance_guide ? $maintenance_guide : '<p>Đang cập nhật...</p>'; ?>
							</div>
						</div>
					</div>
					<div class="view-more-container text-center mt-8">
						<button
							class="btn-view-more text-primary-2 font-normal flex items-center justify-center gap-3 mx-auto"
							type="button"><span>Xem thêm</span><i class="fa-solid fa-chevron-down"></i></button>
					</div>
				</div>
			</div>
		</section>

		<section class="section-related-customers section-py">
			<div class="container">
				<h2 class="title-36 text-center text-primary-1 mb-10">Khách hàng liên quan</h2>
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
								<div class="customer-card bg-white p-4 rounded-lg h-full">
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
										<a class="btn btn-primary" href="<?php the_permalink(); ?>">Xem chi tiết</a>
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