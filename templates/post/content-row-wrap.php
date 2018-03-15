<main class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 clearfix article article-row-wrap">
                <h1 class="page-heading"><?php the_title(); ?></h1>

                <h2 class="page-subheadline"><?php echo get_the_excerpt(); ?></h2>

	            <?php if(has_post_thumbnail() && !uciseventeen_has_featured_video()): ?>
                <div class="portrait-header">
                    <?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
                    <div class="caption"><?php the_post_thumbnail_caption(); ?></div>
                </div>
	            <?php elseif(has_post_thumbnail() && uciseventeen_has_featured_video()): ?>
		            <?php echo wp_oembed_get(uciseventeen_get_featured_video_url()); ?>
                <?php endif; ?>

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

                <p><?php the_content(); ?></p>
            </div>
            <aside class="col-md-12">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</main>