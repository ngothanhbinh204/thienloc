<?php
get_header();

// Get current taxonomy term
$current_term = get_queried_object();
$term_id = "customer_cat_{$current_term->term_id}";

// Find the page using page-customers.php template as fallback
$customers_page_id = null;
$pages = get_pages(array(
	'meta_key' => '_wp_page_template',
	'meta_value' => 'templates/page-customers.php'
));
if (!empty($pages)) {
	$customers_page_id = $pages[0]->ID;
}

/**
 * Helper function to get field with fallback
 * Priority: Term ACF -> Page ACF -> Default
 */
function get_field_with_fallback($field_name, $term_id, $page_id, $default = '') {
	// Try to get from term first
	$value = get_field($field_name, $term_id);
	
	// If empty, try to get from page
	if (empty($value) && $page_id) {
		$value = get_field($field_name, $page_id);
	}
	
	// If still empty, return default
	return !empty($value) ? $value : $default;
}
?>

<main>
	<?php get_template_part('modules/common/banner') ?>

	<div class="">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<!-- Commitment Section -->
		<section class="section-product-commitment section-py">
			<div class="container">
				<div class="commitment-header">
					<div class="header-icon">
						<?php
						$commitment_icon = get_field_with_fallback('commitment_icon', $term_id, $customers_page_id);
						if ($commitment_icon) :
						?>
							<img class="lozad" data-src="<?= esc_url($commitment_icon); ?>" alt="" />
						<?php else: ?>
							<img class="lozad" data-src="<?= get_template_directory_uri() ?>/assets/images/check-icon.png"
								alt="" />
						<?php endif; ?>
					</div>
					<h2><?= get_field_with_fallback('commitment_heading', $term_id, $customers_page_id, 'Cam kết sản phẩm – Chính hãng & Hiệu quả vượt trội'); ?>
					</h2>
				</div>
				<div class="commitment-content">
					<div class="commitment-image">
						<?php
						$commitment_image = get_field_with_fallback('commitment_image', $term_id, $customers_page_id);
						if ($commitment_image) :
						?>
							<img class="lozad" data-src="<?= esc_url($commitment_image); ?>" alt="" />
						<?php else: ?>
							<img class="lozad"
								data-src="<?= get_template_directory_uri() ?>/assets/images/commitment-img.jpg" alt="" />
						<?php endif; ?>
					</div>
					<div class="commitment-info">
						<?php
						// Try term ACF first, then page ACF
						$has_rows_term = have_rows('commitment_list', $term_id);
						$has_rows_page = $customers_page_id && have_rows('commitment_list', $customers_page_id);
						
						if ($has_rows_term || $has_rows_page) :
							$source_id = $has_rows_term ? $term_id : $customers_page_id;
							while (have_rows('commitment_list', $source_id)) : the_row();
						?>
								<div class="info-item">
									<h3 class="info-title"><?php the_sub_field('item_title'); ?></h3>
									<div class="body-18"><?php the_sub_field('item_desc'); ?></div>
								</div>
							<?php
							endwhile;
						else:
							?>
							<div class="info-item">
								<h3 class="info-title">Hàng chính hãng 100%</h3>
								<p class="body-18">Tất cả sản phẩm đều được nhập khẩu trực tiếp từ các thương hiệu nông
									nghiệp uy tín hàng đầu như Syngenta, Bayer CropScience, Vinh Thanh, MAP Pacific,
									Fertiberia, NAM Agro, ADOB, SGCT GROUP...</p>
							</div>
							<div class="info-item">
								<h3 class="info-title">Chất lượng đã được kiểm chứng</h3>
								<p class="body-18">Chúng tôi cam kết mang đến giá trị thực sự và lâu dài cho nhà nông.</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<!-- Customers List Section -->
		<section class="section-brand-partners section-py">
			<div class="container space-y-5">
				<div class="partners-header">
					<div class="header-icon">
						<?php
						$partners_icon = get_field_with_fallback('partners_icon', $term_id, $customers_page_id);
						if ($partners_icon) :
						?>
							<img class="lozad" data-src="<?= esc_url($partners_icon); ?>" alt="" />
						<?php else: ?>
							<img class="lozad" data-src="<?= get_template_directory_uri() ?>/assets/images/check-icon.png"
								alt="" />
						<?php endif; ?>
					</div>
					<h2><?= get_field_with_fallback('partners_heading', $term_id, $customers_page_id, single_term_title('', false)); ?></h2>
				</div>
				<div class="partners-description body-18">
					<?php 
					$partners_desc = get_field_with_fallback('partners_desc', $term_id, $customers_page_id);
					if ($partners_desc) {
						echo $partners_desc;
					} else {
						echo term_description();
					}
					?>
				</div>

				<div class="partners-grid">
					<?php
					// Main query đã được WordPress tự động set cho taxonomy archive
					// Chỉ cần dùng have_posts() và the_post()
					if (have_posts()) :
						while (have_posts()) : the_post();
					?>
							<div class="partner-card">
								<div class="partner-logo">
									<?php if (has_post_thumbnail()) : ?>
										<?= get_image_attrachment(get_post_thumbnail_id(), 'image'); ?>
									<?php endif; ?>
								</div>
								<div class="partner-info">
									<h4 class="partner-name"><?php the_title(); ?></h4>
									<div class="partner-description body-18 ">
										<?php the_excerpt(); ?>
									</div>
									<a class="btn btn-primary w-fit" href="<?php the_permalink(); ?>">Xem thêm</a>
								</div>
							</div>
						<?php
						endwhile;
					else :
						echo '<p>Đang cập nhật...</p>';
					endif;
					?>
				</div>

				<!-- Pagination -->
			<?php
			global $wp_query;
			if ($wp_query->max_num_pages > 1) :
			?>
				<div class="pagination mt-8 flex justify-center">
					<?php 
					wp_bootstrap_pagination();  // Auto-detect customer_cat và dùng ?pagenumber=
					?>
				</div>
			<?php endif; ?>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
