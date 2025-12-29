<?php


get_header();

// Get current category term object
$current_term = get_queried_object();
$term_context_id = $current_term instanceof WP_Term 
	? "{$current_term->taxonomy}_{$current_term->term_id}" 
	: null;

// Check if current category has banner data
$term_has_banner = false;
if ($term_context_id) {
	$term_banner = get_field('banner_select_page', $term_context_id);
	$term_has_banner = !empty($term_banner);
}

// Get Posts page (blog page) ID dynamically
$posts_page_id = get_option('page_for_posts');

// Check if Posts page has banner data (for fallback)
$posts_page_has_banner = false;
if ($posts_page_id) {
	$posts_page_banner = get_field('banner_select_page', $posts_page_id);
	$posts_page_has_banner = !empty($posts_page_banner);
}

// Determine if we should render banner and which source to use
$should_render_banner = $term_has_banner || $posts_page_has_banner;

// If term doesn't have banner but Posts page does, temporarily set globals for fallback
if (!$term_has_banner && $posts_page_has_banner && $posts_page_id) {
	// Store original queried object
	$original_queried_object = $GLOBALS['wp_query']->queried_object;
	$original_queried_object_id = $GLOBALS['wp_query']->queried_object_id;
	
	// Temporarily override to Posts page for banner template
	$GLOBALS['wp_query']->queried_object = get_post($posts_page_id);
	$GLOBALS['wp_query']->queried_object_id = $posts_page_id;
}

// Render banner if available
if ($should_render_banner) {
	get_template_part('modules/common/banner');
}

// Restore original queried object if it was overridden
if (!$term_has_banner && $posts_page_has_banner && $posts_page_id) {
	$GLOBALS['wp_query']->queried_object = $original_queried_object;
	$GLOBALS['wp_query']->queried_object_id = $original_queried_object_id;
}

// Determine extra class for main when no banner
$main_extra_class = !$should_render_banner ? ' wrapper-gap-top' : '';
?>
<div class="<?= $main_extra_class ?>">
	<?php get_template_part('modules/common/breadcrumb')?>

	<section class="section-product-listing section-py">
		<div class="container">
			<div class="wrapper-product-listing">
				<aside class=" product-sidebar">
					<div class="sidebar-sticky">
						<div class="sidebar-widget category-menu">
							<div class="widget-header">
								<?php _e('Danh mục sản phẩm', 'canhcamtheme'); ?>
							</div>

							<nav class="category-nav">
								<ul class="category-list">
									<?php
								// Lấy term hiện tại (chỉ khi đang ở taxonomy)
								$current_term_id = is_tax('product_cat') ? get_queried_object_id() : 0;

								// Lấy danh mục cha
								$terms = get_terms([
									'taxonomy'   => 'product_cat',
									'hide_empty' => false,
									'parent'     => 0,
								]);

								foreach ($terms as $term) :

									// Lấy danh mục con
									$children = get_terms([
										'taxonomy'   => 'product_cat',
										'hide_empty' => false,
										'parent'     => $term->term_id,
									]);

									$has_child = !empty($children);

									/**
									 * Xác định:
									 * - Có đang active chính nó không
									 * - Hoặc có child nào đang active không
									 */
									$is_current_term_tree = false;

									if ($current_term_id) {
										// Chính nó active
										if ($term->term_id == $current_term_id) {
											$is_current_term_tree = true;
										}

										// Hoặc có child active
										if (!$is_current_term_tree && $has_child) {
											foreach ($children as $child) {
												if ($child->term_id == $current_term_id) {
													$is_current_term_tree = true;
													break;
												}
											}
										}
									}

									$item_class   = $is_current_term_tree ? 'active' : '';
									$icon_class   = $is_current_term_tree ? 'fa-minus' : 'fa-plus';
									$display_attr = $is_current_term_tree
										? 'style="display:block"'
										: 'style="display:none"';
								?>

									<li class="category-item <?php echo esc_attr($item_class); ?>">
										<div class="header-filter">
											<a href="<?php echo esc_url(get_term_link($term)); ?>">
												<?php echo esc_html($term->name); ?>
											</a>

											<?php if ($has_child) : ?>
											<span class="toggle-icon">
												<i class="fa-solid <?php echo esc_attr($icon_class); ?>"></i>
											</span>
											<?php endif; ?>
										</div>

										<?php if ($has_child) : ?>
										<ul class="sub-category" <?php echo $display_attr; ?>>
											<?php foreach ($children as $child) :
												$child_active = ($child->term_id == $current_term_id) ? 'active' : '';
											?>
											<li class="<?php echo esc_attr($child_active); ?>">
												<a href="<?php echo esc_url(get_term_link($child)); ?>">
													<?php echo esc_html($child->name); ?>
												</a>
											</li>
											<?php endforeach; ?>
										</ul>
										<?php endif; ?>
									</li>

									<?php endforeach; ?>
								</ul>
							</nav>
						</div>
					</div>
				</aside>


				<main class=" product-main">
					<div class="product-header">
						<h1 class="product-title"><?= single_term_title() ?></h1>
						<?php $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : ''; ?>
						<div class="product-sort">
							<label for="product-sort-select">Lọc theo</label>
							<select id="product-sort-select" class="form-select js-product-sort-select"
								onchange="location = this.value;">
								<?php 
								$base_url = remove_query_arg(['sort', 'page', 'paged', 'perpage']);
								?>
								<option value="<?= esc_url($base_url) ?>" <?= empty($sort) ? 'selected' : '' ?>>Tất cả
								</option>
								<option value="<?= esc_url(add_query_arg('sort', 'name_asc', $base_url)) ?>"
									<?= $sort == 'name_asc' ? 'selected' : '' ?>>Từ A-Z</option>
								<option value="<?= esc_url(add_query_arg('sort', 'name_desc', $base_url)) ?>"
									<?= $sort == 'name_desc' ? 'selected' : '' ?>>Từ Z-A</option>
								<option value="<?= esc_url(add_query_arg('sort', 'newest', $base_url)) ?>"
									<?= $sort == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
								<option value="<?= esc_url(add_query_arg('sort', 'oldest', $base_url)) ?>"
									<?= $sort == 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
							</select>
						</div>
					</div>

					<div class="product-grid">
						<?php
					$args = array();

					// Sort logic logic
					switch ($sort) {
						case 'name_asc':
							$args['orderby'] = 'title';
							$args['order']   = 'ASC';
							break;
						case 'name_desc':
							$args['orderby'] = 'title';
							$args['order']   = 'DESC';
							break;
						case 'oldest':
							$args['orderby'] = 'date';
							$args['order']   = 'ASC';
							break;
						case 'newest':
						default:
							$args['orderby'] = 'date';
							$args['order']   = 'DESC';
							break;
					}

					// Modify main query for sorting
					if (!empty($sort)) {
						$args['ignore_custom_sort'] = true; // Bypass dynamic sorting plugins on Live
						global $wp_query;
						$query_args = array_merge($wp_query->query_vars, $args);
						query_posts($query_args);
					}

					if (have_posts()) :
						while (have_posts()) : the_post();
							$image_id = get_post_thumbnail_id();
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
									<?php if ($specs) : ?>
									<?php if (!empty($specs) && is_array($specs)) : ?>
									<div class="product-specs">
										<?php foreach ($specs as $row) : ?>
										<?php if (!empty($row['text'])) : ?>
										<div>
											<?php echo esc_html($row['text']); ?>
										</div>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<?php endif; ?>

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

					<?php wp_bootstrap_pagination(); ?>
				</main>
			</div>
		</div>
	</section>
</div>

<script>

</script>

<?= get_footer() ?>