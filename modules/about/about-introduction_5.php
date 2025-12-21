<?php
/**
 * Introduction 5 - Machine & Equipment Section
 * 
 * ACF Field Assumptions (NEW FIELDS REQUIRED):
 * ---------------------------------------------
 * Repeater: 'rows' - Contains multiple row blocks
 *   - 'is_reverse' (true/false) - Whether this row uses reversed layout
 *   - 'title' (text) - Section title (e.g., "Máy Móc & Thiết Bị")
 *   - 'description' (textarea/wysiwyg) - Section description
 *   - Repeater: 'slides' - Swiper slides
 *       - Repeater: 'machines' - Machine items per slide (grid of 3)
 *           - 'image' (image) - Machine image
 *           - 'title' (text) - Machine name
 *           - 'link' (url/link) - Machine link (optional)
 *           - 'is_full' (true/false) - Full-width item flag
 * 
 * OR use existing fields with assumptions below:
 */

$rows = get_sub_field('rows');

// Fallback: If 'rows' field doesn't exist, try to use existing fields
if (!$rows) {
	// ASSUMPTION: Mapping existing fields to new structure
	// Existing: gallery, title, description, button
	// This assumes a single row without the machine grid pattern
	$title       = get_sub_field('title');
	$description = get_sub_field('description');
	$machines    = get_sub_field('machines'); // NEW FIELD NEEDED
}
?>
<section class="introduction-5 section-py bg-linear-2">
	<div class="container">
		<?php if ($rows) : ?>
			<?php foreach ($rows as $index => $row) :
				$is_reverse  = !empty($row['is_reverse']);
				$row_title   = $row['title'] ?? '';
				$row_desc    = $row['description'] ?? '';
				$slides      = $row['slides'] ?? [];
				
				$grid_class = $is_reverse ? 'wrapper-grid is-reverse' : 'wrapper-grid';
				$slider_aos = $is_reverse ? 'fade-left' : 'fade-right';
				$content_aos = $is_reverse ? 'fade-right' : 'fade-left';
				$slider_id = 'machine-slider-' . $index;
			?>
			<div class="<?= $grid_class; ?>">
				<div class="col-slider" data-aos="<?= $slider_aos; ?>" data-aos-delay="200">
					<div class="machine-slider relative" id="<?= $slider_id; ?>">
						<div class="swiper">
							<div class="swiper-wrapper">
								<?php if (!empty($slides)) : ?>
									<?php foreach ($slides as $slide) : ?>
										<div class="swiper-slide">
											<div class="machine-grid">
												<?php if (!empty($slide['machines'])) : ?>
													<?php foreach ($slide['machines'] as $machine) :
														$is_full = !empty($machine['is_full']);
														$item_class = $is_full ? 'machine-item full' : 'machine-item';
														$link = $machine['link'] ?? '#';
													?>
														<div class="<?= $item_class; ?>">
															<div class="img-wrapper">
																<?= get_image_attrachment($machine['image'], 'image'); ?>
															</div>
															<h4 class="title">
																<a href="<?= esc_url($link); ?>"><?= esc_html($machine['title']); ?></a>
															</h4>
														</div>
													<?php endforeach; ?>
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-content" data-aos="<?= $content_aos; ?>" data-aos-delay="200">
					<?php if ($row_title) : ?>
						<h2><?= esc_html($row_title); ?></h2>
					<?php endif; ?>
					<?php if ($row_desc) : ?>
						<div class="desc">
							<?= wpautop($row_desc); ?>
						</div>
					<?php endif; ?>
					<div class="group-controll in-static">
						<div class="wrap-button-slide machine-nav" data-target="#<?= $slider_id; ?>">
							<div class="btn-prev btn-slider-2"><i class="fa-solid fa-chevron-left"></i></div>
							<div class="btn-next btn-slider-2"><i class="fa-solid fa-chevron-right"></i></div>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		
		<?php elseif (!empty($machines)) : ?>
			<!-- Fallback: Single row using 'machines' field -->
			<div class="wrapper-grid">
				<div class="col-slider" data-aos="fade-right" data-aos-delay="200">
					<div class="machine-slider relative" id="machine-slider-0">
						<div class="swiper">
							<div class="swiper-wrapper">
								<div class="swiper-slide">
									<div class="machine-grid">
										<?php foreach ($machines as $index => $machine) :
											$is_full = !empty($machine['is_full']);
											$item_class = $is_full ? 'machine-item full' : 'machine-item';
											$link = $machine['link'] ?? '#';
										?>
											<div class="<?= $item_class; ?>">
												<div class="img-wrapper">
													<?= get_image_attrachment($machine['image'], 'image'); ?>
												</div>
												<h4 class="title">
													<a href="<?= esc_url($link); ?>"><?= esc_html($machine['title']); ?></a>
												</h4>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-content" data-aos="fade-left" data-aos-delay="200">
					<?php if ($title) : ?>
						<h2><?= esc_html($title); ?></h2>
					<?php endif; ?>
					<?php if ($description) : ?>
						<div class="desc">
							<?= wpautop($description); ?>
						</div>
					<?php endif; ?>
					<div class="group-controll in-static">
						<div class="wrap-button-slide machine-nav" data-target="#machine-slider-0">
							<div class="btn-prev btn-slider-2"><i class="fa-solid fa-chevron-left"></i></div>
							<div class="btn-next btn-slider-2"><i class="fa-solid fa-chevron-right"></i></div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>