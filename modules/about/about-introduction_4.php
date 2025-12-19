<?php
$title    = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$items    = get_sub_field('items');
?>
<section class="introduction-4 section-py">
	<div class="container">
		<div class="header-center" data-aos="fade-up">
			<?php if ($title) : ?>
				<h2 class="title-center"><?= esc_html($title); ?></h2>
			<?php endif; ?>
			<?php if ($subtitle) : ?>
				<p class="title-20"><?= nl2br(esc_html($subtitle)); ?></p>
			<?php endif; ?>
		</div>

		<?php if ($items) : ?>
			<div class="accordion-container">
				<?php foreach ($items as $index => $item) :
					$active_class = $index === 0 ? 'active' : '';
					$delay = $index * 100;
				?>
					<div class="accordion-item <?= $active_class; ?>" data-index="<?= $index; ?>" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
						<div class="accordion-header">
							<span class="accordion-number">0<?= $index + 1; ?></span>
							<h3 class="accordion-title"><?= esc_html($item['title']); ?></h3>
							<span class="accordion-icon">
								<i class="fa-solid fa-plus"></i>
								<i class="fa-solid fa-minus"></i>
							</span>
						</div>
						<div class="accordion-content">
							<div class="content-inner">
								<?= $item['content']; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>