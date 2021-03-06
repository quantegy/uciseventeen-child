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

	// campaign button
    wp_enqueue_script('uci-campaign', 'https://uci.edu/js/campaign-button/campaign-button.js', [], false, true);
    wp_enqueue_style('uci-campaign-style', 'https://uci.edu/js/campaign-button/campaign-button.css');
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

        if(!empty($imgId)) {
	        $img = wp_get_attachment_image( $imgId, $size, FALSE, $attr );
	        $img = uciseventeen_cleanup_image_element( $img );

	        return $img;
        } else {
            /*
             * if no placeholder image is supplied do not output anything
             */
	        return '';
        }
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
	} catch(Exception $e) {}

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

/**
 * Add related posts to bottom of posts
 */
function uciseventeen_related_posts($limit = 4, $orderby = 'rand') {
    echo apply_filters('uciseventeen_related_posts_filter', $limit, $orderby, [
            'before_posts' => '<div class="related-posts">',
            'after_posts' => '</div>',
            'before_item' => '<div class="media">',
            'after_item' => '</div>',
            'before_label' => '<h2>',
            'after_label' => '</h2>',
            'before_title' => '<p class="media-heading">',
            'after_title' => '</p>',
            'before_date' => '<p><time>',
            'after_time' => '</time></p>'
    ]);
}

add_filter('uciseventeen_related_posts_filter', 'uciseventeen_related_posts_handler', 10, 3);
function uciseventeen_related_posts_handler($limit = 5, $orderby = 'rand', $args = []) {
    $btps = isset($args['before_posts']) ? $args['before_posts'] : '<div>';
    $atps = isset($args['after_posts']) ? $args['after_posts'] : '</div>';
    $bti = isset($args['before_item']) ? $args['before_item'] : '<div>';
	$ati = isset($args['after_item']) ? $args['after_item'] : '</div>';
	$label = isset($args['label']) ? $args['label'] : __('Related Posts', 'uciseventeen');
	$btl = isset($args['before_label']) ? $args['before_label'] : '<h3>';
	$atl = isset($args['after_label']) ? $args['after_label'] : '</h3>';
	$btt = isset($args['before_title']) ? $args['before_title'] : '';
	$att = isset($args['after_title']) ? $args['after_title'] : '';
	$btd = isset($args['before_date']) ? $args['before_date'] : '';
	$atd = isset($args['after_date']) ? $args['after_date'] : '';

    $html = '';

    $postId = get_queried_object_id();

    $tags = wp_get_post_terms($postId, 'post_tag', ['fields' => 'ids']);

    $queryArgs = [
            'post__not_in' => array($postId),
            'posts_per_page' => $limit,
            'ignore_sticky_posts' => 1,
            'orderby' => $orderby,
            'tag__in' => $tags,
            'caller_get_posts' => 1
    ];

    $query = new WP_Query($queryArgs);
    if($query->have_posts()) {
	    $html .= $btps;
        $html .= $btl . $label . $atl;
        while($query->have_posts()) {
            $query->the_post();

            $html .= $bti;
            $html .= '<div class="media-left"><a href="' . get_the_permalink() . '">' . get_the_post_thumbnail(null,'thumbnail', ['class' => 'img-responsive']) . '</a></div>';
            $html .= '<div class="media-body"><a href="' . get_the_permalink() . '">' . the_title($btt, $att, false) . '</a>';
            $html .= the_date(null, $btd, $atd, false) . '</div>';
            $html .= $ati;
        }
        $html .= $atps;
    }

    wp_reset_query();


    return $html;
}

/**
 * Removing unwanted widgets from SiteOrigin
 * @param $widgets
 *
 * @return mixed
 */
function uciseventeen_remove_unwanted_widgets($widgets) {
    unset($widgets['SiteOrigin_Widget_PostCarousel_Widget']);

    return $widgets;
}
add_filter('siteorigin_panels_widgets', 'uciseventeen_remove_unwanted_widgets', 11);

/**
 * adding crop thumbnail plugin to handle 2:3 aspect ratio,
 * so i'm removing all unwanted image sizes from other widgets/plugins
 */
add_action('init', function() {
	remove_image_size('sow-carousel-default');
}, 11);

add_action('init', 'uciseventeen_thumbnail_init');
function uciseventeen_thumbnail_init() {
	if(is_plugin_active('crop-thumbnails/crop-thumbnails.php')) {
		// 3:2 aspect ratio
		add_image_size('thumbnail_3to2', 720, 480, true);
	}
}

/**
 * replacing the default featured image with 3to2 ratio
 * because social media platforms cannot process portrait dimensions
 */
add_filter('fb_og_image', 'uci_fb_og_image');
function uci_fb_og_image($i) {
    $i = get_the_post_thumbnail_url(null, 'thumbnail_3to2');

    return $i;
}

function debug($d) {
    print '<pre>';
    var_dump($d);
    print '</pre>';
}

function uciseventeen_rest_mobile_posts(WP_REST_Request $req) {
    /*$postIds = $req->get_param('post_ids');
    $postIds = explode(',', $postIds);*/

    //$posts = get_posts(array( 'post__in' => $postIds ));
    $posts = get_posts(array(
        'meta_key' => 'mobile-featured',
        'meta_value' => 1
    ));
    array_walk($posts, 'formatPostJSON');

    return new WP_REST_Response($posts, 200);
}

function uciseventeen_rest_mobile_search(WP_REST_Request $req) {
    $posts = get_posts([
            's' => $req->get_param('query'),
            'nopaging' => false,
            'posts_per_page' => 20
    ]);

	array_walk($posts, 'formatPostJSON');

	return new WP_REST_Response($posts, 200);
}

function uciseventeen_rest_mobile_geo(WP_REST_Request $r) {
    $posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'meta_key' => 'mobile-lat-long',
            'meta_value' => '',
            'meta_compare' => '!='
    ]);

    array_walk($posts, 'formatPostJSON');

    return new WP_REST_Response($posts, 200);
}

add_action('rest_api_init', function() {
	register_rest_route('uci/v1', '/posts/mobile/geo', [
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'uciseventeen_rest_mobile_geo'
	]);

    register_rest_route('uci/v1', '/posts/mobile', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'uciseventeen_rest_mobile_posts'
    ));

    register_rest_route('uci/v1', "/posts/mobile/search/(?P<query>.+)", array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'uciseventeen_rest_mobile_search'
    ));
});

function formatPostJSON(&$item) {
	$item->post_content = apply_filters('the_content', $item->post_content);
	$item->thumbnail = get_the_post_thumbnail_url($item->ID, 'thumbnail_3to2');
	$item->coordinates = get_post_meta($item->ID, 'mobile-lat-long', true);
}

function is_siteorigin_content() {
	if(is_plugin_active('siteorigin-panels/siteorigin-panels.php') && !empty(siteorigin_panels_render())) {
		return true;
	}

	return false;
}

/**
 * WP Multisite does not allow non-admin users to embed
 * unfiltered HTML. We are making an exception for Editor role.
 *
 * @param $caps
 * @param $cap
 * @param $user_id
 *
 * @return array
 */
/*function uciseventeen_unfiltered_html_for_editors($caps, $cap, $user_id) {
    if($cap === 'unfiltered_html' && user_can($user_id, 'editor')) {
        $caps = array('unfiltered_html');
    }

    return $caps;

}
add_filter('map_meta_cap', 'uciseventeen_unfiltered_html_for_editors', 1, 3);*/

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
