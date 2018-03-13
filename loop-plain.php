<?php
/**
 * Created by PhpStorm.
 * User: walshcj
 * Date: 3/6/18
 * Time: 2:46 PM
 */
?>
<?php if ( have_posts() ): ?>
	<?php while ( have_posts() ): the_post(); ?>
        <div class="media">
            <div class="media-left">
                <a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'medium_large', [
						'class' => 'media-object',
						'style' => 'width: 100px;',
					] ); ?>
                </a>
            </div>
            <div class="media-body">
                <a href="<?php the_permalink(); ?>"><?php the_title( '<p class="media-heading">', '</p>' ); ?></a>
                <p>
                    <time><?php echo get_the_date( 'F j, Y' ); ?></time>
                </p>
            </div>
        </div>
	<?php endwhile; ?>
<?php endif;
