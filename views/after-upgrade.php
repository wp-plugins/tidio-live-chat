<div class="wrap">
	<h2>Tidio Live Chat</h2>

    <?php if(!$compatibilityPlugin): ?>
    <div class="alert alert-info" style="margin: 10px 0px 15px;">We're sorry, this plugin is not compatible with other Tidio Elements plugins - that is why it cannot be displayed on your site. To take advantage of all the possibilities our platform offers, please install <a href="http://wordpress.org/plugins/tidio-elements-integrator/" target="_blank" style="font-weight: bold;">Tidio Elements Integrator</a> plugin or uninstall the other plugins.</div>    
    <?php endif; ?>

	<p>Dziękujemy za aktualizacje pluginu, aby zarządzać pluginem wydzieliśmy 2 sekcje, pierwsza to zarządzanie listą użytkowników, natomiast druga odpowiada za zarządzanie widokiem!</p>

	<p>
    	<a href="http://www.tidioelements.com/editor/<?php echo $tidioChatOptions->getPublicKey() ?>?userAccessKey=<?php echo TidioPluginUpgrade::getUserAccessKey() ?>&userLoginSession=1#plugin,chat,openSettings" class="button button-primary" target="_blank">Przejdź do <strong>edycji wyglądu</strong></a>
    	<a href="http://www.tidioelements.com/en/panel/plugins/chat?userAccessKey=<?php echo TidioPluginUpgrade::getUserAccessKey() ?>&userLoginSession=1" class="button button-primary" target="_blank">Przejdź do <strong>panelu zarządzania</strong></a>
    </p>
    
    
</div>
