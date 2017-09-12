<?php
/**
 * Created by PhpStorm.
 * User: walshcj
 * Date: 9/6/17
 * Time: 4:48 PM
 */
get_header();
?>
<?php while ( have_posts() ): the_post(); ?>
	<?php if ( is_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) ): ?>
		<?php if ( empty( siteorigin_panels_render() ) ): // pagebuilder is not being used, fallback to default post format ?>
			<?php get_template_part( 'templates/primary-content' ); ?>
		<?php else: // use pagebuilder content ?>
            <div class="pb-container">
				<?php the_content(); ?>
            </div>
		<?php endif; ?>
	<?php else: // fallback to default post format ?>
		<?php get_template_part( 'templates/primary-content' ); ?>
	<?php endif; ?>
<?php endwhile; ?>
<?php
get_footer();
