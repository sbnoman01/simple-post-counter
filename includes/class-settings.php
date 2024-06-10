<?php

namespace Simple_Post_Counter\Includes;
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class Settings {
    // Properties
    private $pages = [];
    private $args  = [];

    /**
	 * Class constructor.
	 *
	 * @return void
	 */
    public function __construct( $args ){
        //assign value
        $this->args = $args;
        //filters
        add_filter( $this->args['prefix'] . 'page_settings', [ $this, 'page_settings' ] );

    }

    public function page_settings( $pages ){

        // default page
		$pages[ $this->args['prefix'] . 'pages' ] = [
			'menu_slug'		=> 'simple-post-counter',
			'page_title'	=> __( 'Simple PostViews Counter Settings', 'simple-post-counter' ),
			'menu_title'	=> __( 'Simple Counter', 'simple-post-counter' ),
			'capability'	=>  'manage_options',
			'callback'		=> null,
			'tabs'			=> [
				'general'	 => [
					'label'			=> __( 'General', 'simple-post-counter' ),
					'option_name'	=> 'post_views_counter_settings_general'
				],
				'display'	 => [
					'label'			=> __( 'Display', 'simple-post-counter' ),
					'option_name'	=> 'post_views_counter_settings_display'
				],
				'reports'	=> [
					'label'			=> __( 'Reports', 'simple-post-counter' ),
					'option_name'	=> 'post_views_counter_settings_reports'
				],
				'other'		=> [
					'label'			=> __( 'Other', 'simple-post-counter' ),
					'option_name'	=> 'post_views_counter_settings_other'
				]
			]
		];

        $pages[ $this->args['prefix'] . 'pages' ]['type'] = 'page';
        $pages[ $this->args['prefix'] . 'pages' ]['icon'] = 'dashicons-chart-bar';
        $pages [$this->args['prefix'] . 'pages' ]['position'] = '10';

        return $pages;
    }
}
