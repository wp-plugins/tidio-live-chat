var tidioChatOptions = {
		
	public_key: null,
	
	private_key: null,
	
	//
	
	plugin_url: 'http://visual-editor.tidioelements.com/',
	
	api_url: '//www.tidioelements.com/',
	
	extension_url: null,
	
	ajax_url: null,
	
	translate: null,
	
	//
	
	$iframe: null,
	
	create: function(data){
		
		// this.api_url = 'http://localhost/tidio_elements_app/public/';
		
		//
				
		var default_data = {
			extension_url: null,
			public_key: null,
			private_key: null,
			settings: null,
			translate: null,
			ajax_url: null
		};
		
		data = $.extend(default_data, data);
		
		//
		
		this.extension_url = data.extension_url;

		this.public_key = data.public_key;

		this.private_key = data.private_key;

		this.settings = data.settings;

		this.ajax_url = data.ajax_url;
		
		//
		
		if(this.settings['translate']){
			
			translateDialog.importTranslate(this.settings['translate']);
			
			console.log('this.translate', this.settings['translate']);
			
		}
		
		//
		
		this.registerProject(function(){
		
			tidioChatOptions.chatLoading();
		
		});
		
		this.initEvents();
		
		this.replayDialogSettings();
				
	},
	
	// Events
	
	initEvents: function(){
		
		$("#chat-settings-link").on('click', function(){
			
			tidioDialog.show('#dialog-settings');
			
			return false;
			
		});
		
		$("#dialog-settings-form-submit").on('click', function(){
			
			$("#dialog-settings-form-submit").text('loading...');
			
			tidioChatOptions.apiUpdateSettings(function(){
				
				$("#dialog-settings-form-submit").text( $("#dialog-settings-form-submit").attr('data-text') );
				
			});
			
		});
		
		$("#settings-form-translate-link").on('click', function(){
			
			tidioDialog.hide('#dialog-settings');
			
			translateDialog.showDialog();
			
		});
		
		//
		
		$("#settings-form-base-color-input").minicolors();
		
	},
		
	// Settings Update
	
	apiUpdateSettings: function(_func){
		
		if(typeof _func!='function')
			_func = function(){};
			
		//
		
		var xhr_url = this.api_url + 'apiExternalPlugin/updateData?privateKey=' + this.private_key;
						
		var plugin_data = tidioChatOptions.apiDataSerialize('#dialog-settings-form');
				
		$.ajax({
			url: xhr_url,
			type: 'POST',
			data: {
				pluginData: plugin_data
			}
		}).done(function(){
			
			_func();
			
		});
		
		//
		
		$.ajax({
			url: this.ajax_url + 'admin-ajax.php?action=tidio_chat_settings_update',
			type: 'POST',
			data: {
				settingsData: encodeURI(plugin_data)
			}
		});
		
	},
	
	apiDataSerialize: function(selector){
		
		var api_data = {};
		
		$(selector).find('[name]').each(function(){
			
			var this_name = this.getAttribute('name'),
				this_value = this.value;
				
			api_data[this_name] = this_value;
			
		});
		
		api_data['translate'] = translateDialog.exportData();
		
		
		//
		
		return JSON.stringify(api_data);
	
	},
	
	// Dialog Settings
	
	replayDialogSettings: function(){
		
		if(!this.settings)
			
			return false;
			
		//
					
		$("#settings-form-email-input").val(this.settings['email']);

		$("#settings-form-base-color-input").val(this.settings['base_color']);

		$("#settings-form-online-message-input").val(this.settings['online_message']);

		$("#settings-form-offline-message-input").val(this.settings['offline_message']);

		$("#settings-form-language-input").val(this.settings['language']);
		
	},
		
	// Register Project
	
	registerProject: function(_func){
		
		_func();
		
	},
	
	//
	
	chatLoading: function(){
		
		var iframe_height = $('body').height() - ($("#wpadminbar").height() + $(".wrap h2").height() + 10);
		
		
		
		$("#chat-content").html(
			'<iframe src="' + this.plugin_url + 'en/panel/plugins/chat/external?externalAccessKey=' + this.private_key + '" id="chat-iframe" style="height: ' + iframe_height + 'px;"></iframe>'
		);		
		
		$("#chat-iframe").load(function(){
			
			$("#chat-loading").fadeOut('fast', function(){
				
				$("#chat-content").fadeIn('fast');
				
				tidioChatOptions.chatIsReady();
				
			});
			
		});
						
	},
	
	chatIsReady: function(){
		
	}
	
	
};