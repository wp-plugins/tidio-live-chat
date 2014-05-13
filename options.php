<?php

define('TIDIO_CHAT_PLUGIN_URL', content_url().'/plugins/tidio-live-chat/');

//

require "classes/TidioPluginUpgrade.php";

$tidioPluginUpgrade = new TidioPluginUpgrade();

$tidioPluginUpgrade->init();

//

require "classes/TidioChatOptions.php";

$tidioChatOptions = new TidioChatOptions();

//

if(!class_exists('TidioPluginsScheme')){

	require "classes/TidioPluginsScheme.php";

}

TidioPluginsScheme::registerPlugin('chat');

//

$tidioPublicKey = $tidioChatOptions->getPublicKey();

$tidioPrivateKey = $tidioChatOptions->getPrivateKey();

$chatSettings = $tidioChatOptions->getChatSettings();

$compatibilityPlugin = TidioPluginsScheme::compatibilityPlugin('chat');

//

wp_register_style('tidio-chat-css', plugins_url('media/css/app-options.css', __FILE__) );

wp_enqueue_style('tidio-chat-css' );

?>

<script> var $ = jQuery; </script>
<script src="<?php echo TIDIO_CHAT_PLUGIN_URL ?>media/js/plugin-minicolors.js"></script>
<script src="<?php echo TIDIO_CHAT_PLUGIN_URL ?>media/js/tidio-dialog.js"></script>
<script src="<?php echo TIDIO_CHAT_PLUGIN_URL ?>media/js/plugin-upgrade.js"></script>
<script src="<?php echo TIDIO_CHAT_PLUGIN_URL ?>media/js/translate-dialog.js"></script>
<script src="<?php echo TIDIO_CHAT_PLUGIN_URL ?>media/js/tidio-chat-options.js"></script>

<?php

if(!TidioPluginUpgrade::getUserAccessKey()){
	
	require 'views/before-upgrade.php';
	
} else if(TidioPluginUpgrade::getUserAccessKey()) {
	
	require 'views/after-upgrade.php';
	
}

?>

<!-- Dialog Overlay -->

<div id="dialog-overlay"></div>

<!-- Le' Script -->

<script>

tidioChatOptions.create({
	extension_url: '<?php echo TIDIO_CHAT_PLUGIN_URL ?>',
	public_key: '<?php echo $tidioPublicKey ?>',
	private_key: '<?php echo $tidioPrivateKey ?>',
	settings: <?php echo json_encode($chatSettings); ?>,
	ajax_url: '<?php echo admin_url() ?>'
});

translateDialog.create();

pluginUpgrade.create();

translateDialog.showDialog();

</script>



