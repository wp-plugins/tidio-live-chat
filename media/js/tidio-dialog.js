var tidioDialog = {
	
	// Settings
	
	init: function(){
		
		if(this.dialog_init)
			
			return false;
			
		//
		
		this.dialog_init = true;
		
		$("body").on('click', function(e){
			
			if(!tidioDialog.dialog_current){
				
				return false;
				
			}
			
			if(!$(e.target).closest('.frame-dialog').length){
				
				tidioDialog.hide(tidioDialog.dialog_current);
				
			}
						
			return false;
			
		});
		
		
		$('.frame-dialog .btn-close').on('click', function(){
			
			if(!tidioDialog.dialog_current){
				
				return false;
				
			}
			
			tidioDialog.hide(tidioDialog.dialog_current);
			
			return false;
			
		});
		
	},
	
	show: function(selector){
		
		if(!this.dialog_init){
			
			this.init();
			
		}

		if(typeof selector=='string')
			
			selector = $(selector);
			
		//

		selector.fadeIn('fast').addClass('dialog-active');
		
		$("#dialog-overlay").fadeIn('fast');
		
		//
		
		this.dialog_current = selector;
		
	},
	
	hide: function(selector){
		
		if(typeof selector=='string')
			
			selector = $(selector);
			
		//
		
		selector.fadeOut('fast').removeClass('dialog-active');
		
		$("#dialog-overlay").fadeOut('fast');
		
	}	
};