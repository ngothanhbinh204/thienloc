<?php
$title = get_sub_field('title') ?? '';
$steps = get_sub_field('items') ?? [];

if (!$title && empty($steps)) {
	return;
}
?>

<section class="section-home-4 section-py">
	<div class="container">
		<?php if ($title): ?>
		<div class="section-header text-center">
			<h2 class="title-48 text-white font-medium"><?= esc_html($title); ?></h2>
		</div>
		<?php endif; ?>

		<?php if ($steps): ?>
		<div class="workflow-grid">
			<?php foreach ($steps as $index => $step): ?>
			<?php
					$icon  = $step['icon'] ?? null;
					$title = $step['label'] ?? '';
					$desc  = $step['description'] ?? '';
					$arrow = $step['arrow'] ?? null;

					if (!$title && !$desc) continue;
					$is_even = $index % 2 === 1;
					?>
			<div class="workflow-item <?= $is_even ? 'item-even' : 'item-odd'; ?>">
				<?php if ($desc): ?>
				<div class="workflow-desc">
					<p><?= $desc ; ?></p>
				</div>
				<?php endif; ?>

				<div class="workflow-box">
					<div class="workflow-circle">
						<?php if ($icon): ?>
						<div class="wrapper-icon">
							<div class="workflow-icon">
								<img class="lozad w-full h-full object-contain" data-src="<?= esc_url($icon['url']); ?>"
									alt="">
							</div>
						</div>
						<?php endif; ?>
					</div>

					<?php if ($title): ?>
					<div class="workflow-label">
						<span><?= esc_html($title); ?></span>
					</div>
					<?php endif; ?>
				</div>

				<?php if ($arrow): ?>
				<div class="workflow-arrow">
					<img class="lozad w-full h-full object-contain" data-src="<?= esc_url($arrow['url']); ?>" alt="">
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>