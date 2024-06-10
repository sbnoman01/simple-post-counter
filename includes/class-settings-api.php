<?php

namespace Simple_Post_Counter\Includes;
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class Settings_Api {
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
            }

        }
    }

    public function options_page(){
        echo 'hello counter';
    }


}