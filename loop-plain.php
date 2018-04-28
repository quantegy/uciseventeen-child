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
					<?php the_post_thumbnail( 'thumbnail_3to2', [
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
	<?php if(is_archive() || is_category()): ?>
		<?php
		// get category title for link label
		$catID = get_query_var('cat');
		$catName = get_category($catID)->name;
		?>
        <div class="category-more">
            <a href="<?php echo get_category_link($catID); ?>">View more <?php echo $catName; ?></a>
        </div>
	<?php endif; ?>
<?php endif;
