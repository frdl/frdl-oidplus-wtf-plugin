<?php

/*
 * OIDplus 2.0
 * Copyright 2019 - 2023 Daniel Marschall, ViaThinkSoft
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
//"Frdlweb\\OIDplus\\Plugins\\PublicPages\\WTFunctions\\"
namespace Frdlweb\OIDplus\Plugins\PublicPages\WTFunctions;

use ViaThinkSoft\OIDplus\Core\OIDplus;
use ViaThinkSoft\OIDplus\Core\OIDplusException;
use ViaThinkSoft\OIDplus\Core\OIDplusHtmlException;
use ViaThinkSoft\OIDplus\Core\OIDplusPagePluginAdmin;
use ViaThinkSoft\OIDplus\Core\OIDplusPagePluginPublic;

// phpcs:disable PSR1.Files.SideEffects
\defined('INSIDE_OIDPLUS') or die;
// phpcs:enable PSR1.Files.SideEffects

class OIDplusPageWTFunctions extends OIDplusPagePluginPublic 
{
   public function gui(string $id, array &$out, bool &$handled): void {
	   global $oidplus_public_pages_gui_id;
       global $oidplus_public_pages_gui_out;
       global $oidplus_public_pages_gui_handled;
		
 
	      $oidplus_public_pages_gui_id = $id;
		  $oidplus_public_pages_gui_out = $out;
		  $oidplus_public_pages_gui_handled = $handled;
		  if(!did_action('oidplus_public_pages_gui')){
			  do_action('oidplus_public_pages_gui', $id);
		  }
		$out = $oidplus_public_pages_gui_out;
		$handled = (bool)$oidplus_public_pages_gui_handled === true ? true : false;
	 // unset($oidplus_public_pages_gui_out);	 
	  unset($oidplus_public_pages_gui_id);
	//	THIS BRFEAKS HOME NO !	unset($handled);
   }
		
	public function handle404(string $request): bool {
		global $oidplus_handle_404_request;
		global $oidplus_handle_404_rel_url_original;
		global $oidplus_handle_404_handled_return_value;
		
		$oidplus_handle_404_handled_return_value = false;
		$oidplus_handle_404_request = $request;
		 $oidplus_handle_404_rel_url_original = $rel_url_original;	
		  if(function_exists('did_action') && !did_action('oidplus_handle_404')){
			  do_action('oidplus_handle_404', [$rel_url_original,$request]);
		  }		
		 $request = $oidplus_handle_404_request;
		 $rel_url_original = $oidplus_handle_404_rel_url_original;
			 
		unset($oidplus_handle_404_request);
		unset($oidplus_handle_404_rel_url_original);	
		return $oidplus_handle_404_handled_return_value;
	}
			
	public function tree(array &$json, string $ra_email=null, bool $nonjs=false, string $req_goto=''): bool {
           global $oidplus_public_pages_tree_json;
 	 
		       $oidplus_public_pages_tree_json = $json;		
			  if(function_exists('did_action') && !did_action('oidplus_public_pages_tree')){		
				  do_action('oidplus_public_pages_tree', $ra_email);		
			  }			  
			  $json = $oidplus_public_pages_tree_json;
		// unset($oidplus_public_pages_tree_json);
		/*  THIS YOU HAVE TO IMPLEMENT, NOW IT IS IN IO4 WHAT IS NOT A GOOD PLACE!!!
		
		  if (OIDplus::authUtils()->isAdminLoggedIn()) {
			  $oidplus_admin_pages_tree_json = $json;		
			  if(function_exists('did_action') && !did_action('oidplus_admin_pages_tree')){		
				  do_action('oidplus_admin_pages_tree', $ra_email);		
			  }			  
			  $json = $oidplus_admin_pages_tree_json;
		  }
		*/
		return true;
	}	
	
	/**
	 * @param bool $html
	 * @return void
	 */
	public function init(bool $html=true): void {
		       
		$isWPHooksFunctionsInstalled 
		   = (//true === @\WPHooksFunctions::defined ||
			  function_exists('add_action') ||
			  \call_user_func_array(function(string $file){
	  	            require_once $file;
              return function_exists('add_action');	 
           }, [	__DIR__.\DIRECTORY_SEPARATOR.'WPHooksFunctions.class.php',
	     -1]));		

		
		  if(!$isWPHooksFunctionsInstalled){
			 throw new \Exception('Could not init wp-functions-shim in '.__METHOD__.' '.__LINE__);  
		  }
		
	    $io4Plugin = OIDplus::getPluginByOid("1.3.6.1.4.1.37476.9000.108.19361.24196");
		if (!is_null($io4Plugin) ) {
		    $Stubrunner = $io4Plugin->getWebfat(true,false);
		}else{
			$Stubrunner = null;
		} 
		//Pluginfiles
		foreach(OIDplus::getAllPlugins() as $plugin){
			$dir = $plugin->getPluginDirectory();
			$file = rtrim($dir, '\\/ ').\DIRECTORY_SEPARATOR.'plugin.php';
			if(file_exists($file)){
				//this my be different from OIDplus Plugin manifest.json, mhh...???
				$pData = \get_file_data($file, ['Name'=>'Plugin Name', 'Author'=>'Author', 'Version'=>'Version', 'License'=>'License',]);
				if(count($pData) >= 3){
					$fn = include $file;
					if(is_callable($fn)){
						if(!is_null($Stubrunner)){
							$Stubrunner->call($fn);
						}else{
						    call_user_func($fn);	
						}
					}
				}
			}
			
		}	
 
		    // userdata and tenant plugins, for OIDplus the are "anonympous"!?! 
			foreach( array_merge(array_merge(glob(OIDplus::getUserDataDir('')."/plugins/*.php"),
								 glob(OIDplus::getUserDataDir('')."/plugins/*/plugin.php")
					), array_merge(glob(OIDplus::getUserDataDir('wtf-plugins')."*.php"),
								 glob(OIDplus::getUserDataDir('wtf-plugins')."/*/plugin.php")
					)) as $file){
				$pData = \get_file_data($file, ['Name'=>'Plugin Name', 'Author'=>'Author', 'Version'=>'Version', 'License'=>'License',]);
				if(count($pData) >= 3){
					$fn = include $file;
					if(is_callable($fn)){			
						if(!is_null($Stubrunner)){
							$Stubrunner->call($fn);
						}else{
						    call_user_func($fn);	
						}
					}
				}				
			}		
		
			foreach( array_merge(array_merge(glob(OIDplus::getUserDataDir('', true)."/plugins/*.php"),
								 glob(OIDplus::getUserDataDir('', true)."/plugins/*/plugin.php")
					), array_merge(glob(OIDplus::getUserDataDir('wtf-plugins', true)."*.php"),
								 glob(OIDplus::getUserDataDir('wtf-plugins', true)."/*/plugin.php")
					)) as $file){
				$pData = \get_file_data($file, ['Name'=>'Plugin Name', 'Author'=>'Author', 'Version'=>'Version', 'License'=>'License',]);
				if(count($pData) >= 3){
					$fn = include $file;
					if(is_callable($fn)){			
						if(!is_null($Stubrunner)){
							$Stubrunner->call($fn);
						}else{
						    call_user_func($fn);	
						}
					}
				}				
			}	
		
	}
	
}
