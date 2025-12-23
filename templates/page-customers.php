<?php
/*
Template Name: Customers
*/
get_header();
?>

<main>
	<?php get_template_part('modules/common/banner')?>

	<div class="">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<!-- Commitment Section -->
		<section class="section-product-commitment section-py">
			<div class="container">
				<div class="commitment-header">
					<div class="header-icon">
						<?php 
						$commitment_icon = get_field('commitment_icon');
						if ($commitment_icon) :
						?>
						<img class="lozad" data-src="<?= esc_url($commitment_icon); ?>" alt="" />
						<?php else: ?>
						<img class="lozad" data-src="<?= get_template_directory_uri() ?>/assets/images/check-icon.png"
							alt="" />
						<?php endif; ?>
					</div>
					<h2><?= get_field('commitment_heading') ?: 'Cam kết sản phẩm – Chính hãng & Hiệu quả vượt trội'; ?>
					</h2>
				</div>
				<div class="commitment-content">
					<div class="commitment-image">
						<?php 
						$commitment_image = get_field('commitment_image');
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
						if (have_rows('commitment_list')) :
							while (have_rows('commitment_list')) : the_row();
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

		<!-- Partners/Customers List Section -->
		<section class="section-brand-partners section-py">
			<div class="container space-y-5">
				<div class="partners-header">
					<div class="header-icon">
						<?php 
						$partners_icon = get_field('partners_icon');
						if ($partners_icon) :
						?>
						<img class="lozad" data-src="<?= esc_url($partners_icon); ?>" alt="" />
						<?php else: ?>
						<img class="lozad" data-src="<?= get_template_directory_uri() ?>/assets/images/check-icon.png"
							alt="" />
						<?php endif; ?>
					</div>
					<h2><?= get_field('partners_heading') ?: 'Đối tác của <span class="text-primary">NÔNG DƯỢC XANH</span>'; ?>
					</h2>
				</div>
				<div class="partners-description body-18">
					<?= get_field('partners_desc') ?: 'CÔNG TY TNHH THƯƠNG MẠI NÔNG DƯỢC XANH là chuỗi cửa hàng cung cấp vật tư nông nghiệp chính hãng với chất lượng vượt trội; chuyên phục vụ người nông dân trên hành trình canh tác bền vững và hiệu quả.'; ?>
				</div>

				<div class="partners-grid">
					<?php
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$args = array(
						'post_type' => 'customer',
						'posts_per_page' => 12,
						'paged' => $paged,
						'orderby' => 'date',
						'order' => 'DESC'
					);
					$query = new WP_Query($args);

					if ($query->have_posts()) :
						while ($query->have_posts()) : $query->the_post();
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
						wp_reset_postdata();
					else :
						echo '<p>Đang cập nhật...</p>';
					endif;
					?>
				</div>

				<!-- Pagination -->
				<?php if ($query->max_num_pages > 1) : ?>
				<div class="pagination mt-8 flex justify-center">
					<?php
						echo paginate_links(array(
							'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
							'format' => '?paged=%#%',
							'current' => max(1, get_query_var('paged')),
							'total' => $query->max_num_pages,
							'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
							'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
							'type' => 'list',
							'end_size' => 3,
							'mid_size' => 3
						));
						?>
				</div>
				<?php endif; ?>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>