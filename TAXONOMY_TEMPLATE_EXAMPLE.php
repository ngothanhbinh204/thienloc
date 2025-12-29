<?php
/**
 * Example: taxonomy-product_cat.php
 * 
 * This template is used when viewing a product category archive.
 * WordPress automatically handles pagination - no custom code needed!
 */

get_header();

// Get current category term object
$current_term = get_queried_object();

// Banner handling (if needed)
$term_context_id = $current_term instanceof WP_Term 
	? "{$current_term->taxonomy}_{$current_term->term_id}" 
	: null;

// ... banner code ...

?>

<div class="wrapper-gap-top">
	<?php get_template_part('modules/common/breadcrumb'); ?>

	<section class="section-product-listing section-py">
		<div class="container">
			<div class="wrapper-product-listing">
				<aside class="product-sidebar">
					<!-- Category menu sidebar -->
				</aside>

				<main class="product-main">
					<div class="product-header">
						<h1 class="product-title"><?php single_term_title(); ?></h1>
						
						<?php 
						// Sort filter
						$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : ''; 
						?>
						<div class="product-sort">
							<label for="product-sort-select">Lọc theo</label>
							<select id="product-sort-select" class="form-select" onchange="location = this.value;">
								<?php 
								$base_url = remove_query_arg(['sort', 'page', 'paged']);
								?>
								<option value="<?= esc_url($base_url) ?>" <?= empty($sort) ? 'selected' : '' ?>>Tất cả</option>
								<option value="<?= esc_url(add_query_arg('sort', 'name_asc', $base_url)) ?>" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Từ A-Z</option>
								<option value="<?= esc_url(add_query_arg('sort', 'name_desc', $base_url)) ?>" <?= $sort == 'name_desc' ? 'selected' : '' ?>>Từ Z-A</option>
								<option value="<?= esc_url(add_query_arg('sort', 'newest', $base_url)) ?>" <?= $sort == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
								<option value="<?= esc_url(add_query_arg('sort', 'oldest', $base_url)) ?>" <?= $sort == 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
							</select>
						</div>
					</div>

					<?php
					// Handle sorting via pre_get_posts hook (better than query_posts)
					// This should be in functions.php, but shown here for reference:
					/*
					add_action('pre_get_posts', function($query) {
						if (!is_admin() && $query->is_main_query() && is_tax('product_cat')) {
							$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : '';
							if ($sort) {
								switch ($sort) {
									case 'name_asc':
										$query->set('orderby', 'title');
										$query->set('order', 'ASC');
										break;
									case 'name_desc':
										$query->set('orderby', 'title');
										$query->set('order', 'DESC');
										break;
									case 'oldest':
										$query->set('orderby', 'date');
										$query->set('order', 'ASC');
										break;
									case 'newest':
									default:
										$query->set('orderby', 'date');
										$query->set('order', 'DESC');
										break;
								}
								$query->set('ignore_custom_sort', true);
							}
						}
					});
					*/
					?>

					<div class="product-grid">
						<?php
						// WordPress main query is already set up!
						// No need for custom WP_Query
						if (have_posts()) :
							while (have_posts()) : the_post();
								$idProduct = get_the_ID();
								$specs = get_field('product_specs');
						?>
							<article class="product-card">
								<a class="product-link" href="<?php the_permalink(); ?>">
									<div class="product-image">
										<?php echo get_image_post($idProduct, 'image'); ?>
									</div>
									<div class="product-info">
										<h3 class="product-name"><?php the_title(); ?></h3>
										<?php if ($specs && is_array($specs)) : ?>
										<div class="product-specs">
											<?php foreach ($specs as $row) : ?>
												<?php if (!empty($row['text'])) : ?>
													<div><?php echo esc_html($row['text']); ?></div>
												<?php endif; ?>
											<?php endforeach; ?>
										</div>
										<?php endif; ?>
									</div>
								</a>
							</article>
						<?php
							endwhile;
						else :
							echo '<p>Chưa có sản phẩm nào.</p>';
						endif;
						?>
					</div>

					<?php
					// PAGINATION: Simple and works perfectly!
					// WordPress main query handles everything automatically
					// Just call wp_bootstrap_pagination() - no parameters needed!
					wp_bootstrap_pagination();
					?>
				</main>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>

