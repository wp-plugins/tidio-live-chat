<?php

/**
 * Plugin Name: Tidio Chat
 * Plugin URI: http://www.tidioelements.com
 * Description: Free live chat from Tidio Elements
 * Version: 1.3.1
 * Author: Tidio Ltd.
 * Author URI: http://www.tidiomobile.com
 * License: GPL2
 */
 
if(!class_exists('TidioPluginsScheme')){
	 
	 require "classes/TidioPluginsScheme.php";
	 
} 
 
class TidioLiveChat {
	
	private $scriptUrl = '//tidioelements.com/uploads/redirect-plugin/';
	
	private $pageId = '';
		
	public function __construct() {
				
		add_action('admin_menu', array($this, 'addAdminMenuLink'));
		
		add_action('wp_enqueue_scripts', array($this, 'enqueueScript'));
			 
		add_action("wp_ajax_tidio_chat_settings_update", array($this, "ajaxPageSettingsUpdate"));	 
			 
	}
	
	// Menu Positions
	
	public function addAdminMenuLink(){
		
        $optionPage = add_menu_page(
                'Live Chat', 'Live Chat', 'manage_options', 'tidio-chat', array($this, 'addAdminPage'), content_url().'/plugins/tidio-chat/media/img/icon.png'
        );
        $this->pageId = $optionPage;
		
	}
	
    public function addAdminPage() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }

	
	// Enqueue Script
	
	public function enqueueScript(){

		$iCanUseThisPlugin = TidioPluginsScheme::usePlugin('chat');
		
		if(!$iCanUseThisPlugin){
						
			return false;
			
		}
		
		$tidioPublicKey = get_option('tidio-chat-public-key');
				
        if(!empty($tidioPublicKey)){
			
            wp_enqueue_script('tidio-chat',  $this->scriptUrl.$tidioPublicKey.'.js', array(), '1.1', false);
			
		}

	}
	
	// Ajax Pages
	
	public function ajaxPageSettingsUpdate(){

		if(empty($_POST['settingsData'])){
			
			$this->ajaxResponse(false, 'ERR_PASSED_DATA');
			
		}
		
		$chatSettings = $_POST['settingsData'];
		
		$chatSettings = urldecode($chatSettings);
		
		$chatSettings = str_replace("\'", "'", $chatSettings);
								
		//
				
		update_option('tidio-chat-settings', $chatSettings);
						
		$this->ajaxResponse(true, true);

	}

	public function ajaxResponse($status = true, $value = null){
		
		echo json_encode(array(
			'status' => $status,
			'value' => $value
		));	
		
		exit;
			
	}
}

$tidioLiveChat = new TidioLiveChat();

