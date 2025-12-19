<?php
/**
 * Get Rank Math breadcrumbs as array
 */
if (!function_exists('canhcam_get_breadcrumbs')) {
	function canhcam_get_breadcrumbs()
	{
		if (function_exists('rank_math_get_breadcrumbs')) {
			return rank_math_get_breadcrumbs();
		}
		return [];
	}
}

$breadcrumbs = canhcam_get_breadcrumbs();

// Fallback if no breadcrumbs (e.g. home page or plugin inactive)
if (empty($breadcrumbs)) {
	if (is_front_page()) return;
	
	$breadcrumbs = [
		['label' => 'Trang chủ', 'url' => home_url('/')],
		['label' => get_the_title(), 'url' => ''],
	];
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