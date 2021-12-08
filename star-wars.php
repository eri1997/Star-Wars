<?php

/**
 * Plugin Name: Star Wars
 * Description: This plugin displays data from Star Wars API.
 */

use Elementor\Core\Utils\Str;

defined('ABSPATH') or die('You are not allowed to do that!');





/**
 * Adds Star_Wars_Widget widget.
 */
class Star_Wars_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'Star_Wars_widget', // Base ID
            'Star_Wars_Widget', // Name
            array('description' => __('A Star Wars Widget to display Star Wars Spaceships', 'text_domain'),)
            // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        echo $before_widget;
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }


        //WIDGET CONTENT OUTPUT .
        echo __('Starship Names : ', 'text_domain');
        //WIDGET CONTENT OUTPUT .
        echo '<p>';
        $url = 'https://swapi.dev/api/starships/';
        $request = wp_remote_get($url);
        if (is_wp_error($request)) {
            return false; //bail early
        }
        $body = wp_remote_retrieve_body($request);
        $data = json_decode($body);

        echo '<form method="post">';

        echo '<select name="selectValue">';
        while ($data->next != null) {

            //echo '<select>';
            foreach ($data->results as $result) {
                echo '<option value="' . '<br>' . 'Manufactorer:' . $result->manufacturer . '<br>' . 'Created:' . $result->created . '<br>' . 'Crew Number:' . $result->crew . '<br>' . 'Passager Number:' . $result->passengers . '<br>' . 'Cost in Credits:' . $result->cost_in_credits . '<br>' . '">' . $result->name . '</option>';
            }
            $url = $data->next;
            $request = wp_remote_get($url);
            if (is_wp_error($request)) {
                return false; //bail early
            }
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body);
        }

        foreach ($data->results as $result) {

            echo '<option value="' . '<br>' . 'Manufactorer:' . $result->manufacturer . '<br>' . 'Created:' . $result->created . '<br>' . 'Crew Number:' . $result->crew . '<br>' . 'Passager Number:' . $result->passengers . '<br>' . 'Cost in Credits:' . $result->cost_in_credits . '<br>' . '">' . $result->name . '</option>';
        }

        echo '</select>';
        echo '<br>.<br>';
        echo '<input type="submit" value="Submit the form"/>';
        echo '</form>';


        $selectOption = $_POST['selectValue'];



        echo '<p>' . $selectOption . '</p>';



        echo $after_widget;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'text_domain');
        }
?>
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
<?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }
} // class Star_Wars_Widget


// Register Star_Wars_Widget widget
add_action('widgets_init', 'register_Star_Wars');

function register_Star_Wars()
{
    register_widget('Star_Wars_Widget');
}
?>