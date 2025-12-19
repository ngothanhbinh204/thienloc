<?php get_header(); ?>

<main>
	<!-- Banner Section -->
	<section class="page-banner relative">
		<div class="swiper banner-swiper">
			<div class="swiper-wrapper">
				<?php
				// Get the ID of the page set as "Posts page"
				$page_for_posts_id = get_option('page_for_posts');
				$banners = get_field('banner', $page_for_posts_id);
				
				if ($banners) :
					foreach ($banners as $banner) :
						$image_url = $banner['image']['url'];
						$title = $banner['title'];
						$desc = $banner['description'];
						$link = $banner['link'];
						$link_text = $banner['link_text'];
				?>
						<div class="swiper-slide">
							<div class="banner-item relative h-full w-full">
								<div class="banner-img">
									<img class="lozad w-full h-full object-cover" data-src="<?= $image_url ?>" alt="<?= esc_attr($title) ?>" />
								</div>
								<div class="content flex items-center">
									<div class="wrapper-content container">
										<?php if ($title) : ?><h2 class="title-57 text-white font-bold mb-4"><?= $title ?></h2><?php endif; ?>
										<?php if ($desc) : ?><div class="desc text-white text-lg mb-6"><?= $desc ?></div><?php endif; ?>
										<?php if ($link) : ?>
											<a href="<?= $link ?>" class="btn btn-primary"><?= $link_text ? $link_text : 'Xem thêm' ?></a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php
					endforeach;
				else :
					// Fallback static banner
					?>
					<div class="swiper-slide">
						<div class="banner-item relative h-full w-full">
							<div class="banner-img">
								<img class="lozad w-full h-full object-cover" data-src="<?= get_template_directory_uri() ?>/assets/images/banner-placeholder.jpg" alt="Banner" />
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="group-controll">
				<div class="wrap-button-slide">
					<div class="btn-prev banner-prev"><i class="fa-solid fa-chevron-left"></i></div>
					<div class="btn-next banner-next"><i class="fa-solid fa-chevron-right"></i></div>
				</div>
				<div class="swiper-pagination banner-pagination"></div>
			</div>
		</div>
	</section>

	<div class="wrapper-gap-top">
		<?php get_template_part('modules/common/breadcrumb'); ?>

		<section class="section-news-archive section-py">
			<div class="container">
				<?php if (have_posts()) : ?>
					<?php 
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					
					// Featured Post (Only on first page)
					if ($paged == 1) :
						the_post(); // Advance to first post
					?>
						<div class="featured-post">
							<div class="box-news box-news-featured">
								<div class="news-image">
									<a href="<?php the_permalink(); ?>">
										<?php if (has_post_thumbnail()) : ?>
											<?= get_image_attrachment(get_post_thumbnail_id(), 'large'); ?>
										<?php endif; ?>
									</a>
								</div>
								<div class="news-content">
									<div class="news-date">
										<div class="date-day"><?= get_the_date('d'); ?></div>
										<div class="date-month"><?= get_the_date('m.Y'); ?></div>
									</div>
									<div class="wrapper">
										<h2 class="news-title title-20">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h2>
										<div class="news-excerpt body-16">
											<?php the_excerpt(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<div class="news-grid">
						<?php while (have_posts()) : the_post(); ?>
							<div class="box-news">
								<div class="news-image">
									<a href="<?php the_permalink(); ?>">
										<?php if (has_post_thumbnail()) : ?>
											<?= get_image_attrachment(get_post_thumbnail_id(), 'medium'); ?>
										<?php endif; ?>
									</a>
								</div>
								<div class="news-content">
									<div class="news-date">
										<div class="date-day"><?= get_the_date('d'); ?></div>
										<div class="date-month"><?= get_the_date('m.Y'); ?></div>
									</div>
									<div class="wrapper">
										<h3 class="news-title title-20">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<div class="news-excerpt body-16">
											<?php the_excerpt(); ?>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>

					<div class="news-pagination">
						<?php
						echo paginate_links(array(
							'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
							'format' => '?paged=%#%',
							'current' => max(1, get_query_var('paged')),
							'total' => $wp_query->max_num_pages,
							'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
							'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
							'type' => 'list',
							'end_size' => 3,
							'mid_size' => 3
						));
						?>
					</div>

				<?php else : ?>
					<p>Chưa có bài viết nào.</p>
				<?php endif; ?>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
