<?php
/**
 * Display Breadcrumbs
 */
$breadcrumbs = canhcam_get_breadcrumbs();

// Don't show anything if empty or on home page
if (empty($breadcrumbs) || is_front_page()) {
	return;
}
?>
<div class="breadcrumb-wrapper py-2 w-full">
	<div class="container">
		<ul class="flex items-center gap-2 text-sm text-neutral-600">
			<?php foreach ($breadcrumbs as $key => $item) : ?>
			<?php if ($key > 0) : ?>
			<li><span>|</span></li>
			<?php endif; ?>

			<li>
				<?php if (!empty($item['url']) && $key < count($breadcrumbs) - 1) : ?>
				<a href="<?= esc_url($item['url']) ?>"><?= esc_html($item['label']) ?></a>
				<?php else : ?>
				<span class="text-primary-1"><?= esc_html($item['label']) ?></span>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>