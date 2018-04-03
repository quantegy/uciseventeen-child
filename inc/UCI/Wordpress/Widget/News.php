<?php
/**
 * Created by Chris Walsh.
 * User: walshcj
 * Date: 9/20/17
 * Time: 4:57 PM
 */
namespace UCI\Wordpress\Widget;

class News extends \WP_Widget {
	const NAME = 'wordpress_uci_news_widget';
	const TITLE = 'Simple Fields Widget';
	const CONTACT_INFO_FIELD = 'contact_info_group';
	const AUTHOR_INFO_FIELD = 'author_info_group';
	const AUTHOR_FULLNAME_FIELD = 'author_info_fullname';
	const AUTHOR_TITLE_FIELD = 'author_info_title';
	const CONTACT_FULLNAME_FIELD = 'contact_fullname';
	const CONTACT_PHONE_FIELD = 'contact_phone';
	const CONTACT_EMAIL_FIELD = 'contact_email';

	static $instance;

	public function __construct() {
		parent::__construct(self::NAME, self::TITLE);

		self::$instance = $this;

		add_filter('simple_fields_contacts_filter', array(self::class, 'contactsFilter'), 10, 3);
		add_filter('simple_fields_authors_filter', array(self::class, 'authorsFilter'), 10, 3);
	}

	public static function getInstance() {
	    return self::$instance;
    }

	public function widget( $args, $instance ) {
		$contacts = simple_fields_fieldgroup(self::CONTACT_INFO_FIELD);
		$authors = simple_fields_fieldgroup(self::AUTHOR_INFO_FIELD);

		if(is_single()) {
			$this->outputAuthor($args, $instance, $authors);
			$this->outputContact($args, $instance, $contacts);
		}
	}

	public function authorFilter($authors, $args, $instance) {
		$widgetTitle = apply_filters('widget_title', empty($instance['title']) ? __('Story by') : $instance['title'], $instance, self::getInstance()->id_base);
		$containerOpen = (empty($args['container_open'])) ? '<ul>' : $args['container_open'];
		$containerClose = (empty($args['container_close'])) ? '</ul>' : $args['container_close'];
		$itemOpen = (empty($args['item_open'])) ? '<li>' : $args['item_open'];
		$itemClose = (empty($args['item_close'])) ? '</li>' : $args['item_close'];

		$html = '';

		if(!empty($authors)) {
			$html .= $args['before_widget'];

			$html .= $args['before_title'] . $widgetTitle . $args['after_title'];

			$html .= $containerOpen;

			foreach ($authors as $author) {
				$html .= $itemOpen . $author[self::AUTHOR_FULLNAME_FIELD];

				if(!empty($author[self::AUTHOR_TITLE_FIELD])) {
					$html .=  ', ' . $author[self::AUTHOR_TITLE_FIELD];
				}

				$html .= $itemClose;
			}

			$html .= $containerClose;

			$html .= $args['after_widget'];
		}

		return $html;
    }

    public function contactsFilter($contacts, $args, $instance) {
	    $widgetTitle = apply_filters('widget_title', empty($instance['title']) ? __('Contacts') : $instance['title'], $instance, self::getInstance()->id_base);
	    $containerOpen = (empty($args['container_open'])) ? '<ul>' : $args['container_open'];
	    $containerClose = (empty($args['container_close'])) ? '</ul>' : $args['container_close'];
	    $itemOpen = (empty($args['item_open'])) ? '<li>' : $args['item_open'];
	    $itemClose = (empty($args['item_close'])) ? '</li>' : $args['item_close'];
	    $fieldOpen = (empty($args['field_open'])) ? '<div>' : $args['field_open'];
	    $fieldClose = (empty($args['field_close'])) ? '</div>' : $args['field_close'];

	    $html = '';

	    if(!empty($contacts)) {
		    $html .= $args['before_widget'];

		    $html .= $args['before_title'] . $widgetTitle . $args['after_title'];

		    $html .= $containerOpen;

		    foreach ($contacts as $contact) {
			    $html .= $itemOpen . $fieldOpen . $contact[self::CONTACT_FULLNAME_FIELD] . $fieldClose;
			    $html .= $fieldOpen . $contact[self::CONTACT_PHONE_FIELD] . $fieldClose;

			    if(!empty($contact[self::CONTACT_EMAIL_FIELD])) {
				    $html .= $fieldOpen . $contact[self::CONTACT_EMAIL_FIELD] . $fieldClose;
			    }

			    $html .= $itemClose;
		    }

		    $html .= $containerClose;

		    $html .= $args['after_widget'];
	    }

	    return $html;
    }

	private function outputAuthor($args, $instance, $authors) {
		echo apply_filters('simple_fields_authors_filter', $authors, $args, $instance);
	}

	private function outputContact($args, $instance, $contacts) {
        echo apply_filters('simple_fields_contacts_filter', $contacts, $args, $instance);
	}

	public function form( $instance ) {


		$title = $instance['title'];

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}
}