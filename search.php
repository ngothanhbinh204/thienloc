<?php
get_header();

$keyword = get_search_query();

// Define Post Types
$post_types_config = [
	'product' => [
		'label' => 'Sản phẩm',
		'icon'  => 'fa-solid fa-box',
	],
	'post' => [
		'label' => 'Bài viết',
		'icon'  => 'fa-solid fa-newspaper',
	],
	'service' => [
		'label' => 'Dịch vụ',
		'icon'  => 'fa-solid fa-briefcase',
	],
	'customer' => [
		'label' => 'Khách hàng',
		'icon'  => 'fa-solid fa-users',
	],
];

// Execute Queries
$results_data = [];
$total_posts_found = 0;

foreach ($post_types_config as $slug => $config) {
	$args = [
		'post_type'      => $slug,
		's'              => $keyword,
		'posts_per_page' => -1, // Get all results for accurate counts
		'post_status'    => 'publish',
	];
	$query = new WP_Query($args);
	if ($query->have_posts()) {
		$results_data[$slug] = [
			'query' => $query,
			'count' => $query->found_posts,
			'info'  => $config,
		];
		$total_posts_found += $query->found_posts;
	}
}

// Search Taxonomies (Product Categories)
$tax_results = [];
$tax_count = 0;
$tax_terms = get_terms([
	'taxonomy'   => 'product_cat',
	'name__like' => $keyword,
	'hide_empty' => false,
]);

