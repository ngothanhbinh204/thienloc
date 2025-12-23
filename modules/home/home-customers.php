<?php
$title       = get_sub_field('title') ?? '';
$description = get_sub_field('description') ?? '';
$customers   = get_sub_field('items') ?? [];
$button      = get_sub_field('link') ?? null;
$background  = get_sub_field('background') ?? null;
if (!$title && empty($customers)) {
	return;
}
?>

<section class="home-3 section-py bg-gray-50" style="<?= get_sub_field('background') ? 'background-image:url(' . esc_url(get_sub_field('background')['url']) . ')' : ''; ?>; background-size: cover;
    background-position: center;">
	<div class="container">
		<div class="section-header text-center space-y-6">
			<?php if ($title): ?>
			<h2 class="title-48 text-primary-1"><?= esc_html($title); ?></h2>
			<?php endif; ?>

			<?php if ($description): ?>
			<p class="desc"><?= $description; ?></p>
			<?php endif; ?>
		</div>

		<?php if ($customers): ?>
		<div class="customer-slider swiper flex flex-col items-center">
			<div class="swiper-wrapper">
				<?php foreach ($customers as $item): ?>
				<?php
						$logo = $item['logo'] ?? null;
						$name = $item['name'] ?? '';
						if (!$logo && !$name) continue;
						?>
				<div class="swiper-slide">
					<div class="customer-card">
						<?php if ($logo): ?>
						<div class="customer-logo">
							<img class="lozad w-full h-full object-contain" data-src="<?= esc_url($logo['url']); ?>"
								alt="<?= esc_attr($name); ?>">
						</div>
						<?php endif; ?>

						<?php if ($name): ?>
						<div class="customer-name"><?= esc_html($name); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="swiper-pagination home-3-pagination"></div>

			<?php if ($button): ?>
			<a class="btn btn-primary btn-large mx-auto" href="<?= esc_url($button['url']); ?>">
				<?= esc_html($button['title']); ?>
			</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
</section>