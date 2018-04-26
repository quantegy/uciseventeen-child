<?php
/**
 * Created by Chris Walsh.
 * User: walshcj
 * Date: 9/12/17
 * Time: 1:25 PM
 */
?>
<?php if(have_posts()): $i = 0; ?>
	<?php while(have_posts()): the_post(); ?>
		<?php if($i === 0): ?>
            <div data-mh="loop-lg-match" class="loop-lg">
                <div>
                    <a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail('thumbnail_2to3', ['class' => 'img-responsive', 'data-mh' => 'loop-lg-img']); ?>
						<?php the_title('<div class="title">', '</div>'); ?>
                    </a>
                </div>
                <div class="excerpt"><?php echo get_the_excerpt(); ?></div>
                <div class="pubdate"><?php echo get_the_date('F j, Y'); ?></div>
            </div>
		<?php else: ?>
            <div class="media">
                <div class="media-left">
                    <a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail('thumbnail_2to3', ['class' => 'media-object loop-sm-img']); ?>
                    </a>
                </div>
                <div class="media-body">
                    <a href="<?php the_permalink(); ?>"><?php the_title('<p class="media-heading">', '</p>'); ?></a>
                    <p><time><?php echo get_the_date('F j, Y'); ?></time></p>
                </div>
            </div>
		<?php endif; ?>
		<?php $i++; endwhile; ?>
        <?php if(is_archive() || is_category()): ?>
		<?php
        // get category title for link label
		$catID = get_query_var('cat');
		$catName = get_category($catID)->name;
		?>
        <div class="category-more">
            <a href="<?php echo get_category_link($catID); ?>">View more <?php echo $catName; ?> <i aria-hidden="true" class="fa fa-angle-right"></i></a>
        </div>
        <?php endif; ?>
<?php endif;