<?php
if(!file_exists($file = __DIR__ . '/vendor/autoload.php')) {
    throw new Exception('please, run "composer install" in ' . __DIR__);
}
require_once 'vendor/autoload.php';

/**
 * Created by Chris Walsh.
 * User: walshcj
 * Date: 9/5/17
 * Time: 10:08 AM
 */

/**
 * metadata key for alternative featured image
 */
@define('ALT_IMG_META_KEY', '_secondary_featured_image');

@define('GTM_SCRIPT_OPTION_NAME', 'gtm_script');

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_script('match-heights', get_stylesheet_directory_uri() . '/assets/js/jquery-match-height/dist/jquery.matchHeight-min.js', ['jquery'], false, false);
    wp_enqueue_script('image-preload', get_stylesheet_directory_uri(). '/assets/js/jquery.imgpreload/dist/jquery.imgpreload.min.js', ['jquery'], false, false);
    wp_enqueue_script('uci-height-matching', get_stylesheet_directory_uri() . '/assets/js/uciseventeen/height-matching.js', ['match-heights'], false, false);

	wp_enqueue_style('uciseventeen-child', get_stylesheet_directory_uri() . '/assets/theme-styles/uciseventeen.css');
	wp_enqueue_style('bootstrap3-uci', get_stylesheet_directory_uri() . '/assets/theme-styles/Bootstrap3-UCI-theme/css/bootstrap3-uci.css');
	wp_enqueue_style('bootstrap3-accessibility', get_stylesheet_directory_uri() . '/assets/theme-styles/Bootstrap3-UCI-theme/css/bootstrap3-uci-accessibility/bootstrap3-uci-accessibility.css');
	wp_enqueue_style('main-style', get_stylesheet_directory_uri() . '/style.css');
}

add_action('wp_print_styles', 'uciseventeen_print_styles');
function uciseventeen_print_styles() {
    wp_dequeue_style('bootstrap-uci');
    wp_deregister_style('bootstrap-uci');

    /**
     * if Sharify plugin is active remove it's queued CSS files
     */
    if(is_plugin_active('sharify/sharify.php')) {
	    wp_dequeue_style( 'sharify' );
	    wp_deregister_style( 'sharify' );
	    wp_dequeue_style( 'sharify-icon' );
	    wp_deregister_style( 'sharify-icon' );
    }
}

/**
 * add support for thumbnails
 */
//add_theme_support('post-thumbnails');

/**
 * include our custom sitorigin pagebuilder widgets
 * if the plugin is available
 */
/*include_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'so-widgets-bundle/so-widgets-bundle.php' ) ) {
	require_once 'widgets/parallax-headline.php';
}*/

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
add_filter('cjtl_widget_title', function ($title, $instance, $args) {
	return '<h3 class="widget-title">' . $instance['title'] . '</h3>';
}, 10, 3);

/**
 * default wrappers in widgets need to handled thus,
 * because they do not have their own custom filters
 */
add_action('dynamic_sidebar_before', function($id) {
    global $wp_registered_sidebars;

    $wp_registered_sidebars[$id]['before_title'] = str_replace('<h4', '<h3', $wp_registered_sidebars[$id]['before_title']);
	$wp_registered_sidebars[$id]['after_title'] = str_replace('h4>', 'h3>', $wp_registered_sidebars[$id]['after_title']);
});

add_action('widgets_init', 'uciseventeen_child_widgets_init');
function uciseventeen_child_widgets_init() {
    if(is_plugin_active('simple-fields/simple_fields.php') || is_plugin_active_for_network('simple-fields/simple_fields.php')) {
        register_widget(\UCI\Wordpress\Widget\News::class);
    }
}