if (!is_wp_error($tax_terms) && !empty($tax_terms)) {
	$tax_results = $tax_terms;
	$tax_count = count($tax_results);
	$total_posts_found += $tax_count;
}
?>
<main>
	<section class="page-banner relative">
		<div class="swiper banner-swiper">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<div class="banner-item relative h-full w-full">
						<div class="banner-img"><img class="lozad w-full h-full object-cover"
								data-src="<?php echo get_template_directory_uri(); ?>/assets/img/1.jpg" alt="" />
						</div>
						<div class="content flex items-center">
							<div class="wrapper-content">
								<h1 class="title-60 text-white font-bold uppercase">DI SẢN 30 NĂM KIẾN TẠO <br> BIỂU
									TƯỢNG THƯƠNG HIỆU</h1><a class="btn btn-primary" href="#">Về chúng tôi</a>
							</div>
						</div>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="banner-item relative h-full w-full">
						<div class="banner-img"><img class="lozad w-full h-full object-cover"
								data-src="<?php echo get_template_directory_uri(); ?>/assets/img/1.jpg" alt="" />
						</div>
						<div class="content flex items-center">
							<div class="wrapper-content">
								<h1 class="title-60 text-white font-bold uppercase">DI SẢN TẠO <br> BIỂU TƯỢNG THƯƠNG
									HIỆU</h1><a class="btn btn-primary" href="#">Về ABC</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="group-controll">
				<div class="wrap-button-slide">
					<div class="btn-prev banner-prev"><i class="fa-solid fa-chevron-left"></i></div>
					<div class="btn-next banner-next"><i class="fa-solid fa-chevron-right"></i></div>
				</div>
				<div class="swiper-pagination banner-pagination"></div>
			</div>
		</div>
	</section>
	<div class="breadcrumb-wrapper py-2 w-full">
		<div class="container">
			<ul class="flex items-center gap-2 text-sm text-neutral-600">
				<li><a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a></li>
				<li><span>|</span></li>
				<li><span class="text-primary-1">Tìm kiếm</span></li>
			</ul>
		</div>
	</div>
	<div class="search-page-wrapper">
		<section class="search-page section-py">
			<div class="container">
				<div class="search-header text-center mb-base">
					<h1 class="heading-2 font-bold text-primary-2 mb-4">Kết quả tìm kiếm</h1>
					<div class="search-query body-1">Tìm kiếm từ khóa:<span
							class="font-bold text-primary-1">&nbsp;"<?php echo esc_html($keyword); ?>"</span></div>
					<div class="search-total mt-2 text-utility-700">Tìm thấy <?php echo esc_html($total_posts_found); ?>
						kết quả</div>
				</div>
				<?php if ($total_posts_found > 0) : ?>
				<div class="search-tabs mb-base">
					<ul class="search-tab-nav flex flex-wrap justify-center gap-3">
						<li>
							<button class="search-tab-btn active btn btn-outline" data-tab="all">Tất cả
								(<?php echo esc_html($total_posts_found); ?>)</button>
						</li>
						<?php foreach ($results_data as $slug => $data) : ?>
						<li>
							<button class="search-tab-btn btn btn-outline" data-tab="<?php echo esc_attr($slug); ?>"><i
									class="<?php echo esc_attr($data['info']['icon']); ?> mr-2"></i><?php echo esc_html($data['info']['label']); ?>
								(<?php echo esc_html($data['count']); ?>)</button>
						</li>
						<?php endforeach; ?>
						<?php if ($tax_count > 0) : ?>
						<li>
							<button class="search-tab-btn btn btn-outline" data-tab="taxonomies"><i
									class="fa-solid fa-tags mr-2"></i>Danh mục
								(<?php echo esc_html($tax_count); ?>)</button>
						</li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="search-tab-content">
					<div class="search-tab-pane active" data-pane="all">
						<?php foreach ($results_data as $slug => $data) : 
								$query = $data['query'];
								$query->rewind_posts();
								$preview_count = 0;
							?>
						<div class="search-section mb-base">
							<div class="search-section-header flex items-center justify-between mb-6">
								<h2 class="heading-4 font-bold text-primary-2"><i
										class="<?php echo esc_attr($data['info']['icon']); ?> mr-2"></i><?php echo esc_html($data['info']['label']); ?><span
										class="text-utility-700 font-normal">&nbsp;(<?php echo esc_html($data['count']); ?>)</span>
								</h2>
							</div>
							<div class="search-grid">
								<?php while ($query->have_posts()) : $query->the_post(); 
											$preview_count++;
											if ($preview_count > 4) break; 
											get_template_part('template-parts/search-item', $slug);
										endwhile; ?>
							</div>
							<?php if ($data['count'] > 4) : ?>
							<div class="text-center mt-6">
								<button class="btn btn-primary search-tab-btn"
									data-tab="<?php echo esc_attr($slug); ?>">Xem tất cả
									<?php echo esc_html($data['info']['label']); ?>
									(<?php echo esc_html($data['count']); ?>)</button>
							</div>
							<?php endif; ?>
						</div>
						<?php endforeach; ?>

						<?php if ($tax_count > 0) : ?>
						<div class="search-section mb-base">
							<div class="taxonomy-group mb-8">
								<h3 class="heading-5 font-bold text-primary-2 mb-4">Danh mục sản phẩm</h3>
								<div class="search-taxonomies flex flex-wrap gap-3">
									<?php foreach ($tax_results as $term) : ?>
									<a class="search-term-item inline-flex items-center gap-2 px-4 py-2"
										href="<?php echo esc_url(get_term_link($term)); ?>"><i
											class="fa-solid fa-folder"></i><span><?php echo esc_html($term->name); ?></span><span
											class="text-sm">(<?php echo esc_html($term->count); ?>)</span></a>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</div>

					<?php foreach ($results_data as $slug => $data) : 
							$query = $data['query'];
							$query->rewind_posts();
						?>
					<div class="search-tab-pane" data-pane="<?php echo esc_attr($slug); ?>">
						<div class="search-section">
							<div class="search-grid">
								<?php while ($query->have_posts()) : $query->the_post(); 
											get_template_part('template-parts/search-item', $slug);
										endwhile; ?>
							</div>
						</div>
					</div>
					<?php endforeach; ?>

					<?php if ($tax_count > 0) : ?>
					<div class="search-tab-pane" data-pane="taxonomies">
						<div class="search-section">
							<div class="taxonomy-group mb-8">
								<h3 class="heading-5 font-bold text-primary-2 mb-4">Danh mục sản phẩm</h3>
								<div class="search-taxonomies flex flex-wrap gap-3">
									<?php foreach ($tax_results as $term) : ?>
									<a class="search-term-item inline-flex items-center gap-2 px-4 py-2"
										href="<?php echo esc_url(get_term_link($term)); ?>"><i
											class="fa-solid fa-folder"></i><span><?php echo esc_html($term->name); ?></span><span
											class="text-sm">(<?php echo esc_html($term->count); ?>)</span></a>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<?php else : ?>
				<div class="search-no-results text-center py-20">
					<div class="icon text-6xl text-utility-300 mb-6"><i class="fa-solid fa-search"></i></div>
					<h2 class="heading-4 font-bold text-primary-2 mb-4">Không tìm thấy kết quả</h2>
					<p class="body-1 text-utility-700 mb-6">Vui lòng thử lại với từ khóa khác</p><a
						class="btn btn-primary" href="<?php echo esc_url(home_url('/')); ?>">Về trang chủ</a>
				</div>
				<?php endif; ?>
			</div>
		</section>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const tabBtns = document.querySelectorAll('.search-tab-btn');
	const tabPanes = document.querySelectorAll('.search-tab-pane');

	tabBtns.forEach(btn => {
		btn.addEventListener('click', function() {
			const targetTab = this.getAttribute('data-tab');

			// Remove active class from all buttons and panes
			tabBtns.forEach(b => b.classList.remove('active'));
			tabPanes.forEach(p => p.classList.remove('active'));

			// Add active class to clicked button and corresponding pane
			this.classList.add('active');
			document.querySelector(`.search-tab-pane[data-pane="${targetTab}"]`).classList.add(
				'active');

			// Scroll to top of results
			document.querySelector('.search-tabs').scrollIntoView({
				behavior: 'smooth'
			});
		});
	});
});
</script>
<?php
wp_reset_postdata();
get_footer();