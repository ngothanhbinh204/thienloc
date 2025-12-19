<?php
/*
Template Name: Contact
*/
get_header();
?>

<main>
	<div class="wrapper-gap-top">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<section class="section-contact section-py">
			<div class="container">
				<div class="wrapper-contact grid grid-cols-12 gap-10 lg:gap-15">
					<div class="contact-info col-span-12 lg:col-span-5">
						<h2><?= get_field('contact_company_name') ?: 'CÔNG TY CỔ PHẦN ĐẦU TƯ TT CAPITAL'; ?></h2>
						<div class="contact-list">
							<div class="contact-item">
								<div class="contact-icon"><i class="fa-solid fa-location-dot fa-fw"></i></div>
								<div class="contact-content">
									<div class="contact-label">ĐỊA CHỈ VĂN PHÒNG</div>
									<div class="contact-value">
										<p><?= get_field('contact_address') ?: 'Lầu 14, Tòa Nhà Lim 2, Số 62A Cách Mạng Tháng 8, Phường Võ Thị Sáu, Quận 3, Thành phố Hồ Chí Minh, Việt Nam'; ?>
										</p>
									</div>
								</div>
							</div>
							<div class="contact-item">
								<div class="contact-icon"><i class="fa-solid fa-phone fa-fw"></i></div>
								<div class="contact-content">
									<div class="contact-label">ĐIỆN THOẠI</div>
									<div class="contact-value">
										<?php 
										if (have_rows('contact_phones')) :
											$phones = array();
											while (have_rows('contact_phones')) : the_row();
												$display = get_sub_field('phone_display');
												$link = get_sub_field('phone_link');
												$phones[] = '<a href="tel:' . esc_attr($link) . '">' . esc_html($display) . '</a>';
											endwhile;
											echo implode('<span> - </span>', $phones);
										else:
										?>
										<a href="tel:+842835352694">(+84) 28 3535 2694</a><span> - </span><a
											href="tel:+842835352672">(+84) 28 3535 2672</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<div class="contact-item">
								<div class="contact-icon"><i class="fa-solid fa-envelope fa-fw"></i></div>
								<div class="contact-content">
									<div class="contact-label">EMAIL</div>
									<div class="contact-value">
										<?php $email = get_field('contact_email') ?: 'info@ttcapitalgroup.vn'; ?>
										<a href="mailto:<?= esc_attr($email); ?>"><?= esc_html($email); ?></a>
									</div>
								</div>
							</div>
						</div>
						<div class="contact-map">
							<?php 
							$map = get_field('contact_map');
							if ($map) {
								echo $map;
							} else {
							?>
							<iframe
								src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.3239936162194!2d106.68528931533381!3d10.787308792312827!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f35c43de38b%3A0x5e0e5c0c0c0c0c0c!2zNjJBIEPDoWNoIE3huqFuZyBUaMOhbmcgOCwgUGjGsOG7nW5nIDExLCBRdeG6rW4gMywgVGjDoG5oIHBo4buRIEjhu5MgQ2jDrSBNaW5o!5e0!3m2!1svi!2s!4v1234567890123!5m2!1svi!2s"
								width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
								referrerpolicy="no-referrer-when-downgrade"></iframe>
							<?php } ?>
						</div>
					</div>
					<div class="contact-form-wrapper col-span-12 lg:col-span-7">
						<div class="form-header">
							<p class="body-18">
								<?= get_field('form_intro') ?: 'Mọi sự hợp tác tốt đẹp đều bắt đầu từ một cuộc trò chuyện. Hãy liên hệ với chúng tôi, mọi mong muốn của bạn sẽ được lắng nghe.'; ?>
							</p>
						</div>

						<?php 
						$cf7_shortcode = get_field('contact_form_shortcode');
						if ($cf7_shortcode) :
							echo do_shortcode($cf7_shortcode);
						else :
						?>
						<form class="contact-form" action="#" method="post">
							<div class="form-row">
								<div class="form-group">
									<input class="form-control" type="text" name="name" placeholder="Họ và tên"
										required>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<input class="form-control" type="email" name="email" placeholder="Email" required>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<input class="form-control" type="tel" name="phone" placeholder="Số điện thoại"
										required>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<input class="form-control" type="text" name="subject" placeholder="Tiêu đề"
										required>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<textarea class="form-control" name="message" rows="5" placeholder="Nội dung"
										required></textarea>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<div class="captcha-box">
										<div class="captcha-checkbox">
											<input type="checkbox" id="captcha-check">
											<label for="captcha-check">Tôi không phải là người máy</label>
										</div>
										<div class="captcha-logo"><img
												src="https://www.google.com/recaptcha/about/images/reCAPTCHA-logo.png"
												alt="reCAPTCHA"></div>
									</div>
								</div>
							</div>
							<div class="form-row">
								<button class="btn btn-primary" type="submit">GỬI NGAY<i
										class="fa-solid fa-arrow-right"></i></button>
							</div>
						</form>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>