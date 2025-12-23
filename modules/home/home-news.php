<?php
// ===== HOME 5 : NEWS =====
$title      = get_sub_field('title') ?? '';
$bg         = get_sub_field('background') ?? null;
$categories = get_sub_field('categories') ?? [];
$per_page   = get_sub_field('posts_per_page') ?: 5;
$button     = get_sub_field('link') ?? null;

// Chuẩn bị category IDs
$cat_ids = [];

if (!empty($categories)) {
	foreach ($categories as $cat) {

		// Term Object
		if (is_object($cat) && isset($cat->term_id)) {
			$cat_ids[] = (int) $cat->term_id;
		}

		// Term ID
		elseif (is_numeric($cat)) {
			$cat_ids[] = (int) $cat;
		}

		// Term Array
		elseif (is_array($cat) && isset($cat['term_id'])) {
			$cat_ids[] = (int) $cat['term_id'];
		}
	}
}


// Query bài viết
$args = [
	'post_type'      => 'post',
	'posts_per_page' => $per_page,
	'post_status'    => 'publish',
];

if (!empty($cat_ids)) {
	$args['category__in'] = $cat_ids;
}

$query = new WP_Query($args);

if (!$title && !$query->have_posts()) {
	return;
}
?>

<section class="section-home-5 section-py relative">
	<?php if ($bg): ?>
	<div class="home-5-decor">
		<img class="lozad" data-src="<?= esc_url($bg['url']); ?>" alt="">
	</div>
	<?php endif; ?>

	<div class="container">

		<?php if ($title): ?>
		<div class="section-header text-center !mb-7.5">
			<h2 class="title-48 text-primary-1 font-medium">
				<?= esc_html($title); ?>
			</h2>
		</div>
		<?php endif; ?>

		<?php if (!empty($cat_ids)): ?>
		<div class="news-filter flex justify-center gap-3 mb-11.5">
			<button class="filter-btn active" data-filter="all">Tất cả</button>
			<?php foreach ($categories as $cat): 
				$term = is_object($cat) ? $cat : get_term((int) $cat, 'category');
				if (!$term || is_wp_error($term)) continue;
			?>
			<button class="filter-btn" data-filter="<?= esc_attr($term->slug); ?>">
				<?= esc_html($term->name); ?>
			</button>
			<?php endforeach; ?>

		</div>
		<?php endif; ?>

		<?php if ($query->have_posts()): ?>
		<div class="news-grid">
			<?php
				$index = 0;
				while ($query->have_posts()): $query->the_post();
					$is_featured = ($index === 0);
					$terms = get_the_terms(get_the_ID(), 'category');
					$term  = (!empty($terms) && !is_wp_error($terms)) ? $terms[0] : null;
				?>
			<div class="news-item <?= $is_featured ? 'news-item-featured' : 'news-item-small'; ?>"
				<?= $term ? 'data-category="' . esc_attr($term->slug) . '"' : ''; ?>>

				<div class="news-card <?= $is_featured ? 'news-card-featured' : 'news-card-small'; ?>">

					<a class="news-card-img" href="<?= esc_url(get_permalink()); ?>">
						<?php if (has_post_thumbnail()): ?>
						<?= get_the_post_thumbnail(get_the_ID(), 'large', [
										'class' => 'lozad w-full h-full object-cover'
									]); ?>
						<?php endif; ?>
					</a>

					<div class="news-card-content">
						<div class="news-card-meta">
							<span class="news-date"><?= esc_html(get_the_date('d.m.Y')); ?></span>

							<?php if ($term): ?>
							<span class="news-category cat-<?= esc_attr($term->slug); ?>">
								<?= esc_html($term->name); ?>
							</span>
							<?php endif; ?>
						</div>

						<h3 class="news-card-title">
							<a href="<?= esc_url(get_permalink()); ?>">
								<?= esc_html(get_the_title()); ?>
							</a>
						</h3>

						<?php if ($is_featured): ?>
						<p class="news-card-desc">
							<?= esc_html(wp_trim_words(get_the_excerpt(), 30)); ?>
						</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
					$index++;
				endwhile;
				wp_reset_postdata();
				?>
		</div>
		<?php endif; ?>

		<?php if ($button): ?>
		<div class="news-footer" data-aos="fade-up" data-aos-delay="800">
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
	const sections = document.querySelectorAll('.section-home-5');

	sections.forEach(function(section) {
		const btns = section.querySelectorAll('.filter-btn');
		const items = section.querySelectorAll('.news-item');

		btns.forEach(function(btn) {
			btn.addEventListener('click', function() {
				const filter = this.getAttribute('data-filter');

				// Active state
				btns.forEach(b => b.classList.remove('active'));
				this.classList.add('active');

				// Filter items
				items.forEach(function(item) {
					const category = item.getAttribute('data-category');
					if (filter === 'all' || category === filter) {
						item.style.display = '';
					} else {
						item.style.display = 'none';
					}
				});
			});
		});
	});
});
</script>