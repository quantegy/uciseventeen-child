<?php
/**
 * Created by Chris Walsh.
 * User: walshcj
 * Date: 03/15/18
 * Time: 11:47 PM
 */
$classes = ['img-responsive'];
if(uciseventeen_has_featured_video()) {
	$classes[] = 'featured-video';
}
?>
<div class="row">
	<?php if(has_post_thumbnail()): ?>
	<div class="col-md-4">
		<a href="<?php the_permalink(); ?>">
			<?php
			the_post_thumbnail('large', array('class' => implode(' ', $classes)));
			?>
		</a>
	</div>
	<?php endif; ?>
	<div class="col-md-8">
		<div class="post-meta post-title">
			<a href="<?php the_permalink(); ?>"><?php the_title('<h2>', '</h2>'); ?></a>
		</div>
        <div class="post-meta post-excerpt"><?php echo get_the_excerpt(); ?></div>
		<?php the_date('F j, Y', '<div class="post-meta post-date">', '</div>'); ?>
	</div>
</div>
