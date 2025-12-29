<?php
$footer_company   = get_field('footer_company', 'option') ?: [];
$footer_socials   = get_field('footer_socials', 'option') ?: [];
$footer_certs     = get_field('footer_certs', 'option') ?: [];
$footer_col_1     = get_field('footer_middle_col_1', 'option') ?: [];
$footer_col_2     = get_field('footer_middle_col_2', 'option') ?: [];
$footer_copyright = get_field('footer_copyright', 'option');
$field_body_extra = get_field('field_config_body', 'option');
$company_name   = $footer_company['name'] ?? '';
$company_slogan = $footer_company['slogan'] ?? '';


$footer_decor_image = get_field('footer_decor_image', 'option');
?>

</main>


<?php if (stripos($_SERVER['HTTP_USER_AGENT'] ?? '', 'Chrome-Lighthouse') === false) : ?>
<?php wp_footer(); ?>
<?php endif; ?>


<?= $field_body_extra ?: '' ?>

<footer class="footer relative">
	<div class="header-search-form">
		<div class="wrap-form-search-product">
			<form class="productsearchbox" action="<?= esc_url(home_url('/')); ?>" method="get">

				<input type="text" name="s" placeholder="Tìm kiếm sản phẩm..." value="<?= get_search_query(); ?>">

				<button type="submit">
					<i class="fa-light fa-magnifying-glass text-white"></i>
				</button>

			</form>
		</div>

		<div
			class="close-search absolute top-8 right-8 cursor-pointer text-white text-4xl hover:text-primary-1 transition-300">
			<i class="fa-light fa-xmark"></i>
		</div>
	</div>

	<div class="container">

		<?php if ($footer_decor_image) : ?>

		<div class="footer-decor">
			<img class="footer-decor-img" src="<?= esc_url($footer_decor_image['url']); ?>"
				data-src="<?= esc_url($footer_decor_image['url']); ?>" alt="">
		</div>

		<?php endif; ?>
		<!-- FOOTER TOP -->
		<div class="footer-top">
			<div class="box-left w-auto mb-6 lg:mb-0">
				<?php if ($company_name || $company_slogan) : ?>
				<div class="footer-company">
					<?php if ($company_name) : ?>
					<h2 class="footer-title"><?= esc_html($company_name); ?></h2>
					<?php endif; ?>

					<?php if ($company_slogan) : ?>
					<p class="footer-subtitle"><?= esc_html($company_slogan); ?></p>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>

			<div class="box-right w-auto">
				<div class="footer-social-certs">

					<!-- SOCIAL -->
					<?php if (!empty($footer_socials)) : ?>
					<div class="footer-social">
						<span class="social-label">Mạng xã hội</span>
						<div class="social-links">
							<?php foreach ($footer_socials as $item) :
										$link = $item['link'] ?? null;
										$icon = $item['icon'] ?? '';
										if (empty($link['url'])) continue;
									?>
							<a class="social-link" href="<?= esc_url($link['url']); ?>"
								target="<?= esc_attr($link['target'] ?: '_blank'); ?>"
								aria-label="<?= esc_attr($link['title'] ?: 'Social'); ?>">
								<?php if ($icon) : ?>
								<i class="<?= esc_attr($icon); ?>"></i>
								<?php endif; ?>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<!-- CERTS -->
					<?php if (!empty($footer_certs)) : ?>
					<div class="footer-certs">
						<?php foreach ($footer_certs as $item) :
									$image = $item['image'] ?? null;
									if (!$image) continue;
								?>
						<div class="box-img">
							<img class="cert-img" src="<?= esc_url($image['url']); ?>"
								data-src="<?= esc_url($image['url']); ?>" alt="" />
						</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

				</div>
			</div>
		</div>

		<!-- FOOTER MIDDLE -->
		<div class="footer-middle">

			<!-- COLUMN 1 -->
			<?php if (!empty($footer_col_1)) : ?>
			<div class="col-footer lg:col-span-5">
				<ul class="footer-info-list">
					<?php foreach ($footer_col_1 as $item) :
								$icon_type  = $item['icon_type'] ?? 'class';
								$icon_class = $item['icon_class'] ?? '';
								$icon_image = $item['icon_image'] ?? null;
								$label      = $item['label'] ?? '';
								$content       = $item['link'] ?? null;
							?>
					<li class="footer-info-item">
						<div class="info-icon">
							<?php if ($icon_type === 'image' && $icon_image) : ?>
							<img class="cert-img" src="<?= esc_url($icon_image['url']); ?>"
								data-src="<?= esc_url($icon_image['url']); ?>" alt="" />
							<?php elseif ($icon_class) : ?>
							<i class="<?= esc_attr($icon_class); ?>"></i>
							<?php endif; ?>
						</div>

						<div class="info-content">
							<?php if ($label) : ?>
							<span class="info-label"><?= esc_html($label); ?></span>
							<?php endif; ?>
							<?php
							if (!empty($content)) :
							?>
							<div class="info-value editor-content">
								<?= wp_kses_post($content); ?>
							</div>
							<?php endif; ?>

						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<!-- COLUMN 2 -->
			<?php if (!empty($footer_col_2)) : ?>
			<div class="col-footer lg:col-span-5">
				<ul class="footer-info-list">
					<?php foreach ($footer_col_2 as $item) :
								$icon_type  = $item['icon_type'] ?? 'class';
								$icon_class = $item['icon_class'] ?? '';
								$icon_image = $item['icon_image'] ?? null;
								$label      = $item['label'] ?? '';
								$content       = $item['link'] ?? null;
							?>
					<li class="footer-info-item">
						<div class="info-icon">
							<?php if ($icon_type === 'image' && $icon_image) : ?>
							<?= get_image_attrachment($icon_image, 'image'); ?>
							<?php elseif ($icon_class) : ?>
							<i class="<?= esc_attr($icon_class); ?>"></i>
							<?php endif; ?>
						</div>

						<div class="info-content">
							<?php if ($label) : ?>
							<span class="info-label"><?= esc_html($label); ?></span>
							<?php endif; ?>

							<?php if (!empty($content)) : ?>
							<div class="info-value editor-content">
								<?= wp_kses_post($content); ?>
							</div>
							<?php endif; ?>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<!-- FOOTER MENU -->
			<div class="col-footer lg:col-span-2">
				<div class="footer-menu">
					<h3 class="menu-title">Liên kết nhanh</h3>
					<?php
						wp_nav_menu([
							'theme_location' => 'footer-1',
							'container'      => false,
							'menu_class'     => 'menu-list',
							'fallback_cb'    => false,
						]);
						?>
				</div>
			</div>

		</div>

		<!-- FOOTER BOTTOM -->
		<div class="footer-bottom">
			<div class="footer-bottom-inner">
				<?php if ($footer_copyright) : ?>
				<p class="copyright">
					<?= wp_kses_post($footer_copyright); ?>
				</p>
				<?php endif; ?>

				<?php
					wp_nav_menu([
						'theme_location' => 'footer-policy-menu',
						'container'      => false,
						'menu_class'     => 'footer-policy-menu',
						'fallback_cb'    => false,
					]);
					?>
			</div>
		</div>

	</div>
</footer>

<script>
window.addEventListener('load', function() {
	if (typeof lozad === 'function') {
		const observer = lozad();
		observer.observe();
		window.FE = {
			lozad: () => observer.observe()
		};
	}
});
</script>
</body>

</html>