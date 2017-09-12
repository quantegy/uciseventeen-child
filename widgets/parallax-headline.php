<?php
/**
 * Created by PhpSChris Walsh.
 * User: walshcj
 * Date: 4/6/17
 * Time: 5:00 PM
 *
 * Widget Name: UCI Parallax Headline Widget
 * Description: Adds a headline to a parallax instances
 * Author: Chris Walsh
 * Author URI: http://sites.uci.edu/cwalsh/
 */
namespace UCI\Wordpress\SiteOrigin;

class Parallax_Headline extends \SiteOrigin_Widget {
	function __construct()
	{
		parent::__construct('parallax-headline-widget', __('UCI Parallax Headline Widget', 'parallax-headline'), array(
			'description' => __('Adds a headline to a parallax instances', 'parallax-headline'),
			'help' => 'n/a'
		), array(

		), array(
			'headline' => array(
				'type' => 'text',
				'label' => __('Headline', 'parallax-headline'),
				'default' => 'Your headline here!'
			),
			'subheadline' => array(
				'type' => 'section',
				'label' => __('Sub-headline', 'parallax-headline'),
				'fields' => array(
					'label' => array(
						'type' => 'text',
						'label' => __('Label', 'parallax-headline'),
						'default' => 'Your sub-headline here'
					),
					'url' => array(
						'type' => 'link',
						'label' => __('URL', 'parallax-headline')
					)
				)
			)
		), plugin_dir_path(__FILE__));
	}

	function get_template_name($instance)
	{
		return 'parallax-headline-template';
	}

	function get_template_dir($instance)
	{
		return 'templates';
	}

	function get_style_name($instance)
	{
		return 'parallax-headline-styles';
	}
}

//siteorigin_widget_register('parallax-headline-widget', __FILE__, \UCI\Wordpress\SiteOrigin\Parallax_Headline::class);