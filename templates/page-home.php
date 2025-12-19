<?php 
/*
Template name: Page - Home
*/ 
?>
<?= get_header() ?>
<?php
// $breadcrumbs = canhcam_get_breadcrumbs();

// if (empty($breadcrumbs)) {
// 	return;
// }
?>
<?php get_template_part('modules/common/banner')?>
<?php
if (have_rows('home_sections')) :
	while (have_rows('home_sections')) : the_row();
		$layout = get_row_layout();
		get_template_part('modules/home/home-' . get_row_layout());
	endwhile;
endif;
?>
<?php get_template_part('modules/common/section-cta')?>

<?= get_footer() ?>