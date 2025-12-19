<?php
$gallery     = get_sub_field('gallery');
$title       = get_sub_field('title');
$description = get_sub_field('description');
$button      = get_sub_field('button');
?>
<section class="introduction-5 section-py bg-linear-2">
	<div class="container">
		<div class="wrapper-grid">
			<div class="col-slider" data-aos="fade-right">
				<?php if ($gallery) : ?>
				<div class="swiper intro-5-swiper">
					<div class="swiper-wrapper">
						<?php foreach ($gallery as $image_id) : ?>
						<div class="swiper-slide">
							<div class="img-wrapper">
								<?= get_image_attrachment($image_id, 'image'); ?>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					<div class="swiper-pagination intro-5-pagination"></div>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-content" data-aos="fade-left" data-aos-delay="200">
				<?php if ($title) : ?>
				<h2 class="title-48 text-primary-1 font-medium mb-6">
					<?= nl2br(esc_html($title)); ?>
				</h2>
				<?php endif; ?>

				<?php if ($description) : ?>
				<div class="content-html text-justify mb-8">
					<?= $description; ?>
				</div>
				<?php endif; ?>

				<?php if ($button) : ?>
				<a class="btn btn-primary" href="<?= esc_url($button['url']); ?>"
					target="<?= esc_attr($button['target'] ?: '_self'); ?>">
					<?= esc_html($button['title']); ?>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>