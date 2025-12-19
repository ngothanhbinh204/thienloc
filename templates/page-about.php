<?php 
/*
Template name: Page - About
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

<div class="wrapper-introduction">
	<div class="bg-decor-top"><img class="lozad" data-src="<?= get_template_directory_uri() ?>/UI/img/bg-partent-1.svg" alt=""/></div>
	<div class="bg-decor-bottom"><img class="lozad" data-src="<?= get_template_directory_uri() ?>/UI/img/bg-partent-1.svg" alt=""/></div>
	
	<?php
	if (have_rows('about_sections')) :
		while (have_rows('about_sections')) : the_row();
			$layout = get_row_layout();
			get_template_part('modules/about/about-' . get_row_layout());
		endwhile;
	endif;
	?>
</div>

<?php get_template_part('modules/common/section-cta')?>

<?= get_footer() ?>