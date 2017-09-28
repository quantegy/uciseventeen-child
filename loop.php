<?php
/**
 * Created by Chris Walsh.
 * User: walshcj
 * Date: 9/12/17
 * Time: 1:25 PM
 */
?>
<?php if ( have_posts() ): $i = 0; // @todo get a template for this ?>
    <div class="content">
		<?php while ( have_posts() ): the_post(); ?>
			<?php if ( $i === 0 ): ?>
                <div>
                    <a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'medium_large', [ 'class' => 'img-responsive' ] ); ?>
						<?php the_title( '<h2>', '</h2>' ); ?>
                    </a>
                    <p><?php the_excerpt(); ?></p>
                </div>
                <hr>
			<?php else: ?>
                <div class="media">
                    <div class="media-left">
                        <a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'medium_large', [ 'class' => 'media-object' ] ); ?>
                        </a>
                    </div>
                    <div class="media-body">
                        <time datetime=""><?php the_date(); ?></time>
                        <a href="<?php the_permalink(); ?>"><?php the_title( '<h4 class="media-heading">', '</h4>' ); ?></a>
                        <p><?php the_excerpt(); ?></p>
                    </div>
                </div>
			<?php endif; ?>
			<?php $i ++; endwhile; ?>
    </div>
<?php endif;