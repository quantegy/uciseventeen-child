<?php
/**
 * Created by PhpStorm.
 * User: walshcj
 * Date: 3/19/18
 * Time: 12:09 PM
 */
/**
 * Widget Name: YouTube Playlister
 * Description: Pulls a playlist from YouTube and produces HTML for widget section
 * Author: Chris Walsh, UCI
 * Author URI: http://news.uci.edu/
 */
namespace UCI\Wordpress\SiteOrigin;

class Youtube_Playlist extends \SiteOrigin_Widget {
	const BASE_API_URL = 'https://www.googleapis.com/youtube/v3/playlistItems?';

	const TEXT_DOMAIN = 'youtube-playlist';

	private $apiKey;

	function __construct( $id, $name, array $widget_options = [], array $control_options = [], array $form_options = [], $base_folder = FALSE ) {
		parent::__construct(
			'youtube-playlist',
			__('YouTube Playlist', self::TEXT_DOMAIN),
			[
				'description' => __('Pulls a playlist from YouTube and produces HTML for widget section', self::TEXT_DOMAIN),
				'help' => ''
			],
			[],
			[
				'apikey' => [
					'type' => 'text',
					'label' => __('API key', self::TEXT_DOMAIN),
					'default' => ''
				]
			],
			plugin_dir_path(__FILE__)
		);
	}

	function get_template_name( $instance ) {
		//return parent::get_template_name( $instance ); // TODO: Change the autogenerated stub
		return 'youtube-playlist-template';
	}

	function get_template_dir( $instance ) {
		//return parent::get_template_dir( $instance ); // TODO: Change the autogenerated stub
		return 'tpl';
	}

	function get_style_name( $instance ) {
		//return parent::get_style_name( $instance ); // TODO: Change the autogenerated stub
		return '';
	}

	/*function get_widget_form() {
		return [
			'apikey' => [
				'type' => 'text',
				'label' => __('API key', self::TEXT_DOMAIN),
				'default' => ''
			]
		];
	}*/
}

//siteorigin_widget_register('youtube-playlist', __FILE__, Youtube_Playlist::class);