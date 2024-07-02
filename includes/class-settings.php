<?php

namespace Simple_Post_Counter\Includes;

// exit if accessed directly
if (!defined('ABSPATH'))
	exit;

class Settings
{
	// Properties
	private $pages = [];
	private $args = [];

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct($args)
	{
		//assign value
		$this->args = $args;
		//filters
		add_filter($this->args['prefix'] . 'page_settings', [$this, 'page_settings']);
		add_filter($this->args['prefix'] . 'settings_data', [$this, 'settings_data']);

	}

	public function page_settings($pages)
	{

		// default page
		$pages[$this->args['prefix']] = [
			'menu_slug' => 'simple-post-counter',
			'page_title' => __('Simple PostViews Counter Settings', 'simple-post-counter'),
			'menu_title' => __('Simple Counter', 'simple-post-counter'),
			'capability' => 'manage_options',
			'callback' => null,
			'tabs' => [
				'general' => [
					'label' => __('General', 'simple-post-counter'),
					'option_name' => 'simple_views_counter_settings_general'
				],
				'display' => [
					'label' => __('Display', 'simple-post-counter'),
					'option_name' => 'simple_views_counter_settings_display'
				],
				'reports' => [
					'label' => __('Reports', 'simple-post-counter'),
					'option_name' => 'simple_views_counter_settings_reports'
				],
				'other' => [
					'label' => __('Other', 'simple-post-counter'),
					'option_name' => 'simple_views_counter_settings_other'
				]
			]
		];

		$pages[$this->args['prefix']]['type'] = 'page';
		$pages[$this->args['prefix']]['icon'] = 'dashicons-chart-bar';
		$pages[$this->args['prefix']]['position'] = '10';

		return $pages;
	}


	public function settings_data($settings)
	{

		$settings[$this->args['prefix']] = [
			'label' => 'Simple Post Counter',
			'option_name' => [
				'general' => 'simple_views_counter_settings_general',
				'display' => 'simple_views_counter_settings_display',
				'reports' => 'simple_views_counter_settings_reports',
				'other' => 'simple_views_counter_settings_other'
			],
			'sections' => [
				'simple_post_counter_general_setting' => [
					'tab' => 'General'
				],
				'simple_post_counter_display_setting' => [
					'tab' => 'Display'
				],
				'simple_post_counter_reports_setting' => [
					'tab' => 'Reports'
				],
				'simple_post_counter_other_setting' => [
					'tab' => 'Other'
				]
			],
			'field' => [
				'post_types_count' => [
					'tab'    	 => 'General',
					'title'		 => __( 'Post Types Count', 'post-views-counter' ),
					'section' 	 => 'simple_post_counter_general_setting',
					'options' => [	
						'option_key'  => 'option value',
						'option_key2' => 'option value 2'
						]
				],
				'label' => [
					'tab'			=> 'General',
					'title'			=> __( 'Views Label', 'post-views-counter' ),
					'section'		=> 'post_views_counter_display_settings',
					'type'			=> 'input',
					'description'	=> __( 'Enter the label for the post views counter field.', 'post-views-counter' ),
					'subclass'		=> 'regular-text',
					'validate'		=> [ $this, 'validate_label' ],
					'reset'			=> [ $this, 'reset_label' ]
				],
			]
		];


		return $settings;
	}
}
