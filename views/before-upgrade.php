<div class="wrap">
	
	<a href="http://www.tidioelements.com/?utm_source=wordpress&utm_medium=cpc&utm_campaign=plugin_inside&utm_content=chat" id="tidio-top-logo"></a>

	<h2>Tidio Live Chat<a href="#" id="chat-settings-link" class="settings-link">settings</a></h2>

    <?php if(!$compatibilityPlugin): ?>
        
    <div class="alert alert-info" style="margin: 10px 0px 15px;">We're sorry, this plugin is not compatible with other Tidio Elements plugins - that is why it cannot be displayed on your site. To take advantage of all the possibilities our platform offers, please install <a href="http://wordpress.org/plugins/tidio-elements-integrator/" target="_blank" style="font-weight: bold;">Tidio Elements Integrator</a> plugin or uninstall the other plugins.</div>    
    
    <?php endif; ?>

	<!--<p><a href="#" class="tidio-plugin-upgrade-link">click to upgrade</a></p>-->
    
    <div id="chat-loading">
    	<p>Loading...</p>
    </div>
    
    <div id="chat-content"></div>
    
</div>

<!-- Dialog Settings -->

<div class="frame-dialog-wrap" id="dialog-settings">
	
    <div class="frame-dialog content">
    	
        <a href="#" class="btn-close">&times;</a>
        
        <h3>Settings</h3>
        
        <!-- settings form -->
        
        <form class="form-default" id="dialog-settings-form" action="" method="post">
        	
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

            <div class="e clearfix">
                <label>Translate:</label>
                <a href="#" id="settings-form-translate-link">click here to translate plugin</a>
            </div>
            
            <input type="hidden" value="pl" name="language" id="settings-form-language-input" />
            
            <div class="e e-submit clearfix">
            	
                <button type="submit" class="button button-primary" data-text="save changes" id="dialog-settings-form-submit">save changes</button>
                
            </div>
            
        </form>
        
    </div>
    
</div>

<!-- Dialog Translate -->

<div class="frame-dialog-wrap" id="dialog-translate">
	
    <div class="frame-dialog content">
    	
        <a href="#" class="btn-close">&times;</a>
        
        <h3>Translate</h3>
                
        <form class="form-default" id="dialog-translate-form" action="" method="post">
        	                        
            <div class="e e-submit clearfix">
            	
                <button type="submit" class="button button-primary" data-text="save changes" id="dialog-translate-submit">save changes</button>
                
            </div>
            
        </form>

    </div>
    
</div>


<!-- Dialog Upgrade -->

<div class="frame-dialog-wrap" id="dialog-upgrade" style="display: none;">
	
    <div class="frame-dialog content">
    	
        <a href="#" class="btn-close">&times;</a>
        
        <h3>Upgrade Your Plugin</h3>
        
        <!-- intro -->
                
        <div id="dialog-upgrade-intro">
        	
            <p>Are you ready to go level up? - <a href="#" class="dialog-upgrade-register-link">click here</a></p>
            
        </div>
        
        <!-- dialog upgrade login -->

        <div id="dialog-upgrade-login" style="display: none;">

			<p>Do don't have an account? - <a href="#" class="dialog-upgrade-register-link">click here</a></p>
			
            <div id="upgrade-login-form-alert"></div>
            
            <form class="form-default" id="upgrade-login-form" action="" method="post">
                
                <div class="e clearfix">
                    <label>Email:</label>
                    <input type="email" value="" id="login-email" required="1" />
                </div>
    
                <div class="e clearfix">
                    <label>Password:</label>
                    <input type="password" value="" id="login-password" required="1" />
                </div>
                
                <div class="e e-submit clearfix">
                    <button type="submit" class="button button-primary" id="login-submit" value="1">Sign In</button>
                </div>
                
            </form>

        </div>
        
        <!-- dialog upgrade register -->
        
        <div id="dialog-upgrade-register" style="display: none;">
        
        	<p>Do you have an account? - <a href="#" class="dialog-upgrade-login-link">click here</a></p>
        
			<div id="upgrade-register-form-alert"></div>
        	
            <form class="form-default" id="upgrade-register-form" action="" method="post">
                
                <div class="e clearfix">
                    <label>Email:</label>
                    <input type="text" value="" name="email" id="register-email" required="1"  />
                </div>
    
                <div class="e clearfix">
                    <label>Password:</label>
                    <input type="password" value="" name="email" id="register-password" required="1" />
                </div>

                <div class="e clearfix">
                    <label>Repeat Password:</label>
                    <input type="password" value="" name="email" id="register-password-2" required="1" />
                </div>
                
                <div class="e e-submit clearfix">
                    <button type="submit" class="button button-primary" id="register-submit" value="1">Sign Up</button>
                </div>
                
            </form>
        
        </div>
        
    </div>
    
</div>

<script>

jQuery("#tidio-top-logo").on('click', function(){
	
	location.href = this.getAttribute('href');
	
});

</script>

<script>
UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/D0pU3d20Ujw1JxjtZpriA.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();
UserVoice.push(['set', {
  accent_color: '#448dd6',
  trigger_color: 'white',
  trigger_background_color: 'rgba(46, 49, 51, 0.6)'
}]);

UserVoice.push(['identify', {
  type:	'WP Chat'
}]);

UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'bottom-right' }]);
UserVoice.push(['autoprompt', {}]);
</script>
