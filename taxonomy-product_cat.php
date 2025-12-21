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
<?php get_template_part('modules/common/breadcrumb')?>

<section class="section-product-listing section-py">
	<div class="container">
		<div class="row">
			<aside class="col-lg-3 product-sidebar">
				<div class="sidebar-sticky">
					<div class="sidebar-widget category-menu">
						<div class="widget-header">
							<?php _e('Danh mục sản phẩm', 'canhcamtheme'); ?>
						</div>
						<nav class="category-nav">
							<ul class="category-list">
								<?php
								$terms = get_terms(array(
									'taxonomy'   => 'product_cat',
									'hide_empty' => false,
									'parent'     => 0,
								));

								foreach ($terms as $term) :
									$children = get_terms(array(
										'taxonomy'   => 'product_cat',
										'hide_empty' => false,
										'parent'     => $term->term_id,
									));
									$has_child = !empty($children);
									$active_class = (is_tax('product_cat', $term->term_id)) ? 'active' : '';
								?>
								<li class="category-item <?= $active_class ?>">
									<div class="header-filter">
										<a href="<?= get_term_link($term) ?>"><?= esc_html($term->name) ?></a>
										<?php if ($has_child) : ?>
										<span class="toggle-icon">
											<i class="fa-solid fa-plus"></i>
										</span>
										<?php endif; ?>
									</div>
									<?php if ($has_child) : ?>
									<ul class="sub-category">
										<?php foreach ($children as $child) : 
													$child_active = (is_tax('product_cat', $child->term_id)) ? 'active' : '';
												?>
										<li class="<?= $child_active ?>">
											<a href="<?= get_term_link($child) ?>"><?= esc_html($child->name) ?></a>
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

			<main class="col-lg-9 product-main">
				<div class="product-header">
					<h1 class="product-title"><?= single_term_title() ?></h1>
					<!-- Sort functionality can be implemented with JS/AJAX later -->
					<div class="product-sort">
						<label>Lọc theo</label>
						<div class="custom-select">
							<div class="select-trigger"><span class="selected-value">Tất cả</span><i
									class="fa-solid fa-chevron-down"></i></div>
							<div class="select-dropdown">
								<div class="select-option active" data-value="">Tất cả</div>
								<div class="select-option" data-value="price">Giá tăng dần</div>
								<div class="select-option" data-value="price-desc">Giá giảm dần</div>
								<div class="select-option" data-value="date">Mới nhất</div>
							</div>
						</div>
					</div>
				</div>

				<div class="product-grid">
					<?php
					$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
					$args = array();

					// Sort Logic
					switch ($sort) {
						case 'price':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = 'price'; // Assumed meta key
							$args['order']    = 'ASC';
							break;
						case 'price-desc':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = 'price'; // Assumed meta key
							$args['order']    = 'DESC';
							break;
						case 'date':
							$args['orderby'] = 'date';
							$args['order']   = 'DESC';
							break;
						default:
							// Keep default sort or specific
							// $args['orderby'] = 'menu_order date';
							// $args['order']   = 'DESC';
							break;
					}

					if (!empty($args)) {
						global $wp_query;
						query_posts(array_merge($wp_query->query_vars, $args));
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
								<div class="product-specs line-clamp-3">
									<?= get_the_excerpt() ?></p>
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

				<?php wp_bootstrap_pagination(); ?>
			</main>
		</div>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const selectTrigger = document.querySelector('.select-trigger');
	const selectDropdown = document.querySelector('.select-dropdown');
	const selectOptions = document.querySelectorAll('.select-option');
	const selectedValue = document.querySelector('.selected-value');

	// Toggle dropdown
	if (selectTrigger) {
		selectTrigger.addEventListener('click', function() {
			selectDropdown.classList.toggle('show');
		});
	}

	// Close dropdown when clicking outside
	document.addEventListener('click', function(e) {
		if (selectTrigger && !selectTrigger.contains(e.target) && !selectDropdown.contains(e.target)) {
			selectDropdown.classList.remove('show');
		}
	});

	// Handle option click
	selectOptions.forEach(option => {
		option.addEventListener('click', function() {
			const value = this.getAttribute('data-value');
			const text = this.textContent;

			// Update trigger text
			if (selectedValue) selectedValue.textContent = text;

			// Update active class
			selectOptions.forEach(opt => opt.classList.remove('active'));
			this.classList.add('active');

			// Close dropdown
			if (selectDropdown) selectDropdown.classList.remove('show');

			// Redirect with sort param
			const url = new URL(window.location.href);
			if (value) {
				url.searchParams.set('sort', value);
			} else {
				url.searchParams.delete('sort');
			}
			// Reset pagination when sorting changes
			url.searchParams.delete('paged'); // Using 'paged' key for main query
			url.searchParams.delete('page'); // Sometimes 'page' is used
			window.location.href = url.toString();
		});
	});

	// Set active state based on URL
	const currentUrl = new URL(window.location.href);
	const currentSort = currentUrl.searchParams.get('sort');
	if (currentSort) {
		const activeOption = document.querySelector(`.select-option[data-value="${currentSort}"]`);
		if (activeOption) {
			selectOptions.forEach(opt => opt.classList.remove('active'));
			activeOption.classList.add('active');
			if (selectedValue) selectedValue.textContent = activeOption.textContent;
		}
	}
});
</script>

<?= get_footer() ?>