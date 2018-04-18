<?php
/**
 * Created by PhpStorm.
 * User: walshcj
 * Date: 4/17/18
 * Time: 5:06 PM
 */
namespace UCI\Wordpress\Customize\Identity;

class Settings {
	const PLACEHOLDER_SETTING = 'uciseventeen_placeholder_thumbnail';

	private $wpCustomize;

	/**
	 * Settings constructor.
	 *
	 * @param \WP_Customize_Control $wpCustomize
	 */
	public function __construct($wpCustomize) {
		$this->setWpCustomize($wpCustomize);

		$this->addSetting();
	}

	private function addSetting() {
		$this->getWpCustomize()->add_setting(self::PLACEHOLDER_SETTING, array('defualt' => ''));

		$this->getWpCustomize()->add_control(new \WP_Customize_Media_Control($this->getWpCustomize(), self::PLACEHOLDER_SETTING, array(
			'label' => __('Default placehodler thumbnail image', 'uciseventeen'),
			'section' => 'title_tagline',
			'description' => __('This will replace any empty featured image thumbnails with the default image.', 'uciseventeen'),
			'mime_type' => 'image'
		)));
	}

	private function setWpCustomize($wpc) {
		$this->wpCustomize = $wpc;
	}

	public function getWpCustomize() {
		return $this->wpCustomize;
	}
}