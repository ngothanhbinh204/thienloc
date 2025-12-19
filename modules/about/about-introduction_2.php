<?php
$image_main  = get_sub_field('image_main');
$image_sub   = get_sub_field('image_sub');
$title       = get_sub_field('title');
$description = get_sub_field('description');
$features    = get_sub_field('features');
?>
<section class="introduction-2 section-py">
	<div class="container">
		<div class="intro-grid">
			<div class="col-item" data-aos="fade-right">
				<div class="wrapper-img">
					<div class="img-main">
						<?= get_image_attrachment($image_main, 'image'); ?>
					</div>
					<div class="img-absolute">
						<?= get_image_attrachment($image_sub, 'image'); ?>
					</div>
				</div>
			</div>
			<div class="col-item" data-aos="fade-left" data-aos-delay="200">
				<div class="content">
					<?php if ($title) : ?>
						<h2 class="title-48 text-primary-1 font-medium mb-6">
							<?= nl2br(esc_html($title)); ?>
						</h2>
					<?php endif; ?>

					<?php if ($description) : ?>
						<div class="content-html text-justify mb-6">
							<?= $description; ?>
						</div>
					<?php endif; ?>

					<?php if ($features) : ?>
						<ul class="list-check">
							<?php foreach ($features as $feature) : ?>
								<li>
									<i class="fa-solid fa-check"></i>
									<span><?= esc_html($feature['text']); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>