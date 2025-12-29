<?php
// ===== HOME 5 : NEWS =====
$title      = get_sub_field('title') ?? '';
$bg         = get_sub_field('background') ?? null;
$categories = get_sub_field('categories') ?? [];
$per_page   = get_sub_field('posts_per_page') ?: 5;
$button     = get_sub_field('link') ?? null;

// Prepare category IDs for initial query fallback
$cat_ids = [];
if (!empty($categories)) {
	foreach ($categories as $cat) {
		if (is_object($cat) && isset($cat->term_id)) {
			$cat_ids[] = (int) $cat->term_id;
		} elseif (is_numeric($cat)) {
			$cat_ids[] = (int) $cat;
		} elseif (is_array($cat) && isset($cat['term_id'])) {
			$cat_ids[] = (int) $cat['term_id'];
		}
	}
}
?>

<section class="section-home-5 section-py relative" id="home-news-section" data-per-page="<?= esc_attr($per_page); ?>">
	<?php if ($bg) : ?>
	<div class="home-5-decor">
		<?= get_image_attrachment($bg, 'image') ?>
	</div>
	<?php endif; ?>

	<div class="container">
		<?php if ($title) : ?>
		<div class="section-header text-center !mb-7.5">
			<h2 class="title-48 text-primary-1 font-medium">
				<?= esc_html($title); ?>
			</h2>
		</div>
		<?php endif; ?>

		<?php if (!empty($categories)) : ?>
		<div class="news-filter flex justify-center gap-3 mb-11.5">
			<button class="filter-btn active" data-filter="all"><?= esc_html__('Tất cả', 'canhcamtheme'); ?></button>
			<?php foreach ($categories as $cat) :
					$term = is_object($cat) ? $cat : get_term((int) $cat, 'category');
					if (!$term || is_wp_error($term)) continue;
				?>
			<button class="filter-btn" data-filter="<?= esc_attr($term->slug); ?>">
				<?= esc_html($term->name); ?>
			</button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="news-list-container relative min-h-[300px]">
			<?php
			// Initial load using common loop
			get_template_part('modules/home/news-items-loop', null, [
				'category' => 'all',
				'per_page' => $per_page,
				'cat_ids'  => $cat_ids
			]);
			?>
			<div
				class="news-loading absolute inset-0 bg-white/50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
				<div
					class="loading-spinner w-10 h-10 border-4 border-primary-1 border-t-transparent rounded-full animate-spin">
				</div>
			</div>
		</div>

		<?php if ($button) : ?>
		<div class="news-footer mt-10">
			<a class="btn btn-primary" href="<?= esc_url($button['url']); ?>"
				target="<?= esc_attr($button['target'] ?: '_self'); ?>">
				<?= esc_html($button['title']); ?>
			</a>
		</div>
		<?php endif; ?>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const observer = lozad(); // lazy loads elements with default selector as '.lozad'
	observer.observe();

	window.FE = {
		lozad: observer.observe,
	};
	const section = document.querySelector('#home-news-section');
	if (!section) return;

	const filterBtns = section.querySelectorAll('.filter-btn');
	const container = section.querySelector('.news-list-container');
	const loading = section.querySelector('.news-loading');
	const perPage = section.getAttribute('data-per-page');
	const catIds = <?= json_encode($cat_ids); ?>;

	// Simple client-side cache
	const cache = {};

	filterBtns.forEach(btn => {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			if (this.classList.contains('active')) return;

			const category = this.getAttribute('data-filter');

			// UI state
			filterBtns.forEach(b => b.classList.remove('active'));
			this.classList.add('active');

			const updateContent = (html) => {
				const tempDiv = document.createElement('div');
				tempDiv.innerHTML = html;
				const newContent = tempDiv.querySelector('.news-grid') || tempDiv
					.querySelector('.no-posts');

				const currentGrid = container.querySelector('.news-grid') || container
					.querySelector('.no-posts');
				if (currentGrid) {
					currentGrid.style.opacity = '0';
					currentGrid.style.transition = 'opacity 0.2s';
					setTimeout(() => {
						currentGrid.remove();
						container.prepend(newContent);
						newContent.style.opacity = '0';
						newContent.style.transition = 'opacity 0.2s';
						// Re-init Lozad
						if (window.lozad) {
							const observer = window.lozad('.lozad', {
								load: function(el) {
									el.src = el.dataset.src;
									if (el.dataset.srcset) el.srcset =
										el.dataset.srcset;
									el.onload = function() {
										el.classList.add('loaded');
									}
								}
							});
							observer.observe();
						}
						setTimeout(() => newContent.style.opacity = '1', 50);
						loading.classList.add('opacity-0', 'pointer-events-none');
					}, 200);
				} else {
					container.prepend(newContent);
					if (window.lozad) window.lozad().observe();
					loading.classList.add('opacity-0', 'pointer-events-none');
				}
			};

			// Check cache first
			if (cache[category]) {
				updateContent(cache[category]);
				return;
			}

			// Show loading
			loading.classList.remove('opacity-0', 'pointer-events-none');

			// AJAX call
			const formData = new FormData();
			formData.append('action', 'canhcam_filter_news');
			formData.append('category', category);
			formData.append('per_page', perPage);
			if (category === 'all') {
				catIds.forEach(id => formData.append('cat_ids[]', id));
			}

			fetch(canhcam_ajax.url, {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(res => {
					if (res.success) {
						cache[category] = res.data; // Cache the result
						updateContent(res.data);
					}
				})
				.catch(err => {
					console.error('Filter error:', err);
					loading.classList.add('opacity-0', 'pointer-events-none');
				});
		});
	});
});
</script>