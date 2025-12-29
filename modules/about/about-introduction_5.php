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
 *       - Repeater: 'machines' - Machine items per slide (grid of 3) (Note: We flatten this for auto-slide generation)
 *           - Relationship: 'machines' - Product Post Objects
 */

$rows = get_sub_field('rows');

// Fallback: If 'rows' field doesn't exist, try to use existing fields
if (!$rows) {
	$title       = get_sub_field('title');
	$description = get_sub_field('description');
	$machines    = get_sub_field('machines');
}
?>
<section class="introduction-5 section-py bg-linear-2">
	<div class="container">
		<?php if ($rows) : ?>
		<?php foreach ($rows as $index => $row) :
				$is_reverse  = !empty($row['is_reverse']);
				$row_title   = $row['title'] ?? '';
				$row_desc    = $row['description'] ?? '';
				$slides_data = $row['slides'] ?? [];
				
				// 1. Flatten all machines from the ACF repeater structure
				// We collect all items and normalize them into a standard structure
				$all_items = [];
				if (!empty($slides_data)) {
					foreach ($slides_data as $s) {
						$type = $s['type'] ?? 'relationship';

						if ($type === 'custom') {
							// Handle Custom Items
							$custom_items = $s['custom_items'] ?? [];
							if (!empty($custom_items)) {
								foreach ($custom_items as $item) {
									$all_items[] = [
										'type'   => 'custom',
										'image'  => $item['image'],
										'title'  => $item['title'],
										'link'   => null,
									];
								}
							}
						} else {
							// Handle Relationship (Default)
							$machines = $s['machines'] ?? [];
							if (!empty($machines)) {
								foreach ($machines as $m) {
									if ($m instanceof WP_Post) {
										$all_items[] = [
											'type'   => 'post',
											'image'  => get_post_thumbnail_id($m->ID),
											'title'  => get_the_title($m->ID),
											'link'   => get_permalink($m->ID),
										];
									}
								}
							}
						}
					}
				}

				// 3. Chunk items into groups of 3
				$slides_chunked = !empty($all_items) ? array_chunk($all_items, 3) : [];
				
				// 4. Ensure last slide always has 3 items
				$total_items = count($all_items);

				if ($total_items > 3 && !empty($slides_chunked)) {
					$last_index = count($slides_chunked) - 1;
					$last_slide = $slides_chunked[$last_index];

					$missing = 3 - count($last_slide);

					if ($missing > 0) {
						$补 = array_slice($all_items, 0, $missing);
						$slides_chunked[$last_index] = array_merge($last_slide, $补);
					}
				}
				
				$grid_class = $is_reverse ? 'wrapper-grid is-reverse' : 'wrapper-grid';
				$slider_aos = $is_reverse ? 'fade-left' : 'fade-right';
				$content_aos = $is_reverse ? 'fade-right' : 'fade-left';
				$slider_id = 'machine-slider-' . $index;
			?>
		<div class="<?= $grid_class; ?>">
			<div class="col-slider">
				<div class="machine-slider relative" id="<?= $slider_id; ?>">
					<div class="swiper">
						<div class="swiper-wrapper">
							<?php if (!empty($slides_chunked)) : ?>
							<?php foreach ($slides_chunked as $chunk_items) : ?>
							<div class="swiper-slide">
								<div class="machine-grid">
									<?php foreach ($chunk_items as $item) : ?>
									<div class="machine-item">
										<div class="img-wrapper">
											<?= get_image_attrachment($item['image'], 'image'); ?>
										</div>
										<h4 class="title">
											<?php if ($item['type'] === 'post' && !empty($item['link'])) : ?>
											<a
												href="<?= esc_url($item['link']); ?>"><?= esc_html($item['title']); ?></a>
											<?php else : ?>
											<?= esc_html($item['title']); ?>
											<?php endif; ?>
										</h4>
									</div>
									<?php endforeach; ?>
								</div>
							</div>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-content" >
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
		<!-- Fallback logic if needed, currently empty as per requirement to use new structure -->
		<?php endif; ?>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
	setTimeout(() => {
		const sliders = document.querySelectorAll('.introduction-5 .wrapper-grid');

		sliders.forEach(wrapper => {
			const swiperEl = wrapper.querySelector('.swiper');
			const nextBtn = wrapper.querySelector('.btn-next');
			const prevBtn = wrapper.querySelector('.btn-prev');

			if (swiperEl && nextBtn && prevBtn) {
				// Clone buttons to remove any existing event listeners
				const newNextBtn = nextBtn.cloneNode(true);
				const newPrevBtn = prevBtn.cloneNode(true);
				nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
				prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);

				if (swiperEl.swiper) {
					Object.assign(swiperEl.swiper.params.navigation, {
						nextEl: newNextBtn,
						prevEl: newPrevBtn,
					});

					swiperEl.swiper.navigation.destroy();
					swiperEl.swiper.navigation.init();
					swiperEl.swiper.navigation.update();
				} else {
					new Swiper(swiperEl, {
						slidesPerView: 1,
						spaceBetween: 30,
						speed: 800,
						navigation: {
							nextEl: newNextBtn,
							prevEl: newPrevBtn,
						},
					});
				}
			}
		});
	}, 500);
});
</script>