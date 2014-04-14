<?php

require "classes/TidioChatOptions.php";

$tidioChatOptions = new TidioChatOptions();

//

$tidioPublicKey = $tidioChatOptions->getPublicKey();

$tidioPrivateKey = $tidioChatOptions->getPrivateKey();

$extensionUrl = plugins_url(basename(__DIR__).'/');

//

wp_register_style('tidio-chat-css', plugins_url('media/css/app-options.css', __FILE__) );

wp_enqueue_style('tidio-chat-css' );

?>

<div class="wrap">
	<h2>Tidio Live Chat</h2>
    
    <div id="chat-loading">
    	<p>Loading...</p>
    </div>
    
    <div id="chat-content"></div>
    
</div>

<script src="<?php echo $extensionUrl ?>/media/js/tidio-chat-options.js"></script>

<script>

var $ = jQuery;

tidioChatOptions.create({
	extension_url: '<?php echo $extensionUrl ?>',
	public_key: '<?php echo $tidioPublicKey ?>',
	private_key: '<?php echo $tidioPrivateKey ?>'
});

</script>



