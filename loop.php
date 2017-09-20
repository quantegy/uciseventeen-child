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
                <div class="row">
                    <div class="col-lg-12">
                        <a href="<?php the_permalink(); ?>">
						    <?php the_post_thumbnail( 'medium_large', [ 'class' => 'img-responsive' ] ); ?>
                        </a>
                        <div class="caption">
                            <time datetime=""><?php the_date(); ?></time>
                            <a href="<?php the_permalink(); ?>"><?php the_title( '<h4>', '</h4>' ); ?></a>
                            <p><?php the_excerpt(); ?></p>
                        </div>
                    </div>
                </div>
			<?php else: ?>
                <div class="row">
                    <div class="col-lg-4 col-md-5">
                        <a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'medium_large', [ 'class' => 'img-responsive' ] ); ?>
                        </a>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <time datetime=""><?php the_date(); ?></time>
                        <a href="<?php the_permalink(); ?>"><?php the_title( '<h4 class="media-heading">', '</h4>' ); ?></a>
                        <p><?php the_excerpt(); ?></p>
                    </div>
                </div>
			<?php endif; ?>
			<?php $i ++; endwhile; ?>
    </div>
<?php endif;