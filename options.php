<?php

require "classes/TidioChatOptions.php";

$tidioChatOptions = new TidioChatOptions();

//

$tidioPublicKey = $tidioChatOptions->getPublicKey();

$tidioPrivateKey = $tidioChatOptions->getPrivateKey();

$chatSettings = $tidioChatOptions->getChatSettings();

$extensionUrl = plugins_url(basename(__DIR__).'/');

//

wp_register_style('tidio-chat-css', plugins_url('media/css/app-options.css', __FILE__) );

wp_enqueue_style('tidio-chat-css' );


?>

<div class="wrap">
	<h2>Tidio Live Chat<a href="#" id="chat-settings-link" class="settings-link">settings</a></h2>
    
    <div id="chat-loading">
    	<p>Loading...</p>
    </div>
    
    <div id="chat-content"></div>
    
</div>

<!-- Dialog -->

<div class="frame-dialog-wrap" id="dialog-settings">
	
    <div class="frame-dialog content">
    	
        <a href="#" class="btn-close">&times;</a>
        
        <h3>Settings</h3>
        
        <form class="form-default" id="dialog-settings-form">
        	
            <div class="e clearfix">
                <label>Email:</label>
                <input type="text" value="" name="email" id="settings-form-email-input" />
            </div>

            <div class="e clearfix">
                <label>Theme Color:</label>
                <input type="text" name="base_color" id="settings-form-base-color-input" />
            </div>

            <div class="e clearfix">
                <label>Online Message:</label>
                <input type="text" value="" name="online_message" id="settings-form-online-message-input" />
            </div>

            <div class="e clearfix">
                <label>Offline Message:</label>
                <input type="text" value="" name="offline_message" id="settings-form-offline-message-input" />
            </div>
            
            <input type="hidden" value="pl" name="language" id="settings-form-language-input" />
            
            <div class="e e-submit clearfix">
            	
                <button type="submit" class="button button-primary" data-text="save changes" id="dialog-settings-form-submit">save changes</button>
                
            </div>
            
        </form>
        
    </div>
    
</div>

<!-- Dialog Overlay -->

<div id="dialog-overlay"></div>

<!-- Le' Script -->

<script src="<?php echo $extensionUrl ?>/media/js/plugin-minicolors.js"></script>
<script src="<?php echo $extensionUrl ?>/media/js/tidio-chat-options.js"></script>

<script>

var $ = jQuery;

tidioChatOptions.create({
	extension_url: '<?php echo $extensionUrl ?>',
	public_key: '<?php echo $tidioPublicKey ?>',
	private_key: '<?php echo $tidioPrivateKey ?>',
	settings: <?php echo json_encode($chatSettings); ?>,
	ajax_url: '<?php echo admin_url() ?>'
});

</script>



