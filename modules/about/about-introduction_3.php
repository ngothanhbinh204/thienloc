<?php
$title   = get_sub_field('title');
$history = get_sub_field('history');
?>
<section class="introduction-3 section-py">
	<div class="container">
		<?php if ($title) : ?>
			<h2 data-aos="fade-up"><?= esc_html($title); ?></h2>
		<?php endif; ?>

		<?php if ($history) : ?>
			<div class="history-timeline relative" data-aos="fade-up" data-aos-delay="200">
				<div class="swiper">
					<div class="swiper-wrapper">
						<?php foreach ($history as $item) : ?>
							<div class="swiper-slide">
								<div class="timeline-item">
									<div class="year"><?= esc_html($item['year']); ?></div>
									<div class="dot"></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="line-bg"></div>
			</div>

			<div class="wrapper-history-main relative" data-aos="fade-up" data-aos-delay="400">
				<div class="history-main relative">
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php foreach ($history as $item) : ?>
								<div class="swiper-slide">
									<div class="history-item">
										<div class="history-img">
											<?= get_image_attrachment($item['image'], 'image'); ?>
										</div>
										<div class="history-content">
											<div class="year-big"><?= esc_html($item['year']); ?></div>
											<h3 class="title"><?= esc_html($item['title']); ?></h3>
											<div class="desc">
												<?= nl2br(esc_html($item['description'])); ?>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="center-y">
					<div class="history-prev"><i class="fa-solid fa-chevron-left"></i></div>
					<div class="history-next"><i class="fa-solid fa-chevron-right"></i></div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>