<?php
$object = get_queried_object();

if ($object instanceof WP_Term) {
	$context_id = "{$object->taxonomy}_{$object->term_id}";
} elseif (is_front_page()) {
	$context_id = get_option('page_on_front');
} elseif (is_home()) {
	$context_id = get_option('page_for_posts');
} else {
	$context_id = get_the_ID();
}

$banner_selected = get_field('banner_select_page', $context_id);
if (empty($banner_selected)) {
	return;
}

// Vì multiple = false và return_format = id, kết quả là 1 ID duy nhất, không phải mảng
$banner_id = $banner_selected instanceof WP_Post ? $banner_selected->ID : (int) $banner_selected;
$banner_type   = get_field('banner_type', $banner_id);
// Sửa lại đúng tên field trong config là 'banner_single_image'
$banner_image  = get_field('banner_single_image', $banner_id);
$banner_slides = get_field('banner_slides', $banner_id);
?>

<section class="page-banner relative">

	<?php if ($banner_type === 'single' && $banner_image) : ?>

	<div class="banner-swiper">
		<div class="banner-item relative h-full w-full">
			<div class="banner-img">
				<?= get_image_attrachment($banner_image, 'image'); ?>
			</div>
		</div>
	</div>


	<?php elseif ($banner_type === 'slider' && !empty($banner_slides)) : ?>

	<div class="swiper banner-swiper">
		<div class="swiper-wrapper">

			<?php foreach ($banner_slides as $slide) :
					$image  = $slide['image'] ?? null;
					$title  = $slide['title'] ?? '';
					$button = $slide['button'] ?? null;
				?>
			<div class="swiper-slide">
				<div class="banner-item relative h-full w-full">
					<div class="banner-img">
						<?= $image ? get_image_attrachment($image, 'image') : ''; ?>
					</div>

					<?php if ($title || $button) : ?>
					<div class="content flex items-center">
						<div class="wrapper-content">
							<?php if ($title) : ?>
							<h1 class="title-60 text-white font-bold uppercase">
								<?= $title; ?>
							</h1>
							<?php endif; ?>

							<?php if (!empty($button['url'])) : ?>
							<a class="btn btn-primary" href="<?= esc_url($button['url']); ?>"
								target="<?= esc_attr($button['target'] ?: '_self'); ?>">
								<?= $button['title']; ?>
							</a>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>

				</div>
			</div>
			<?php endforeach; ?>

		</div>

		<div class="group-controll">
			<div class="wrap-button-slide">
				<div class="btn-prev banner-prev"><i class="fa-solid fa-chevron-left"></i></div>
				<div class="btn-next banner-next"><i class="fa-solid fa-chevron-right"></i></div>
			</div>
			<div class="swiper-pagination banner-pagination"></div>
		</div>
	</div>

	<?php endif; ?>

</section>