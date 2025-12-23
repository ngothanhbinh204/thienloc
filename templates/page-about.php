<?php 
/*
Template name: Page - About
*/ 
?>
<?= get_header() ?>
<?php
	$image_decor_top = get_field('image_decor_top');
?>
<?php get_template_part('modules/common/banner')?>

<div class="wrapper-introduction">
	<div class="bg-decor-top">
		<?php
		if ($image_decor_top) {
			echo get_lozad_img($image_decor_top['url'], $image_decor_top['alt']);
		}
		?>
	</div>


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