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
						data-src="<?= esc_url($logo[0]) ?>" alt="<?= esc_attr(get_bloginfo('name')) ?>" />
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
					<?php
					$languages = apply_filters('wpml_active_languages', NULL, 'skip_missing=1');
					if (!empty($languages)) {
						$current_lang = null;
						foreach ($languages as $l) {
							if ($l['active']) {
								$current_lang = $l;
								break;
							}
						}

						if ($current_lang) {
							$has_translations = count($languages) > 1;
							$cur_code = ($current_lang['code'] === 'vi') ? 'vn' : $current_lang['code'];
							$cur_flag = get_template_directory_uri() . '/img/flag-' . $cur_code . '.svg';
							$cur_label = strtoupper($cur_code);
					?>
					<div class="current-lang flex items-center gap-1 <?= $has_translations ? 'cursor-pointer' : '' ?>"><img class="lozad flag"
							src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
							data-src="<?= esc_url($cur_flag) ?>" alt="<?= esc_attr($cur_label) ?>" /><span><?= esc_html($cur_label) ?></span>
						<?php if ($has_translations) : ?>
						<i class="fa-light fa-chevron-down text-xs text-neutral-600 transition-transform duration-300"></i>
						<?php endif; ?>
					</div>
					<?php if ($has_translations) : ?>
					<div class="lang-dropdown">
						<ul>
							<?php foreach ($languages as $l) :
								if (!$l['active']) :
									$l_code = ($l['code'] === 'vi') ? 'vn' : $l['code'];
									$l_flag = get_template_directory_uri() . '/img/flag-' . $l_code . '.svg';
									$l_label = strtoupper($l_code);
							?>
							<li><a href="<?= esc_url($l['url']) ?>"><img class="lozad flag"
										src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
										data-src="<?= esc_url($l_flag) ?>" alt="<?= esc_attr($l_label) ?>" /><span><?= esc_html($l_label) ?></span></a></li>
							<?php endif;
							endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
					<?php
						}
					}
					?>
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