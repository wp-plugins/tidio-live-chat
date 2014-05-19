<?php

/**
 * Plugin Name: Tidio Chat
 * Plugin URI: http://www.tidioelements.com
 * Description: Free live chat from Tidio Elements
 * Version: 1.3.2
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
		
		add_action('init', array($this, 'addCode'));
			 
		add_action("wp_ajax_tidio_chat_settings_update", array($this, "ajaxPageSettingsUpdate"));	 
			 
	}
	
	// Menu Positions
	
	public function addAdminMenuLink(){
		
        $optionPage = add_menu_page(
                'Live Chat', 'Live Chat', 'manage_options', 'tidio-chat', array($this, 'addAdminPage'), content_url().'/plugins/tidio-live-chat/media/img/icon.png'
        );
        $this->pageId = $optionPage;
		
	}
	
    public function addAdminPage() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }

	
	// Enqueue Script
	
	public function addCode(){
		
		if(is_admin()){
			return false;
		}
		
		//
		
		$tidioPublicKey = get_option('tidio-chat-public-key');
		
		if(empty($tidioPublicKey)){
			TidioPluginsScheme::insertCode('chat', '');
			return false;
		}
		
		//
		
		$html = '';
				
		//
				
		$chatSettings = $this->getChatSettings();
										
		//
		
		$html .= '<script type="text/javascript" src="//www.tidioelements.com/uploads/addons/addon-chat-en.js"></script>';
		
		$html .= "<script type=\"text/javascript\">";
			
		$html .= "if(typeof tidioElementsLang!='object'){ var tidioElementsLang = {}; }";
			
		if(!empty($chatSettings['translate'])){
			$html .= "tidioElementsLang['pluginChat'] = ".json_encode($chatSettings['translate']).";";
		}
			
		$html .= "</script>";
		
		$html .= '<style media="screen">';
		$html .= '#tidio-plugin-container-right-bottom .tidio-layout-popup, #tidio-chat-popup .tidio-layout-popup { background-color: '.$chatSettings['base_color'].' !important;color: #ffffff; }';
		$html .= '</style>';
		
		//
						
		$html .=
		'<script type="text/javascript">'.
		"if(typeof tidioElementsAddons!='object'){ var tidioElementsAddons = []; }".
		"tidioElementsAddons.push(".json_encode(array(
			'type' => 'chat',
			'addon_data' => array(
				'email' => $chatSettings['email'],
				'button_label' => $chatSettings['online_message'],
				'base_color' => $chatSettings['base_color'],
				'project_public_key' => $tidioPublicKey
			)
		)).");".
		'</script>';
		
		TidioPluginsScheme::insertCode('chat', $html);
		
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
	
	// Chat Settings
	
	private function getChatSettings(){

		$chatSettings = get_option('tidio-chat-settings');
		
		if(!$chatSettings){
			
			return array(
				'email' => get_option('email'),
				'base_color' => '#32475c',
				'online_message' => 'Chat with us!'
			);
			
		}
		
		$chatSettings = json_decode($chatSettings, true);
		
		return $chatSettings;

	}
}

$tidioLiveChat = new TidioLiveChat();

