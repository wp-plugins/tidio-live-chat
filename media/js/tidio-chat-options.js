var tidioChatOptions = {
	
	extension_url: null,
	
	public_key: null,
	
	private_key: null,
	
	plugin_url: '//visual-editor.tidioelements.com/',
	
	//
	
	$iframe: null,
	
	create: function(data){
		
		var default_data = {
			extension_url: null,
			public_key: null,
			private_key: null,
		};
		
		data = $.extend(default_data, data);
		
		//
		
		this.extension_url = data.extension_url;

		this.public_key = data.public_key;

		this.private_key = data.private_key;
		
		//
		
		this.registerProject(function(){
		
			tidioChatOptions.chatLoading();
		
		});
		
	},
	
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