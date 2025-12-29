<?php
$marquee = get_sub_field('marquee') ?? [];
$title   = get_sub_field('title');
$desc    = get_sub_field('description');
$items   = get_sub_field('items') ?? [];

if (!$title && empty($items)) {
	return;
}
?>

<section class="home-2">
	<?php if ($marquee): ?>
	<div class="marquee-wrapper pt-5 mb-2">
		<div class="marquee">
			<?php for ($i = 0; $i < 2; $i++): ?>
			<div class="marquee-content" <?= $i === 1 ? 'aria-hidden="true"' : ''; ?>>
				<?php foreach ($marquee as $m): ?>
				<span class="marquee-item"><?= esc_html($m['text']); ?></span>
				<span class="marquee-dot">•</span>
				<?php endforeach; ?>
			</div>
			<?php endfor; ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="section-header text-center" data-aos="fade-up">
		<div class="container">
			<?php if ($title): ?>
			<h2 class="title-48 text-white"><?= esc_html($title); ?></h2>
			<?php endif; ?>
			<?php if ($desc): ?>
			<div class="desc text-white !mt-10"><?= wp_kses_post($desc); ?></div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ($items): ?>
	<div class="product-cards">
		<div class="product-slider-wrapper">
			<div class="list-grid swiper">
				<div class="swiper-wrapper">
					<?php
						$delay = 200;
						foreach ($items as $item):
						?>
					<div class="swiper-slide">
						<div class="product-card" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
							<?php if (!empty($item['image'])): ?>
							<div class="product-img">
								<img class="lozad" data-src="<?= esc_url($item['image']['url']); ?>" alt="">
							</div>
							<?php endif; ?>

							<div class="product-content">
								<h3 class="product-title">
									<?= esc_html($item['title'] ?? ''); ?>
								</h3>
								<p class="product-desc"><?= wp_kses_post($item['description'] ?? ''); ?></p>
								<div class="product-line"></div>
								<?php if (!empty($item['link'])): ?>
								<a class="btn btn-secondary product-btn" href="<?= esc_url($item['link']['url']); ?>">
									<?= esc_html($item['link']['title']); ?>
								</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php
							$delay += 100;
						endforeach;
						?>
				</div>
				<div class="product-nav">
					<button class="btn-prev btn-slider-2" type="button">
						<i class="fa-solid fa-chevron-left"></i>
					</button>
					<button class="btn-next btn-slider-2" type="button">
						<i class="fa-solid fa-chevron-right"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</section>