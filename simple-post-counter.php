<?php
/*
  Plugin Name: Simple Post View Counter
  Description: Post view counter plugin 
  Version: 1.0
  Requires at least: 5.0
  Requires PHP: 5.2
  Tested up to: 6.5
  Author: sbnoman01
  Text Domain: simple-post-counter
*/

// include only file
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}



if ( ! class_exists( 'Simple_Post_Counter' ) ) {

  final class Simple_Post_Counter {

    //properties
    public $prefix = 'simple_post_counter';

    /**
		 * Disable object cloning.
		 *
		 * @return void
		 */
		public function __clone() {}


    /**
		 * Disable unserializing of the class.
		 *
		 * @return void
		 */
		public function __wakeup() {}


    /**
		 * Defining required constants
		 *
		 * @return 
		 */
    private function define_constants(){
      // PLUGIN PATH
      define( 'SIMPLE_POST_COUNTER_PATH', plugin_dir_path( __FILE__ ) );
      // PLUGIN INCLUDE
      define( 'SIMPLE_POST_COUNTER_INC', SIMPLE_POST_COUNTER_PATH . '/includes/' );
    }

    private function includes(){
      require_once( SIMPLE_POST_COUNTER_INC . 'class-settings.php');
      require_once( SIMPLE_POST_COUNTER_INC . 'class-settings-api.php');
    }

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct(){

      // load the constructs
      $this->define_constants();

      // load includes
      $this->includes();

      new \Simple_Post_Counter\Includes\Settings(
        [
          'prefix' => str_replace('_', '-', $this->prefix)
        ]
      );

      new \Simple_Post_Counter\Includes\Settings_Api(
        [
          'prefix' => str_replace('_', '-', $this->prefix)
        ]
      );

    }

    
  }
  

} new Simple_Post_Counter();


function debug( $arr ){
  echo '<pre>';

  print_r($arr);
  
  echo '</pre>';
}

add_action('admin_init', 'register_simple_post_counter_settings', 999);

function register_simple_post_counter_settings() {
    // Register a settings group
    register_setting('simple_post_counter_options_group', 'simple_post_counter_options');

    // Add a section
    add_settings_section(
        'simple_post_counter_section',
        'Simple Post Counter Settings',
        'simple_post_counter_section_callback',
        'simple-post-counter'
    );

    // Add a field
    add_settings_field(
        'post_counter_text',
        'Text Field Example',
        'post_counter_text_callback',
        'simple-post-counter',
        'simple_post_counter_section'
    );
}

function simple_post_counter_section_callback() {
    echo '<p>Configure options for Simple Post Counter.</p>';
}

function post_counter_text_callback() {
    $options = get_option('simple_post_counter_options');
    $value = isset($options['post_counter_text']) ? esc_attr($options['post_counter_text']) : '';
    echo '<input type="text" name="simple_post_counter_options[post_counter_text]" value="' . $value . '" />';
}