<?php

namespace Simple_Post_Counter\Includes;

// exit if accessed directly
if (!defined('ABSPATH'))
    exit;

class Settings_Api
{
    // Properties
    private $pages = [];
    private $args = [];
    private $page_types = [];
    private $settings = [];

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct($args)
    {
        //assign value
        $this->args = $args;


        //actions
        add_action('admin_menu', [$this, 'admin_menu_options']);

        add_action('admin_init', [$this, 'setting_register']);
    }

    public function admin_menu_options()
    {

        $this->pages = apply_filters($this->args['prefix'] . 'page_settings', []);

        $types = [
            'page' => [],
            'subpage' => [],
            'settings_page' => []
        ];

        foreach ($this->pages as $key => $page) {

            //check
            if (!empty($page['type']) && !array_key_exists($page['type'], $types)) {
                continue;
            }

            $callback = !empty($page['callback']) ? $page['callback'] : [$this, 'options_page'];

            if ($page['type'] == 'page') {
                // add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['slug'], $callback, $page['icon'] );
                add_menu_page(
                    $page['page_title'],
                    $page['menu_title'],
                    $page['capability'],
                    $page['menu_slug'],
                    $callback,
                    $page['icon'],
                    $page['position']
                );

                // add page type
                $types['page'][$page['menu_slug']] = $key;
            }

        }

        //set the types
        $this->page_types = $types;
    }

    public function options_page()
    {

        // get current screen
        $screen = get_current_screen();
        $page = implode(' ', $this->page_types['page']);

        $valid_page = false;

        if ('toplevel_page_' . $page == $screen->base) {
            $valid_page = true;
            $page_type = 'page';
            $url_page = 'admin.php';
        }

        // skip invalid pages
        if (!$valid_page)
            return;


        ?>
        <ul class="nav-tab-wrapper">
            <?php

            // CHECK IF ANY TAB EXITS
            if (array_key_exists('tabs', $this->pages[$page])) {

                // tabs
                $tabs = $this->pages[$page]['tabs'];

                $first_tab = key($tabs);
                // print_r($first_tab);
    
                // get requested tab
                $get_tab = !empty($_GET['tab']) && array_key_exists($_GET['tab'], $tabs) ? $_GET['tab'] : $first_tab;

                foreach ($tabs as $key => $tab) {
                    $url = admin_url($url_page . '?page=' . $page . '&tab=' . $key);
                    echo '
				            <a class="nav-tab' . ($get_tab === $key ? ' nav-tab-active' : '') . (!empty($tab['class']) ? ' ' . esc_attr($tab['class']) : '') . '" href="' . ($url !== '' ? esc_url($url) : '#') . '">' . esc_html($tab['label']) . '</a>';
                }
            }
            ?>
        </ul>

        <?php
    }

    function setting_register()
    {
        $this->settings = apply_filters($this->args['prefix'] . 'settings_data', []);

        foreach( $this->settings as $setting_id => $setting ){
            if( is_array( $setting['option_name'] ) ){
                foreach( $setting['option_name'] as $tab => $option_name ){

                    // register setting for this section
                    register_setting( 
                        $option_name,
                        $option_name,
                    );

                    // register setting sections
                    if( is_array( $setting['sections'] ) && ! empty( $setting['sections'] ) ){
                        foreach( $setting['sections'] as $section_id => $section ){
                            add_settings_section(
                                $section_id,
                                ! empty( $section['title'] ) ? esc_html( $section['title'] ) : '',
                                ! empty( $section['callback'] ) ? esc_html( $section['callback'] ) : '',
                                ! empty( $section['page'] ) ? esc_html( $section['page'] ) : $option_name,                                
                            );
                        }
                    }

                    // registering setting fields
                    if( is_array( $setting['field'] ) && ! empty( $setting['field'] ) ){    
                        foreach( $setting['field'] as $field_key => $field ){
                            // field id
                            $field_id = implode( '_', [ str_replace( '-', '_', $this->args['prefix'] ), $tab, $field_key] );

                            add_settings_field(
                                $field_id,
                                ! empty( $field['title'] ) ? esc_html( $field['title'] ) : '',
                                [ $this, 'render_field' ],
                                $option_name,
                                ! empty( $field['section'] ) ? esc_html( $field['section'] ) : '',
                                ['data' => 'hello']
                            );
                        }
                    }
                }
            }
        }
    }

    public function render_field( $args ){
        echo 'test';

        exit;
    }
}