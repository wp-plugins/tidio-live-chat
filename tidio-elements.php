<?php

/**
 * Plugin Name: Tidio Chat
 * Plugin URI: http://www.tidiochat.com
 * Description: Free live chat from Tidio Elements
 * Version: 2.0
 * Author: Tidio Ltd.
 * Author URI: http://www.tidiochat.com
 * License: GPL2
 */
class TidioLiveChat {

    private $scriptUrl = '//www.tidiochat.com/redirect/';

    public function __construct() {
        add_action('admin_menu', array($this, 'addAdminMenuLink'));
        add_action('admin_footer', array($this, 'adminJS'));

        self::getPrivateKey();
        
        if(!is_admin()){
            add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        }
//
//        if (!is_admin()) {
//            wp_enqueue_script('tidio-chat', 'https://www.tidiochat.com/uploads/redirect/' . self::getPublicKey() . '.js', array(), '1.0.0', true);
//        }
    }
    
    // Front End Scripts
    
    public function enqueueScripts(){
            wp_enqueue_script('tidio-chat', 'https://www.tidiochat.com/uploads/redirect/' . self::getPublicKey() . '.js', array(), '1.0.0', true);
    }

    // Admin JavaScript

    public function adminJS() {

        echo "<script> jQuery('a[href=\"admin.php?page=tidio-chat\"]').attr('href', 'http://external.tidiochat.com/access?privateKey=" . self::getPrivateKey() . "').attr('target', '_blank') </script>";
    }

    // Menu Pages

    public function addAdminMenuLink() {

        $optionPage = add_menu_page(
                'Live Chat', 'Live Chat', 'manage_options', 'tidio-chat', array($this, 'addAdminPage'), content_url() . '/plugins/tidio-live-chat/media/img/icon.png'
        );

        // jQuery('a[href="admin.php?page=tidio-chat"]').attr('href', 'http://www.google.com').attr('target', '_blank')
    }

    public function addAdminPage() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }

    // Get Private Key

    public static function getPrivateKey() {

        $privateKey = get_option('tidio-chat-external-private-key');

        if ($privateKey) {
            return $privateKey;
        }

        @$data = file_get_contents('http://www.tidiochat.com/access/create?url='.site_url().'&platform=wordpress&email='.get_option('admin_email'));
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

