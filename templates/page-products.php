<?php 
/*
Template name: Page - Products
*/ 

?>
<?= get_header() ?>

<?php get_template_part('modules/common/banner')?>
<?php get_template_part('modules/common/breadcrumb')?>

<section class="section-product-listing section-py">
	<div class="container">
		<div class="wrapper-product-listing">
			<aside class="product-sidebar">
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

								$current_term_id = get_queried_object_id();
								$i = 0;

								foreach ($terms as $term) :
									$children = get_terms(array(
										'taxonomy'   => 'product_cat',
										'hide_empty' => false,
										'parent'     => $term->term_id,
									));
									$has_child = !empty($children);
									
									$is_current_term_tree = ($term->term_id == $current_term_id);
									if (!$is_current_term_tree && $has_child) {
										foreach($children as $child) {
											if ($child->term_id == $current_term_id) {
												$is_current_term_tree = true;
												break;
											}
										}
									}

									$is_active = ($i === 0 || $is_current_term_tree);
									$item_class = $is_active ? 'active' : '';
									$icon_class = $is_active ? 'fa-minus' : 'fa-plus';
									$display_style = $is_active ? 'style="display: block;"' : 'style="display: none;"';
								?>
								<li class="category-item <?= $item_class ?>">
									<div class="header-filter">
										<a href="<?= get_term_link($term) ?>"><?= esc_html($term->name) ?></a>
										<?php if ($has_child) : ?>
										<span class="toggle-icon">
											<i class="fa-solid <?= $icon_class ?>"></i>
										</span>
										<?php endif; ?>
									</div>
									<?php if ($has_child) : ?>
									<ul class="sub-category" <?= $display_style ?>>
										<?php foreach ($children as $child) : 
											$child_active = ($child->term_id == $current_term_id) ? 'active' : '';
										?>
										<li class="<?= $child_active ?>">
											<a href="<?= get_term_link($child) ?>"><?= esc_html($child->name) ?></a>
										</li>
										<?php endforeach; ?>
									</ul>
									<?php endif; ?>
								</li>
								<?php 
									$i++;
								endforeach; 
								?>
							</ul>
						</nav>
					</div>
				</div>
			</aside>

			<main class="product-main">
				<div class="product-header">
					<h1 class="product-title"><?= get_the_title() ?></h1>
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
					// FIX: Sử dụng 'page' cho page template thay vì 'paged'
					$current_page = max(1, get_query_var('page') ? get_query_var('page') : (get_query_var('paged') ? get_query_var('paged') : 1));

					$args = array(
						'post_type'          => 'product',
						'posts_per_page'     => 12,
						'paged'              => $current_page,
						'post_status'        => 'publish',
						'ignore_custom_sort' => true, // Fix for Live plugins like "Post Types Order"
					);

					// Filter by product category if exists
					if (is_tax('product_cat')) {
						$term = get_queried_object();
						if ($term && !is_wp_error($term)) {
							$args['tax_query'] = array(
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $term->term_id,
								),
							);
						}
					}

					// Sort logic - ADDED Z-A
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

					$query = new WP_Query($args);

					// === DATABASE QUERY DEBUG - XÓA SAU KHI FIX ===
					if (isset($_GET['debug'])) {
						echo '<div style="background:#1e1e1e;color:#00ff00;padding:20px;margin:20px;font-family:monospace;font-size:12px;border:3px solid #00ff00;">';
						echo '<strong>🗄️ WP_QUERY DEBUG:</strong><br><br>';
						echo 'Found posts: ' . $query->found_posts . '<br>';
						echo 'Posts per page: ' . $query->query_vars['posts_per_page'] . '<br>';
						echo 'Current page: ' . $query->query_vars['paged'] . '<br>';
						echo 'Max pages: ' . $query->max_num_pages . '<br><br>';
						echo '<strong>SQL Query:</strong><br>';
						echo '<div style="word-break:break-all;">' . htmlspecialchars($query->request) . '</div>';
						echo '</div>';
					}
					// === END DEBUG ===

					if ($query->have_posts()) :
						while ($query->have_posts()) : $query->the_post();
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

				<?php if ($query->max_num_pages > 1) : ?>

				<?php 
					wp_bootstrap_pagination_page_template( array( 'custom_query' => $query, 'current_page' => $current_page ) ); ?>

				<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			</main>
		</div>
	</div>
</section>

<script>

</script>

<?= get_footer() ?>