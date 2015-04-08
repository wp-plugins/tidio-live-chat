<?php

/**
 * Plugin Name: Tidio Chat
 * Plugin URI: http://www.tidiochat.com
 * Description: Tidio Live Chat - Live chat for your website. No logging in, no signing up - integrates with your website in less than 20 seconds.
 * Version: 2.0.6
 * Author: Tidio Ltd.
 * Author URI: http://www.tidiochat.com
 * License: GPL2
 */
 
class TidioLiveChat {

    private $scriptUrl = '//code.tidio.co/';

    public function __construct() {
        add_action('admin_menu', array($this, 'addAdminMenuLink'));
        add_action('admin_footer', array($this, 'adminJS'));

        self::getPrivateKey();
        
        if(!is_admin()){
            add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        }
        
        add_action('deactivate_'.plugin_basename(__FILE__), array($this, 'uninstall'));	

        add_action('wp_ajax_tidio_chat_redirect', array($this, 'ajaxTidioChatRedirect'));
        
        if(!empty($_GET['tidio_chat_clear_cache'])){
            delete_option('tidio-chat-external-public-key');
            delete_option('tidio-chat-external-private-key');
        }
        
        if(!empty($_GET['tidio_chat_version'])){
            echo '2.0.5';
            exit;
        }

    }
	
	// Ajax - Create New Project
	
	public function ajaxTidioChatRedirect(){
		
		if(!empty($_GET['access_status']) && !empty($_GET['private_key']) && !empty($_GET['public_key'])){
			
			update_option('tidio-chat-external-public-key', $_GET['public_key']);
			update_option('tidio-chat-external-private-key', $_GET['private_key']);
			
			$view = array(
				'mode' => 'redirect',
				'redirect_url' => self::getRedirectUrl($_GET['private_key'])
			);
			
		} else {
		
			$view = array(
				'mode' => 'access_request',
				'access_url' => self::getAccessUrl()
			);
							
		}
		
		require "views/ajax-tidio-chat-redirect.php";

		exit;
		
	}
	    
    // Front End Scripts
    
    public function enqueueScripts(){
    	wp_enqueue_script('tidio-chat', $this->scriptUrl . self::getPublicKey() . '.js', array(), '2.0.6', true);
		
		function insert_tidio_chat_noscript_code() {
			echo '<noscript><a href="https://www.tidiochat.com" title="tidiochat.com" target="_blank">tidiochat.com</a></noscript>';
		};
		add_action('wp_footer', 'insert_tidio_chat_noscript_code');
    }

    // Admin JavaScript

    public function adminJS() {
		
		$privateKey = self::getPrivateKey();
		$redirectUrl = '';
		
		if($privateKey && $privateKey!='false'){
        	$redirectUrl = self::getRedirectUrl($privateKey);
		} else {
			$redirectUrl = admin_url('admin-ajax.php?action=tidio_chat_redirect');
		}
		
		echo "<script> jQuery('a[href=\"admin.php?page=tidio-chat\"]').attr('href', '".$redirectUrl."').attr('target', '_blank') </script>";
		
	}

    // Menu Pages

    public function addAdminMenuLink() {

        $optionPage = add_menu_page(
			'Live Chat', 'Live Chat', 'manage_options', 'tidio-chat', array($this, 'addAdminPage'), content_url() . '/plugins/tidio-live-chat/media/img/icon.png'
        );
    }

    public function addAdminPage() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }
    
    // Uninstall
	
    public function uninstall(){
    }

    // Get Private Key

    public static function getPrivateKey() {
		
        $privateKey = get_option('tidio-chat-external-private-key');

        if ($privateKey) {
            return $privateKey;
        }

        @$data = file_get_contents(self::getAccessUrl());
        if (!$data) {
            update_option('tidio-chat-external-private-key', 'false');
            return false;
        }

        @$data = json_decode($data, true);
        if (!$data || !$data['status']) {
            update_option('tidio-chat-external-private-key', 'false');
            return false;
        }

        update_option('tidio-chat-external-private-key', $data['value']['private_key']);
        update_option('tidio-chat-external-public-key', $data['value']['public_key']);

        return $data['value']['private_key'];
    }
	
	// Get Access Url
	
	public static function getAccessUrl(){
		
		return 'https://www.tidiochat.com/access/create?url='.urlencode(site_url()).'&platform=wordpress&email='.urlencode(get_option('admin_email')).'&_ip='.$_SERVER['REMOTE_ADDR'];
		
	}
	
	public static function getRedirectUrl($privateKey){
		
		return 'https://external.tidiochat.com/access?privateKey='.$privateKey;
		
	}
	
	// Get Public Key

    public static function getPublicKey() {

        $publicKey = get_option('tidio-chat-external-public-key');

        if ($publicKey) {
            return $publicKey;
        }

        self::getPrivateKey();

        return get_option('tidio-chat-external-public-key');
    }
    
    

}

$tidioLiveChat = new TidioLiveChat();

