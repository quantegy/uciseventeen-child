<?php
/**
 * Created by Chris Walsh.
 * User: walshcj
 * Date: 3/20/18
 * Time: 09:57 AM
 */
get_header();
?>
    <div class="container category">
        <div class="row">
            <div class="col-md-8">
				<?php if ( have_posts() ): ?>
                    <div>
						<?php the_archive_title( '<h1>', '</h1>' ); ?>
						<?php while ( have_posts() ): the_post(); ?>
							<?php get_template_part( 'templates/archive/content', get_post_format() ); ?>
						<?php endwhile; ?>
                    </div>
                    <div class="pagination"><?php wp_bootstrap_pagination(); ?></div>
				<?php else: ?>
                    <div>No stories found.</div>
				<?php endif; ?>
            </div>
            <aside class="col-md-4">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
<?php
get_footer();
