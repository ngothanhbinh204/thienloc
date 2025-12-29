<?php
$bg_image   = get_field('cta_background', 'option') ?? null;

/** Form column */
$form_title    = get_field('cta_form_title', 'option') ?? '';
$form_subtitle = get_field('cta_form_subtitle', 'option') ?? '';
$form_shortcode = get_field('cta_form_shortcode', 'option') ?? '';

/** CTA column */
$cta_title = get_field('cta_title', 'option') ?? '';
$cta_desc  = get_field('cta_description', 'option') ?? '';
$cta_btn   = get_field('cta_link', 'option') ?? null;

/** Hotline */
$hotline_label = get_field('cta_hotline_label_1', 'option') ?? '';
$hotline_label_2 = get_field('cta_hotline_label_2', 'option') ?? '';
$hotline_number  = get_field('cta_hotline_number', 'option') ?? '';

if (
	!$form_title &&
	!$cta_title &&
	!$hotline_number
) {
	return;
}
?>

<section class="section-home-6 section-py relative">
	<?php if ($bg_image): ?>
	<div class="home-6-bg absolute-full z-0">
		<img class="lozad w-full h-full object-cover" data-src="<?= esc_url($bg_image['url']); ?>"
			alt="<?= esc_attr($bg_image['alt'] ?? ''); ?>">
	</div>
	<?php endif; ?>

	<div class="container relative z-10">
		<div class="items-center wrapper-cta">

			<!-- FORM COLUMN -->
			<div class="form-col w-full mb-8 lg:mb-0">
				<?php if ($form_title || $form_subtitle || $form_shortcode): ?>
				<div class="contact-form-wrapper">
					<?php if ($form_title || $form_subtitle): ?>
					<div class="form-header">
						<?php if ($form_title): ?>
						<h3 class="form-title"><?= wp_kses_post($form_title); ?></h3>
						<?php endif; ?>

						<?php if ($form_subtitle): ?>
						<p class="form-subtitle"><?= esc_html($form_subtitle); ?></p>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php if ($form_shortcode): ?>
					<div class="contact-form">
						<?= do_shortcode($form_shortcode); ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>

			<!-- CONTENT COLUMN -->
			<div class="content-col w-full lg:ml-auto">
				<div class="cta-content">

					<?php if ($cta_title || $cta_desc || $cta_btn): ?>
					<div class="cta-header">
						<?php if ($cta_title): ?>
						<h2 class="cta-title"><?= wp_kses_post($cta_title); ?></h2>
						<?php endif; ?>

						<?php if ($cta_desc): ?>
						<div class="cta-desc"><?= wp_kses_post($cta_desc); ?></div>
						<?php endif; ?>

						<?php if ($cta_btn): ?>
						<a class="btn btn-secondary-outline" href="<?= esc_url($cta_btn['url']); ?>"
							target="<?= esc_attr($cta_btn['target'] ?: '_self'); ?>">
							<?= esc_html($cta_btn['title']); ?>
						</a>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<?php if ($hotline_number): ?>
					<div class="cta-hotline">
						<div class="hotline-left">
							<?php if ($hotline_label): ?>
							<div class="hotline-label">
								<?= $hotline_label ?>
							</div>
							<?php endif; ?>
						</div>

						<div class="hotline-right">
							<span class="hotline-tag">
								<?php if ($hotline_label_2): ?>
								<?= esc_html($hotline_label_2); ?>
								<?php endif; ?>
							</span>
							<div class="hotline-number"><?= wp_kses_post($hotline_number); ?></div>
						</div>
					</div>
					<?php endif; ?>

				</div>
			</div>

		</div>
	</div>
</section>