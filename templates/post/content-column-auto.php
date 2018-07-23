<?php 
	$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "medium_large" );
	$image_width = $image_data[1];
	$image_height = $image_data[2];
	$article_class = '';
	if ($image_width > $image_height) {
		$article_class = 'article-column-full';
	} else {
		$article_class = 'article-column-wrap';
	}
?>
<div class="content container">
    <div class="row">
        <div class="col-md-8 clearfix article <?php echo $article_class; ?>">
            <h1 class="page-heading"><?php the_title(); ?></h1>

            <h2 class="page-subheadline"><?php echo get_the_excerpt(); ?></h2>

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
                <div class="portrait-header">
                    <?php the_post_thumbnail('medium_large', array('class' => 'img-responsive')); ?>
					<?php if (get_the_post_thumbnail_caption() != ''): ?>
							<div class="caption"><?php the_post_thumbnail_caption(); ?></div>
					<?php endif; ?>
                </div>
            <?php elseif(has_post_thumbnail() && uciseventeen_has_featured_video()): ?>
	            <?php echo wp_oembed_get(uciseventeen_get_featured_video_url()); ?>
            <?php endif; ?>

            <div class="page-content"><?php the_content(); ?></div>
            <?php uciseventeen_related_posts(); ?>
        </div>
        <aside class="col-md-4 sidebar-right no-print">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</div>
