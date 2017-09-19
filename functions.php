<?php
/**
 * Created by Chris Walsh.
 * User: walshcj
 * Date: 9/5/17
 * Time: 10:08 AM
 */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	wp_enqueue_style('uciseventeen-child', get_stylesheet_directory_uri() . '/style.css');
}

/**
 * add support for thumbnails
 */
//add_theme_support('post-thumbnails');

/**
 * include our custom sitorigin pagebuilder widgets
 * if the plugin is available
 */
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'so-widgets-bundle/so-widgets-bundle.php' ) ) {
	require_once 'widgets/parallax-headline.php';
}

/**
 * let user know that there is a pagebuilder dependency (optional)
 */
function uciseventeen_so_notices() {
	if ( ! is_plugin_active( 'so-widgets-bundle/so-widgets-bundle.php' ) /**||
	 * !is_plugin_active_for_network('so-widgets-bundle/so-widgets-bundle.php')*/
	) {
		?>
        <div class="update-nag notice">
            <p>
				<?php _e( 'Please, install SiteOrigin Widgets Bundle!', 'uciseventeen' ); ?>
                <a href="https://siteorigin.com/widgets-bundle/">Available
                    here.</a>
            </p>
        </div>
		<?php
	}

	if ( ! is_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) /**||
	 * !is_plugin_active_for_network('siteorigin-panels/siteorigin-panels.php')*/
	) {
		?>
        <div class="error notice">
            <p>
				<?php _e( 'SiteOrigin Page Builder is a preferred plugin. Install it!' ); ?>
                <a href="https://siteorigin.com/page-builder/">Available
                    here.</a>
            </p>
        </div>
		<?php
	}
}

add_action( 'admin_notices', 'uciseventeen_so_notices' );

/**
 * Set folders for SiteOrigin templates
 *
 * @param $folders
 *
 * @return array
 */
function uciseventeen_so_widgets_widget_folders( $folders ) {
	$folders[] = get_stylesheet_directory() . '/widgets/';

	return $folders;
}
add_filter( 'siteorigin_widgets_widget_folders', 'uciseventeen_so_widgets_widget_folders' );

/**
 * override default widget title for tag list
 */
add_filter('cjtl_widget_title', function($title, $instance, $args) {
    return '<h4 class="widget-title">' . $title . '</h4>';
}, 9, 3);

/*function uciseventeen_so_before_content($stuff) {
    return $stuff;
}
add_filter('siteorigin_panels_before_content', 'uciseventeen_so_before_content');

function uciseventeen_so_after_content($stuff) {
    return $stuff;
}
add_filter('siteorigin_panels_after_content', 'uciseventeen_so_after_content');

function uciseventeen_so_before_row($stuff) {
    return $stuff;
}
add_filter('siteorigin_panels_before_row', 'uciseventeen_so_before_row');

function uciseventeen_so_after_row($stuff) {
    return $stuff;
}
add_filter('siteorigin_panels_after_row', 'uciseventeen_so_after_row');

function uciseventeen_so_row_cell_attributes($attributes, $grid) {

}
add_filter('siteorigin_panels_row_cell_attributes', 'uciseventeen_so_row_cell_attributes', 10, 2);*/