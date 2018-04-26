<?php
/**
 * Created by PhpStorm.
 * User: walshcj
 * Date: 3/6/18
 * Time: 9:40 AM
 */
?>
<?php if ( have_posts() ): $i = 0; ?>

	<?php while ( have_posts() ): the_post(); ?>
		<?php if ( $i === 0 ): ?>
            <div class="lt-big">
                <div class="thumb">
                    <a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'thumbnail_2to3', [ 'class' => 'img-responsive' ] ); ?>
                    </a>
                </div>
				<?php the_title( '<div class="title"><a href="' . get_the_permalink() . '">', '</a></div>' ); ?>
                <div class="pubdate"><?php echo get_the_date('F j, Y'); ?></div>
            </div>
		<?php else: ?>
			<?php if ( $i % 2 === 1 ): ?>
                <div class="row">
			<?php endif; ?>
            <div class="col-md-6 lt-small">
                    <div class="thumb">
                        <a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'thumbnail_2to3', [ 'class' => 'img-responsive' ] ); ?>
                        </a>
                    </div>
					<?php the_title( '<div class="title"><a href="' . get_the_permalink() . '">', '</a></div>' ) ?>
					<div class="pubdate"><?php echo get_the_date('F j, Y'); ?></div>
            </div>
			<?php if ( $i % 2 === 0 ): ?>
                </div>
			<?php endif; ?>
		<?php endif; ?>
		<?php $i ++; endwhile ?>
<?php endif;