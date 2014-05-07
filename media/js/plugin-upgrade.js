var pluginUpgrade = {
	
	api_url: 'http://www.tidioelements.com',
	
	create: function(){
		
		this.initEvents();
		
	},
	
	initEvents: function(){
		
		$('.tidio-plugin-upgrade-link').on('click', function(){
			
			pluginUpgrade.showDialog();
			
			return false;
			
		});
		
		$('.dialog-upgrade-register-link').on('click', function(){
			
			pluginUpgrade.goToSection('register');
			
			return false;
			
		});


		$('.dialog-upgrade-login-link').on('click', function(){
			
			pluginUpgrade.goToSection('login');
			
			return false;
			
		});
		
		// Register Valid
				
		$("#register-submit").on('click', function(){
			
			var $upgrade_alert = $("#upgrade-register-form-alert"),
				form_err = pluginUpgrade._formValid('#upgrade-register-form');
						
			if(form_err){
				$upgrade_alert.html('<div class="alert alert-danger">Wypełnij wszystkie pola!</div>');
				return false;
			}
			
			//
			
			$upgrade_alert.html('<div class="alert alert-info">Trwa ładowanie...</div>');
			
			pluginUpgrade.apiRegister(function(status, data){
				
				if(!status && data=='ERR_EMAIL'){
					
					$upgrade_alert.html('<div class="alert alert-danger">Podany przez ciebie email jest już używany!</div>');
					
				} else if(!status){
					
					$upgrade_alert.html('<div class="alert alert-danger">Podczas zakładania konta wystąpił błąd!</div>');
					
				} else if(status){
					
					$upgrade_alert.html('<div class="alert alert-success"><strong>Zalogowany</strong>, poczekaj chwilę...</div>');
					
					pluginUpgrade.userUpgradeLocation(data.access_key);
					
				}
				
			});
						
			return false;
			
		});
		
		// Login Valid

		$("#login-submit").on('click', function(){
			
			var $upgrade_alert = $("#upgrade-login-form-alert"),
				form_err = pluginUpgrade._formValid('#upgrade-login-form');
						
			if(form_err){
				$upgrade_alert.html('<div class="alert alert-danger">Wypełnij wszystkie pola!</div>');
				return false;
			}
			
			//
			
			$upgrade_alert.html('<div class="alert alert-info">Trwa ładowanie...</div>');
			
			pluginUpgrade.apiLogin(function(status, data){
				
				if(!status){
					
					$upgrade_alert.html('<div class="alert alert-danger">Wpisałeś nieprawidłowy email lub hasło!</div>');
					
				} else if(status){
					
					$upgrade_alert.html('<div class="alert alert-success"><strong>Zalogowany</strong>, poczekaj chwilę...</div>');
					
					pluginUpgrade.userUpgradeLocation(data.access_key);
										
				}
				
			});
			
			return false;
			
		});

		
	},
		
	goToSection: function(id){
		
		$("#dialog-upgrade-intro,#dialog-upgrade-login,#dialog-upgrade-register").hide();
		
		$("#dialog-upgrade-" + id).show();
		
	},
	
	showDialog: function(){
		
		tidioDialog.show('#dialog-upgrade');
		
	},
	
	_formValid: function(selector){
		
		var form_err = false;
		
		$(selector + " [required1]").each(function(){
			
			if(this.getAttribute('type')=='email' && this.value.indexOf('@')==-1 && this.value.indexOf('.')==-1){
				
				form_err = true;
				
			} else if(!this.value){
				
				form_err = true;
				
			}
			
		});
		
		return form_err;
		
	},
	
	// User Upgrade Location
	
	userUpgradeLocation: function(access_key){
		
		var url = location.origin + location.pathname + location.search + '&userAccountUpgrade=' + access_key;
		
		location.href = url;
		
	},
	
	// API
	
	apiRegister: function(_func){
		
		var email = $("#register-email").val(),
			password = $("#register-password").val();
		
		$.ajax({
			url: this.api_url + '/apiUser/userRegister',
			data: {
				userEmail: email,
				userPassword: password
			},
			type: 'POST',
			dataType: 'json'
		}).done(function(data){
			
			if(!data || !data.status){
				_func(false, data.value);
				return false;
			}
			
			_func(true, data.value);
						
		}).fail(function(){
			
			_func(false);
			
		});
		
	},
	
	apiLogin: function(_func){

			
		$.ajax({
			url: this.api_url + '/apiUser/userLogin',
			data: {
				userEmail: $("#login-email").val(),
				userPassword: $("#login-password").val()
			},
			type: 'POST',
			dataType: 'json'
		}).done(function(data){
			
			if(!data || !data.status){
				_func(false, data.value);
				return false;
			}
			
			_func(true, data.value);
						
		}).fail(function(){
			
			_func(false);
			
		});

	}
	
};