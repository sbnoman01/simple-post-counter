<?php

namespace Simple_Post_Counter\Includes;
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class Settings_Api {
    // Properties
    private $pages = [];
    private $args  = [];
    private $page_types = [];

    /**
	 * Class constructor.
	 *
	 * @return void
	 */
    public function __construct( $args ){
        //assign value
        $this->args = $args;


        //actions
        add_action( 'admin_menu', [ $this, 'admin_menu_options' ] );
    }

    public function admin_menu_options(){

        $this->pages = apply_filters( $this->args['prefix'] . 'page_settings', [] );

        $types = [
			'page'			=> [],
			'subpage'		=> [],
			'settings_page'	=> []
		];
        
        foreach( $this->pages as $key => $page ){

            //check
            if(  !empty( $page['type'] ) && ! array_key_exists( $page['type'], $types ) ){
                continue;
            }

            $callback = !empty( $page['callback'] ) ? $page['callback'] : [ $this, 'options_page'];

            if( $page['type'] == 'page'){
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

    public function options_page(){

        // get current screen
		$screen = get_current_screen();
        $page = implode( ' ', $this->page_types['page'] );

        $valid_page = false;

        if( 'toplevel_page_' . $page == $screen->base ){
            $valid_page = true;
			$page_type = 'page';
			$url_page = 'admin.php';
        }
        
        // skip invalid pages
		if ( ! $valid_page )
        return;


        ?>
            <ul class="nav-tab-wrapper">
                <?php

                    // CHECK IF ANY TAB EXITS
                    if( array_key_exists( 'tabs', $this->pages[$page]) ){

                        // tabs
                        $tabs = $this->pages[$page]['tabs'];

                        $first_tab = key($tabs);
                        // print_r($first_tab);

                        // get requested tab
                        $get_tab = !empty( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $tabs ) ? $_GET['tab'] : $first_tab;

                        foreach( $tabs as $key => $tab ){
                            $url = admin_url( $url_page . '?page=' . $page . '&tab=' . $key );
                            echo '
				            <a class="nav-tab' . ( $get_tab === $key ? ' nav-tab-active' : '' ) .  ( ! empty( $tab['class'] ) ? ' ' . esc_attr( $tab['class'] ) : '' ) . '" href="' . ( $url !== '' ? esc_url( $url ) : '#' ) . '">' . esc_html( $tab['label'] ) . '</a>';
                        }
                    }
                ?>
            </ul>
        <?php

    }


}