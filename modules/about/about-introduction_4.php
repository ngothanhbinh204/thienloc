<?php
/**
 * Layout: introduction_4
 */

$title    = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$items    = get_sub_field('items');
$image_decor_bottom = get_field('image_decor_bottom');
?>

<div class="wrapper-bg">

	<?php if (!empty($image_decor_bottom)) : ?>
	<div class="bg-decor-bottom">
		<?= get_lozad_img(
				$image_decor_bottom['url'],
				$image_decor_bottom['alt'] ?? ''
			); ?>
	</div>
	<?php endif; ?>

	<section class="introduction-4 section-py">
		<div class="section-expand-rotate">
			<div class="container">

				<?php if ($title || $subtitle) : ?>
				<div class="section-header header-center">
					<?php if ($title) : ?>
					<h2 class="title-center"><?= esc_html($title); ?></h2>
					<?php endif; ?>

					<?php if ($subtitle) : ?>
					<div class="desc">
						<?= wp_kses_post($subtitle); ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php if (!empty($items)) : ?>
				<div class="cards">
					<?php foreach ($items as $index => $item) :

							$bg_image   = $item['background_image'] ?? null;
							$icon_svg  = $item['icon_svg'] ?? '';
							$icon_img  = $item['icon_image'] ?? null;
							$item_title = $item['item_title'] ?? '';
							$desc       = $item['item_description'] ?? '';
							$is_expanded = !empty($item['is_expanded']);

							$card_class = $is_expanded ? 'expanded' : '';
						?>
					<div class="card-wrapper <?= esc_attr($card_class); ?>" data-index="<?= esc_attr($index); ?>">
						<div class="card-container">

							<?php if (!empty($bg_image)) : ?>
							<div class="bg" style="background-image:url('<?= esc_url($bg_image['url']); ?>')">
							</div>
							<?php endif; ?>

							<div class="overlay"></div>

							<div class="content-box">

								<?php if ($icon_svg || $icon_img) : ?>
								<div class="icon">
									<?php
												if ($icon_svg) {
													echo $icon_svg; // SVG inline – trusted admin
												} elseif (!empty($icon_img)) {
													echo get_lozad_img(
														$icon_img['url'],
														$icon_img['alt'] ?? ''
													);
												}
												?>
								</div>
								<?php endif; ?>

								<?php if ($item_title) : ?>
								<div class="title-wrap">
									<h3 class="title"><?= esc_html($item_title); ?></h3>
								</div>
								<?php endif; ?>

								<?php if ($desc) : ?>
								<div class="description">
									<?= wp_kses_post($desc); ?>
								</div>
								<?php endif; ?>

							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</section>