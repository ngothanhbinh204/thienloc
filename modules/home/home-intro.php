<?php
$bg    = get_sub_field('background');
$textmask = get_sub_field('text_mask') ?: false;
$title = get_sub_field('title');
$link  = get_sub_field('link');
$stats = get_field('stats', 'option') ?? [];

if (!$title && empty($stats)) {
    return;
}
?>

<section class="home-1 section-py relative overflow-hidden">
	<?php if ($bg): ?>
	<div class="home-1-decor">
		<img class="lozad" data-src="<?= esc_url($bg['url']); ?>" alt="">
	</div>
	<?php endif; ?>

	<div class="container relative z-10">
		<?php if ($title): ?>
		<div class="home-1-content text-center">
			<h2 style="
			<?= $textmask ? '
			background: url(' . esc_url($textmask['url']) . ')  no-repeat center bottom 0;
			background-size: cover;
			background-clip: text;
			-webkit-text-fill-color: transparent;
			user-select: none;
			' : ''; ?>" class="title-80 font-black uppercase text-mask-image text-image" data-aos="fade-up"
				data-aos-duration="1000">
				<?= $title; ?>
			</h2>
		</div>
		<?php endif; ?>

		<?php if ($link): ?>
		<a class="btn btn-primary" href="<?= esc_url($link['url']); ?>">
			<?= esc_html($link['title']); ?>
		</a>
		<?php endif; ?>

		<?php if (!empty($stats)): ?>
		<div class="list-stats">
			<?php foreach ($stats as $stat): ?>
			<div class="stat-item">
				<?php if (!empty($stat['icon'])): ?>
				<div class="stat-icon">
					<img class="lozad" data-src="<?= esc_url($stat['icon']['url']); ?>" alt="">
				</div>
				<?php endif; ?>

				<div class="stat-info">
					<?php if (!empty($stat['number'])): ?>
					<div class="stat-number"><?= esc_html($stat['number']); ?></div>
					<?php endif; ?>
					<?php if (!empty($stat['label'])): ?>
					<div class="stat-label"><?= esc_html($stat['label']); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>