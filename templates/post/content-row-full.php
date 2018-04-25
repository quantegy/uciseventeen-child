<div class="content article article-row-full">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2">
	            <h1 class="page-heading"><?php the_title(); ?></h1>
	            <h2 class="page-subheadline"><?php echo get_the_excerpt(); ?></h2>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
	            <div class="page-date">
	                <?php echo apply_filters('cj_authorship_authors', cj_authorship_get_author_names(get_the_ID()), array(
		                'container_start' => '',
		                'container_end' => ' | ',
		                'before_author' => '',
		                'after_author' => '',
		                'prefix' => 'by',
		                'separator' => ' '
	                )); ?>
	                <?php the_date(); ?>
	            </div>
	            <?php if(has_post_thumbnail() && !uciseventeen_has_featured_video()): ?>
	            <?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
	            <div class="caption"><?php the_post_thumbnail_caption(); ?></div>
	            <?php elseif(has_post_thumbnail() && uciseventeen_has_featured_video()): ?>
		            <?php echo wp_oembed_get(uciseventeen_get_featured_video_url()); ?>
	            <?php endif; ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2">
				<div class="page-content"><?php the_content(); ?></div>
			</div>
		</div>
		<?php uciseventeen_related_posts(); ?>
		<div class="row">
	        <aside class="col-xs-12 sidebar-bottom">
	            <?php get_sidebar(); ?>
	        </aside>
		</div>
	</div>
</div>
