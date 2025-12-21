<?php
$items = get_sub_field('items');
?>
<section class="introduction-2 section-py">
	<div class="container">
		<div class="intro-grid">
			<?php if ($items) :
				foreach ($items as $key => $item) :
					$icon = $item['icon'];
					$title = $item['title'];
					$description = $item['description'];
					$image = $item['image'];

					$aos_anim = ($key % 2 == 0) ? 'fade-right' : 'fade-left';
					$aos_delay = ($key % 2 != 0) ? 'data-aos-delay="200"' : '';
			?>
			<div class="col-item" data-aos="<?= $aos_anim ?>" <?= $aos_delay ?>>
				<div class="header-box">
					<?php if ($icon) : ?>
					<div class="icon">
						<?= get_image_attrachment($icon, 'image'); ?>
					</div>
					<?php endif; ?>
					<?php if ($title) : ?>
					<h3 class="title"><?= $title ?></h3>
					<?php endif; ?>
				</div>
				<?php if ($description) : ?>
				<div class="desc">
					<?= $description ?>
				</div>
				<?php endif; ?>
				<?php if ($image) : ?>
				<div class="img-wrapper">
					<?= get_image_attrachment($image, 'image'); ?>
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach;
			endif; ?>
		</div>
	</div>
</section>