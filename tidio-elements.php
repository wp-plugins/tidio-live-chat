<?php

/**
 * Plugin Name: Tidio Chat
 * Plugin URI: http://www.tidiochat.com
 * Description: Tidio Live Chat - Live chat for your website. No logging in, no signing up - integrates with your website in less than 20 seconds.
 * Version: 2.1.1
 * Author: Tidio Ltd.
 * Author URI: http://www.tidiochat.com
 * License: GPL2
 */
  
class TidioLiveChat {

    private $scriptUrl = '//code.tidio.co/';
    private $tidioOne;

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
            echo '2.1.1';
            exit;
        }
        
		// WooCommerce Hooks - Active only after activiation by user, default is off
		
		if(get_option('tidio-one-woo-hooks-chat') && !class_exists('TidioOneApi')){

			$tidioOneLibPath = plugin_dir_path(__FILE__).'classes/TidioOneApi.php';
			
			if(file_exists($tidioOneLibPath)){
				
				include($tidioOneLibPath);
				
				$this->tidioOne = new TidioOneApi( self::getPublicKey() );
				add_action('woocommerce_checkout_order_processed', array($this, 'wooPaymentCharged'));
				add_action('woocommerce_add_to_cart', array($this, 'wooAddToCart'), 10, 6);
				add_action('woocommerce_cart_item_removed', array($this, 'wooRemoveFromCart'), 10, 2);
				
				add_action('wp_head',array($this, 'wooAddScript'));
			}
		}
		
		// Activation by user process, have to use private key
		
		if(!empty($_GET['tidio_one_hooks_activiation']) && $_GET['tidio_one_hooks_activiation']==self::getPrivateKey()){
			if(!get_option('tidio-one-woo-hooks')){
				update_option('tidio-one-woo-hooks', '1');
				update_option('tidio-one-woo-hooks-chat', '1');
				echo 'OK';
				exit;
			} else {
				echo 'SETTED';
				exit;
			}
		};
		
    }
	
    // Ajax - Create an new project
	
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
    	wp_enqueue_script('tidio-chat', $this->scriptUrl . self::getPublicKey() . '.js', array(), '2.1.1', true);
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
            'Tidio Chat', 'Tidio Chat', 'manage_options', 'tidio-chat', array($this, 'addAdminPage'), content_url() . '/plugins/tidio-live-chat/media/img/icon.png'
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
    
	// WooCommerce Hooks - Thanks to this option, chat operator can see what user do while talking with him
	// Based on Tidio One API

    public function wooPaymentCharged($orderId) {
		
		$visitorId = $this->getVisitorId();
		
		if(!$visitorId){
			return false;
		}
		
        $order = new WC_Order($orderId);
        $curreny = $order->get_order_currency();
        $amount = $order->get_total();
        $response = $this->tidioOne->request('api/track', array(
			'name' => 'payment charged',
			'visitorId' => $visitorId
        ));
    }

    public function wooAddToCart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
		
		$visitorId = $this->getVisitorId();
				
		if(!$visitorId){
			return false;
		}
		
        $response = $this->tidioOne->request('api/track', array(
			'name' => 'add to cart',
			'visitorId' => $visitorId,
			'data' => array(
				'_product_id' => $product_id,
				'_product_name' => get_the_title($product_id),
				'_product_quantity' => $quantity,
				'_product_url' => get_permalink($product_id),
			)
        ));
    }

    public function wooRemoveFromCart($cart_item_key, $cart) {
		
		$visitorId = $this->getVisitorId();
		
		if(!$visitorId){
			return false;
		}
		
        foreach ($cart->removed_cart_contents as $key => $removed) {
            $product_id = $removed['product_id'];
            $quantity = $removed['quantity'];
            $response = $this->tidioOne->request('api/track', array(
				'name' => 'remove from cart',
				'visitorId' => $visitorId,
				'data' => array(
					'_product_id' => $product_id,
					'_product_quantity' => $quantity,
				)
            ));
        }
    }
	
	public function wooAddScript(){
		echo '<script type="text/javascript"> document.tidioOneWooTrackingInside = 1; </script>';
	}
	
	private function getVisitorId(){

		if(empty($_COOKIE['_tidioOne_'])){
			return null;
		}
		
		if(!function_exists('json_decode')){
			return null;
		}
		
		$data = $_COOKIE['_tidioOne_'];
		
		$data = str_replace('\"', '"', $data);
		
		@$data = json_decode($data, true);
				
		if(!$data || empty($data['tidioOneVistiorId'])){
			return null;
		}
				
		return $data['tidioOneVistiorId'][0];
		
	}

}

$tidioLiveChat = new TidioLiveChat();

