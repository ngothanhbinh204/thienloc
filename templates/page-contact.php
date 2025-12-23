<?php
/*
Template Name: Contact
*/
get_header();

$post_id = get_the_ID();
?>

<main>
	<div class="wrapper-gap-top">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<section class="section-contact section-py">
			<div class="container">
				<div class="wrapper-contact grid grid-cols-12 gap-10 lg:gap-15">

					<!-- CONTACT INFO -->
					<div class="contact-info col-span-12 lg:col-span-5">

						<h2>
							<?= esc_html(
								get_field('contact_company_name', $post_id)
								?: __('Công ty cổ phần đầu tư TT Capital', 'canhcamtheme')
							); ?>
						</h2>

						<div class="contact-list">

							<!-- ADDRESS -->
							<div class="contact-item">
								<div class="contact-icon">
									<i class="fa-solid fa-location-dot fa-fw"></i>
								</div>

								<div class="contact-content">
									<div class="contact-label">
										<?= esc_html__('Địa chỉ văn phòng', 'canhcamtheme'); ?>
									</div>

									<div class="contact-value">
										<?php
									$address = get_field('contact_address', $post_id);

									if ($address) {
										echo wp_kses_post($address);
									} else {
										echo '<p>' . esc_html__('Đang cập nhật', 'canhcamtheme') . '</p>';
									}
									?>
									</div>
								</div>
							</div>


							<!-- PHONE -->
							<div class="contact-item">
								<div class="contact-icon">
									<i class="fa-solid fa-phone fa-fw"></i>
								</div>
								<div class="contact-content">
									<div class="contact-label">
										<?= esc_html__('Điện thoại', 'canhcamtheme'); ?>
									</div>
									<div class="contact-value">
										<?php if (have_rows('contact_phones', $post_id)) : ?>
										<?php
											$phones = [];
											while (have_rows('contact_phones', $post_id)) : the_row();
												$display = get_sub_field('phone_display');
												$link    = get_sub_field('phone_link');

												if ($display && $link) {
													$phones[] = sprintf(
														'<a href="tel:%s">%s</a>',
														esc_attr($link),
														esc_html($display)
													);
												}
											endwhile;

											echo implode('<span class="mx-2">-</span>', $phones);
											?>
										<?php else : ?>
										<span><?= esc_html__('Đang cập nhật', 'canhcamtheme'); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<!-- EMAIL -->
							<div class="contact-item">
								<div class="contact-icon">
									<i class="fa-solid fa-envelope fa-fw"></i>
								</div>
								<div class="contact-content">
									<div class="contact-label">
										<?= esc_html__('Email', 'canhcamtheme'); ?>
									</div>
									<div class="contact-value">
										<?php
										$email = get_field('contact_email', $post_id);
										if ($email) :
										?>
										<a href="mailto:<?= esc_attr($email); ?>">
											<?= esc_html($email); ?>
										</a>
										<?php else : ?>
										<span><?= esc_html__('Đang cập nhật', 'canhcamtheme'); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</div>

						</div>

						<!-- MAP -->
						<div class="contact-map">
							<?php
							$map = get_field('contact_map', $post_id);
							if ($map) {
								echo $map; // trusted iframe/html
							}
							?>
						</div>

					</div>

					<!-- CONTACT FORM -->
					<div class="contact-form-wrapper col-span-12 lg:col-span-7">

						<?php if ($intro = get_field('form_intro', $post_id)) : ?>
						<div class="form-header">
							<p class="body-18">
								<?= wp_kses_post($intro); ?>
							</p>
						</div>
						<?php endif; ?>

						<?php
						$form_shortcode = get_field('form_contact_shortcode', $post_id);

						if ($form_shortcode) :
							echo do_shortcode($form_shortcode);
						else :
						?>
						<p class="text-center text-gray-500">
							<?= esc_html__('Form đang được cập nhật.', 'canhcamtheme'); ?>
						</p>
						<?php endif; ?>

					</div>

				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>