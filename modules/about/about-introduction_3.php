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
			<!-- Timeline Navigation Slider -->
			<div class="history-timeline relative" data-aos="fade-up" data-aos-delay="200">
				<div class="swiper">
					<div class="swiper-wrapper">
						<?php foreach ($history as $item) : ?>
							<div class="swiper-slide">
								<div class="year-dot">
									<span class="year"><?= esc_html($item['year']); ?></span>
									<span class="dot"></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="line-bg"></div>
			</div>

			<!-- History Main Content Slider -->
			<div class="wrapper-history-main relative" data-aos="fade-up" data-aos-delay="400">
				<div class="history-main relative">
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php foreach ($history as $index => $item) :
								$odd_even_class = ($index % 2 === 0) ? 'is-odd' : 'is-even';
							?>
								<div class="swiper-slide">
									<div class="history-item <?= $odd_even_class; ?>">
										<div class="content">
											<div class="item-year">
												<span class="dot"></span>
												<span class="text"><?= esc_html($item['year']); ?></span>
											</div>
											<div class="item-content">
												<h3 class="title"><?= esc_html($item['title']); ?></h3>
												<p class="desc"><?= esc_html($item['description']); ?></p>
											</div>
										</div>
										<div class="item-img">
											<?= get_image_attrachment($item['image'], 'image'); ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="center-y">
					<div class="wrap-button-slide history-nav">
						<div class="btn-prev btn-slider-2"><i class="fa-solid fa-chevron-left"></i></div>
						<div class="btn-next btn-slider-2"><i class="fa-solid fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>