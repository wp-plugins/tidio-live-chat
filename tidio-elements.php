<?php

/**
 * Plugin Name: Tidio Chat
 * Plugin URI: http://www.tidioelements.com
 * Description: Free live chat from Tidio Elements
 * Version: 1.0
 * Author: Tidio Ltd.
 * Author URI: http://www.tidiomobile.com
 * License: GPL2
 */

class TidioLiveChat {

    /**
     * Id of plugin option page.
     * @var string
     */
    public $page_id;
    
	private $script_path = '//tidioelements.com/uploads/redirect-plugin/';

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('wp_enqueue_scripts', array($this, 'theme_scripts'));
        
		// add_action('wp_ajax_tidio_visual_set_key', array($this, 'ajax_set_key'));
    }

    function ajax_set_key() {
        global $wpdb;

        $key = $_POST['key'];
        $option_name = 'tidio-public-key';
        $re = update_option($option_name, $key);
        return json_encode(array('success' => true));
    }

    /**
     * Adds help tab on option page.
     */
    public function help_tab() {
        $screen = get_current_screen();
        /*
         * Check if current screen is ok
         * Don't add help tab if it's not
         */
        if ($screen->id != $this->page_id)
            return;
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
        $option_page = add_menu_page(
                'Live Chat', 'Live Chat', 'manage_options', 'tidio-chat', array($this, 'create_admin_page'), plugins_url(basename(__DIR__) . '/media/img/icon.png')
        );
        $this->page_id = $option_page;
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }

    /**
     * Register and add settings
     */
    public function theme_scripts() {
        
		$tidioPublicKey = get_option('tidio-chat-public-key');
				
        if (!empty($tidioPublicKey)){
			
            wp_enqueue_script('tidio-chat',  $this->script_path.$tidioPublicKey.'.js', array(), '1.0', false);
			
		}
		
    }

}

/**
 * Create new instance of plugin class
 */
$my_settings_page = new TidioLiveChat();