add_filter('simple_fields_contacts_filter', 'testing', 9, 3);
function testing($contacts, $args, $instance) {
    if(!empty($contacts)) {
        echo '<div class="widget">';
	    if(!empty($instance['title'])) {
		    echo '<h3 class="widget-title">' . $instance['title'] . '</h3>';
	    }

	    echo '<ul class="list-group">';

        foreach ($contacts as $contact) {
            echo '<li class="list-group-item">';
            echo '<div>' . $contact['contact_fullname'] . '</div>';
            echo '<div>' . $contact['contact_phone'] . '</div>';
            if(!empty($contact['contact_email'])) {
	            echo '<div>' . $contact['contact_email'] . '</div>';
            }
            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';
    }
}

function uciseventeen_excerpt_more($more) {
    global $post;

    return '&nbsp';
}
add_filter('excerpt_more', 'uciseventeen_excerpt_more');

add_filter('post_thumbnail_html', function($html, $post_id, $post_thumbnail_id, $size, $attr) {
	/**
	 * if no featured image use placeholder image for non post pages
	 */
    if(empty($post_thumbnail_id)) {
        $imgId = get_theme_mod(\UCI\Wordpress\Customize\Identity\Settings::PLACEHOLDER_SETTING, '');

        $img = wp_get_attachment_image($imgId, $size, false, $attr);
        $img = uciseventeen_cleanup_image_element($img);

        return $img;
    }

    $alt_img = uciseventeen_alt_image($html, $post_id, $post_thumbnail_id, $size, $attr);
    if($alt_img !== false) {
        if(!is_single($post_id) && $size !== 'full') {
	        $html = $alt_img;
        }
    }

    return $html;
}, 99, 5);

/**
 * remove current post from recent posts
 */
add_action('pre_get_posts', function($query) {
    if(!is_admin() && is_singular('post')) {
        $exclude = get_the_ID();
        $query->set('post__not_in', array($exclude));
    }
});

function uciseventeen_alt_image($html, $post_id, $post_thumbnail_id, $size, $attr){
	$alt_img_id = get_post_meta($post_id, ALT_IMG_META_KEY, true);
	//$alt_img_url = wp_get_attachment_url($alt_img_id);
	//$alt_img_thumb_url = wp_get_attachment_thumb_url($alt_img_id);

	/**
	 * if media ID is not empty let's use the alt image instead of featured
	 */
	if(!empty($alt_img_id)) {
		$img_tag = wp_get_attachment_image($alt_img_id, $size, false, $attr);

        $html = uciseventeen_cleanup_image_element($img_tag);
	} else {
	    return false;
    }

	return $html;
}

/**
 * Remove width and height attributes from Wordpress generated img tags
 * @param string $img_tag HTML output
 *
 * @return string
 */
function uciseventeen_cleanup_image_element($img_tag) {
	/**
	 * need to get rid of WP's hard-coded width and height attributes FFS
	 */
	$dom = new DOMDocument();
	$dom->formatOutput = true;
	$dom->loadHTML($img_tag);

	try {
		$imgEle = $dom->getElementsByTagName( 'img' )->item( 0 );
		$imgEle->removeAttribute( 'width' );
		$imgEle->removeAttribute( 'height' );
	} catch(Exception $e) {

    }

	$html = $dom->saveHTML();

	return $html;
}

/**
 * @todo filter out all default WP image attributes (creating styling issues on thumbnails)
 */
add_filter('wp_get_attachment_image_attributes', function($attr) {
    if(isset($attr['srcset'])) {
        unset($attr['srcset']);
    }

    if(isset($attr['sizes'])) {
        unset($attr['sizes']);
    }

    return $attr;
});

/**
 * add secondary featured image for listing thumbnails
 */
add_action('add_meta_boxes', 'uciseventeen_alt_image_metabox');
function uciseventeen_alt_image_metabox() {
    add_meta_box('secondaryimgdiv', __('Secondary Featured Image', 'uciseventeen'), 'uciseventeen_secondary_image_metabox', 'post', 'side', 'low');
}

function uciseventeen_secondary_image_metabox($post) {
    global $content_width, $_wp_additional_image_sizes;

    $image_id = get_post_meta($post->ID, '_secondary_featured_image', true);

    $old_content_width = $content_width;
    $content_width = 254;

	$content = '<p class="howto">Used for thumbnails on listings, or category pages.</p>';

    if($image_id && get_post($image_id)) {
        if(!isset($_wp_additional_image_sizes['post-thumbnail'])) {
            $thumbnail_html = wp_get_attachment_image($image_id, [$content_width, $content_width]);
        } else {
            $thumbnail_html = wp_get_attachment_image($image_id, 'post-thumbnail');
        }

        if(!empty($thumbnail_html)) {
            $content .= $thumbnail_html;
            $content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_secondary_image_button">' . esc_html__('Remove secondary image', 'uciseventeen') . '</a></p>';
            $content .= '<input type="hidden" id="upload_secondary_image" name="' . ALT_IMG_META_KEY . '" value="' . esc_attr($image_id) . '">';
        }

	    $content_width = $old_content_width;
    } else {
	    $content .= '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
	    $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Secondary featured image', 'uciseventeen' ) . '" href="javascript:;" id="upload_secondary_image_button" id="secondary-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'uciseventeen' ) . '" data-uploader_button_text="' . esc_attr__( 'Set secondary featured image', 'uciseventeen' ) . '">' . esc_html__( 'Set secondary featured image', 'uciseventeen' ) . '</a></p>';
	    $content .= '<input type="hidden" id="upload_secondary_image" name="' . ALT_IMG_META_KEY . '" value="" />';
    }

    echo $content;
}

add_action('save_post', 'uciseventeen_save_secondary_image', 10, 1);
function uciseventeen_save_secondary_image($post_id) {
    if(isset($_POST['_secondary_featured_image'])) {
        $image_id = (int) $_POST['_secondary_featured_image'];

        update_post_meta($post_id, '_secondary_featured_image', $image_id);
    }
}

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('secondary_featured_image', get_stylesheet_directory_uri() . '/assets/js/uciseventeen/secondary_featured_image.js', ['jquery']);
});

/**
 * add Google Tag manager support to wp_head() output
 */
add_action('wp_head', function() {
    $value = get_option(GTM_SCRIPT_OPTION_NAME);
    if (!empty($value)) {
	    ?>
        <script><?php echo $value; ?></script>
	    <?php
    }
});

/**
 * register GTM setting
 */
add_filter('admin_init', function() {
    register_setting('general', GTM_SCRIPT_OPTION_NAME);
    add_settings_field(GTM_SCRIPT_OPTION_NAME, '<label for="' . GTM_SCRIPT_OPTION_NAME . '">' . __('Google Tag Manager Script', 'uciseventeen') . '</label>', function() {
        $value = get_option(GTM_SCRIPT_OPTION_NAME);

        echo '<textarea style="width: 70%; height: 200px;" id="' . GTM_SCRIPT_OPTION_NAME . '" name="' . GTM_SCRIPT_OPTION_NAME . '">' . $value . '</textarea>';
    }, 'general');
});

/**
 * custom placeholder thumbnail for empty featured images
 */
add_action('customize_register', function($wp_customize) {
    $pis = new \UCI\Wordpress\Customize\Identity\Settings($wp_customize);
});

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