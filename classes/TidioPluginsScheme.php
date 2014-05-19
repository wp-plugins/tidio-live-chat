<?php

/*
** Name: TidioPluginsScheme
** Version: 0.2
*/

class TidioPluginsScheme {
	
	public static $pluginUsed;
	
	public static $plugins;

	public static $insertCode;

	public static $pluginLoaded;
	
	public function __construct(){
		
		if(!self::$insertCode){	
			self::$insertCode = array();
		}

		if(!self::$pluginLoaded){	
			self::$pluginLoaded = array();
		}
		
	}
	
	public function __destruct(){
		
	}
	
	// Insert code
	
	public static function insertCode($pluginId, $html, $placement = false){
		
		if(self::findPlugin('integrator') || self::findPlugin('visual-editor')){
			
			if(class_exists('TidioElementsParser') && self::visualEditorIsActive()){
				
				self::$insertCode[] = $html;
				
				return true;
				
			}
						
		}
		
		// add plugin to db
		
		self::$insertCode[] = $html;
		
		self::pluginLoaded($pluginId);
		
		
		//
				
		if(self::pluginToLoadLength()==count(self::$pluginLoaded)){
						
			add_action('wp_head', 'TidioPluginsScheme::execCode');
			
		}
				
	}
	
	public static function pluginLoaded($pluginId){
		
		self::$pluginLoaded[$pluginId] = true;
		
	}
	
	public static function execCode(){
		
		foreach(self::$insertCode as $html){
			
			echo $html;
			
		}
		
		echo '<script type="text/javascript" src="http://www.tidioelements.com/uploads/addons/addon-core.js"></script>';
		
		return true;
		
	}
	//
	
	public static function pluginToLoadLength(){
		
		self::getPlugins();
		
		$length = 0;
		
		foreach(self::$plugins as $e){
			
			if($e=='visual-editor' || $e=='integrator'){
				continue;
			}
			
			$length++;
			
		}
		
		return $length;
		
	}
	
	// Checking if visual editor or integrator is active
	
	public static function visualEditorIsActive(){
		
		if(!get_option('tidio-visual-public-key') && !get_option('tidio-elements-project-data')){
			return false;
		}
		
		return true;
		
	}
	
	// In this method we used hierarchy like this - integrator, visual-editor and else first definied plugin
	
	public static function usePlugin($pluginId){
		
		if(self::$pluginUsed){
			
			return false;
			
		}
		
		if($pluginId=='integrator'){
			
			self::$pluginUsed = $pluginId;
			
			return true;
			
		} else if($pluginId=='visual-editor' && !self::findPlugin('integrator')){
			
			self::$pluginUsed = $pluginId;
			
			return true;
			
		} else if(!self::findPlugin('integrator') && !self::findPlugin('visual-editor')) {
			
			self::$pluginUsed = $pluginId;
			
			return true;
			
		}
		
		return false;		
		
		
	}
		
	
	// Plugin status
	
	public static function compatibilityPlugin($pluginName){
		
		if($pluginName=='integrator'){
			
			return true;
			
		}
		
		$plugins = self::getPlugins();
		
		if(count($plugins)==1){
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	public static function getPlugins(){
		
		if(self::$plugins){
			
			return self::$plugins;
			
		}
				
		$tidioPlugins = get_option('tidio-plugins');
						
		if(!$tidioPlugins){
			
			return array();
			
		} else {
		
			$tidioPlugins = json_decode($tidioPlugins, true);
		
		}
		
		self::$plugins = $tidioPlugins;
		
		//
		
		return $tidioPlugins;
		
	}
	
	public static function removePlugin($pluginName){
		$tidioPlugins = get_option('tidio-plugins');
		
		if($tidioPlugins){
			
			$tidioPlugins = json_decode($tidioPlugins, true);
			
			foreach($tidioPlugins as $i => $e){
				if($e==$pluginName){
					unset($tidioPlugins[$i]);
				}
			}
			
			$tidioPluginsPrepare = array();
			
			foreach($tidioPlugins as $e){
				$tidioPluginsPrepare[] = $e;
			}
			
			update_option('tidio-plugins', json_encode($tidioPluginsPrepare));
			
		}	
		
	}
	
	public static function registerPlugin($pluginName){

		$tidioPlugins = get_option('tidio-plugins');
							
		if(strstr($tidioPlugins, '"'.$pluginName.'"')){
		
			return false;
			
		}
						
		if(!$tidioPlugins){
			
			$tidioPlugins = array();
			
			$tidioPlugins[] = $pluginName;
			
		} else if($tidioPlugins){
			
			$tidioPlugins = json_decode($tidioPlugins, true);
			
			if(!self::findPlugin($pluginName, $tidioPlugins)){
				
				$tidioPlugins[] = $pluginName;
				
			}
						
		}
		
		//
		
		$tidioPlugins = json_encode($tidioPlugins);
				
		$tidioPlugins = update_option('tidio-plugins', $tidioPlugins);
				
		return true;
		
	}
	
	private static function findPlugin($pluginName, $plugins = null){
		
		if(!$plugins){
			
			$plugins = self::getPlugins();
			
		}

		foreach($plugins as $ePlugin){
				
			if($ePlugin==$pluginName){
					
				return true;
					
			}
				
		}
		
		return false;
	}
	

			
}