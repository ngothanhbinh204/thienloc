<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<?php if (stripos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false) : ?>
	<?php endif; ?>
	<?php wp_head(); ?>
	<?= get_field('field_config_head', 'options') ?>
</head>

<body <?php body_class(get_field('add_class_body', get_the_ID())) ?>>
	<header class="header">
		<div class="header-wrapper flex items-center justify-between rounded-[80px]">
			<div class="logo">
				<a href="<?= esc_url(home_url('/')) ?>" rel="home">
					<?php
					if (has_custom_logo()) {
						$custom_logo_id = get_theme_mod('custom_logo');
						$logo = wp_get_attachment_image_src($custom_logo_id, 'full');

						if (!empty($logo)) :
					?>
					<img class="lozad h-12 w-auto object-contain" 
						src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
						data-src="<?= esc_url($logo[0]) ?>"
						alt="<?= esc_attr(get_bloginfo('name')) ?>" />
					<?php
						endif;
					}
					?>
				</a>
			</div>

			<div class="menu hidden lg:block">
				<div class="main-menu">
					<?php
					wp_nav_menu(array(
						'theme_location' => 'header-menu',
						'container'      => false,
						'menu_class'     => '',
						'fallback_cb' => false,
						'walker'         => new Header_Desktop_Menu_Walker(),
					));
					?>
				</div>

			</div>
			<div class="tools">
				<div class="search cursor-pointer"><i class="fa-light fa-magnifying-glass text-xl"></i></div>
				<div class="language relative">
					<div class="current-lang flex items-center gap-1 cursor-pointer"><img class="lozad flag"
							src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
							data-src="./img/flag-vn.svg" alt="" /><span>VN</span><i
							class="fa-light fa-chevron-down text-xs text-neutral-600 transition-transform duration-300"></i>
					</div>
					<div class="lang-dropdown">
						<ul>
							<li><a href="#"><img class="lozad flag" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="./img/flag-vn.svg"
										alt="" /><span>VN</span></a></li>
							<li><a href="#"><img class="lozad flag" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E" data-src="./img/flag-en.svg"
										alt="" /><span>EN</span></a></li>
						</ul>
					</div>
				</div>
				<div class="hamburger lg:hidden">
					<div class="header-hambuger"><span></span><span></span><span></span></div>
				</div>
			</div>
		</div>
		<div class="mobile-menu">
			<?php
				wp_nav_menu([
					'theme_location' => 'header-menu',
					'container'      => false,
					'fallback_cb' => false,
					'menu_class'     => 'flex flex-col gap-4',
					'walker'         => new Header_Mobile_Menu_Walker(),
				]);
				?>
		</div>
	</header>
	<main>