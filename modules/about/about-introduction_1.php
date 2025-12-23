<?php
$title       = get_sub_field('title');
$description = get_sub_field('description');
$button      = get_sub_field('button');
$image       = get_sub_field('image');
$stats       = get_field('stats', 'option');
?>
<div class="wrapper-bg bg-linear-1-top-to-bottom">
	<section class="introduction-1 section-py">
		<div class="container">
			<div class="intro-grid">
				<div class="col-content">
					<?php if ($title) : ?>
					<h2 class="title-48 text-primary-1 font-medium mb-6" data-aos="fade-right">
						<?= nl2br(esc_html($title)); ?>
					</h2>
					<?php endif; ?>

					<?php if ($description) : ?>
					<div class="intro-desc" data-aos="fade-right" data-aos-delay="200">
						<?= wp_kses_post($description); ?>
					</div>
					<?php endif; ?>

					<?php if ($button) : ?>
					<a class="btn btn-primary" href="<?= esc_url($button['url']); ?>"
						target="<?= esc_attr($button['target'] ?: '_self'); ?>" data-aos="fade-up" data-aos-delay="300">
						<?= esc_html($button['title']); ?>
					</a>
					<?php endif; ?>
				</div>

				<div class="col-img" data-aos="fade-left" data-aos-delay="400">
					<div class="img-wrapper">
						<?= get_image_attrachment($image, 'image'); ?>
					</div>
				</div>
			</div>

			<?php if ($stats) : ?>
			<div class="list-stats">
				<?php foreach ($stats as $index => $stat) :
					$icon = $stat['icon'];
					$number = $stat['number'];
					$label = $stat['label'];
					$delay = 400 + ($index * 100);
				?>
				<div class="stat-item" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
					<div class="stat-icon">
						<?= get_image_attrachment($icon, 'image'); ?>
					</div>
					<div class="stat-info">
						<div class="stat-number"><span class="counter"><?= esc_html($number); ?></span><span>+</span>
						</div>
						<div class="stat-desc"><?= esc_html($label); ?></div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</section>