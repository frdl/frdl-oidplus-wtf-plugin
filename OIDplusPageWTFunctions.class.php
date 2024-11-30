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
use ViaThinkSoft\OIDplus\Core\OIDplusObject;
use ViaThinkSoft\OIDplus\Plugins\PublicPages\Objects\INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3;
use ViaThinkSoft\OIDplus\Plugins\PublicPages\Whois\INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_4;
use ViaThinkSoft\OIDplus\Plugins\PublicPages\Objects\INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_2;
use ViaThinkSoft\OIDplus\Plugins\AdminPages\Notifications\INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_8;
use ViaThinkSoft\OIDplus\Plugins\PublicPages\RestApi\INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_9;
use Frdlweb\OIDplus\Plugins\PublicPages\RDAP\INTF_OID_1_3_6_1_4_1_37553_8_1_8_8_53354196964_1276945;
use ViaThinkSoft\OIDplus\Plugins\PublicPages\Attachments\INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_11;
// phpcs:disable PSR1.Files.SideEffects
\defined('INSIDE_OIDPLUS') or die;
// phpcs:enable PSR1.Files.SideEffects

class OIDplusPageWTFunctions extends OIDplusPagePluginPublic 
	implements INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3,/* beforeObject*, afterObject* */
           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_2, //  modifyContent 
	       INTF_OID_1_3_6_1_4_1_37553_8_1_8_8_53354196964_1276945, // rdapExtensions
           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_11  //attachments: befor* after*

	/* INTF_OID_1_3_6_1_4_1_37553_8_1_8_8_53354196964_1276945,
	           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_2,//  modifyContent 
	             INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_9,  //API

                   INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_7,//public function getAlternativesForQuery(string $id): array;
	 
	           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3, /* beforeObject*, afterObject* * /
	           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_4, /* whois*Attributes * /
	           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_8  /* getNotifications * /*/
{

  public function beforeAttachmentUpload(string $id, string $filename_relative, array $file_data): void {
      $filter = 'oidplus_'.__FUNCTION__;
	  $args = func_get_args();
	  if(!did_filter($filter)){
	     $args = apply_filters( $filter, $args, $id, $filename_relative, $file_data );
	  }	  		  
  }
			   
  public function afterAttachmentUpload(string $id, string $filename_relative, array $file_data): void {
      $filter = 'oidplus_'.__FUNCTION__;
	  $args = func_get_args();
	  if(!did_filter($filter)){
	     $args = apply_filters( $filter, $args, $id, $filename_relative, $file_data );
	  }	  	  
  }
			   
  public function beforeAttachmentDelete(string $id, string $filename_relative): void {
      $filter = 'oidplus_'.__FUNCTION__;
	  $args = func_get_args();
	  if(!did_filter($filter)){
	     $args = apply_filters( $filter, $args, $id, $filename_relative );
	  }	  	  
  }
			   
  public function afterAttachmentDelete(string $id, string $filename_relative): void {
      $filter = 'oidplus_'.__FUNCTION__;
	  $args = func_get_args();
	  if(!did_filter($filter)){
	     $args = apply_filters( $filter, $args, $id, $filename_relative );
	  }	  	  
  }
			   
  public function beforeAttachmentDownload(string $id, string $filename_relative): void {
      $filter = 'oidplus_'.__FUNCTION__;
	  $args = func_get_args();
	  if(!did_filter($filter)){
	     $args = apply_filters( $filter, $args, $id, $filename_relative );
	  }	  
  }
			   			   
			   
  public function rdapExtensions(array $out, string $namespace, string $id, $obj, string $query) : array {
       $filter = 'oidplus_'.__FUNCTION__;
	  if(!did_filter($filter)){
	     $out = apply_filters( $filter, $out, $namespace, $id, $obj, $query );
	  }
	  return $out;
  }	
	 				   
  public function beforeObjectDelete(string $id): void {
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
  }

	/**
	 * Implements interface INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3
	 * @param string $id
	 * @return void
	 * @throws OIDplusException
	 */
	public function afterObjectDelete(string $id): void {
 	 		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
	}	
	
				   
		   
	public function modifyContent($id, &$title, &$icon, &$text): void {
	    global $oidplus_content_title;
	    global $oidplus_content_icon;
	    global $oidplus_content_text;
		
		$oidplus_content_title = ''.$title;
		$oidplus_content_icon = ''.$icon;
		$oidplus_content_text = ''.$text;
		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
		
		$title = ''.$oidplus_content_title;	
		$icon = ''.$oidplus_content_icon;	
		$text = ''.$oidplus_content_text;
		
		unset($oidplus_content_title);
		unset($oidplus_content_icon);
		unset($oidplus_content_text);		
	}
	
	/**
	 * Implements interface INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3
	 * @param string $id
	 * @param array $params
	 * @return void
	 */
	public function beforeObjectUpdateSuperior(string $id, array &$params): void {
	    global $oidplus_current_object_params;
		
		$oidplus_current_object_params = $params;
		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
		
		$params = $oidplus_current_object_params;	
	}

	/**
	 * Implements interface INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3
	 * @param string $id
	 * @param array $params
	 * @return void
	 */
	public function afterObjectUpdateSuperior(string $id, array &$params): void {
	    global $oidplus_current_object_params;
		
		$oidplus_current_object_params = $params;
		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
		
		$params = $oidplus_current_object_params;	
	}

	/**
	 * Implements interface INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3
	 * @param string $id
	 * @param array $params
	 * @return void
	 */
	public function beforeObjectUpdateSelf(string $id, array &$params): void {
	    global $oidplus_current_object_params;
		
		$oidplus_current_object_params = $params;
		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
		
		$params = $oidplus_current_object_params;	
	}

	/**
	 * Implements interface INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3
	 * @param string $id
	 * @param array $params
	 * @return void
	 */
	public function afterObjectUpdateSelf(string $id, array &$params): void {
	    global $oidplus_current_object_params;
		
		$oidplus_current_object_params = $params;
		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
		
		$params = $oidplus_current_object_params;	
	}

	/**
	 * Implements interface INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3
	 * @param string $id
	 * @param array $params
	 * @return void
	 */
	public function beforeObjectInsert(string $id, array &$params): void {
	    global $oidplus_current_object_params;
		
		$oidplus_current_object_params = $params;
		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
		
		$params = $oidplus_current_object_params;	
	}

	/**
	 * Implements interface INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3
	 * @param string $id
	 * @param array $params
	 * @return void
	 */
	public function afterObjectInsert(string $id, array &$params): void {
	    global $oidplus_current_object_params;
		
		$oidplus_current_object_params = $params;
		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			  do_action($action, $id);
		  }		
		
		$params = $oidplus_current_object_params;
	}
			   
	
	

	
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
		global $oidplus_handle_404_handled_return_value;
		
		$oidplus_handle_404_handled_return_value = false;
		
		$action = 'oidplus_'.__FUNCTION__;
		  if(!did_action($action)){
			 // do_action('oidplus_handle_404', [$rel_url_original,$request]);
			  do_action($action, $request);
		  }		
		// $request = $oidplus_handle_404_request;
		// $rel_url_original = $oidplus_handle_404_rel_url_original;
			 
		//unset($oidplus_handle_404_request);
		//unset($oidplus_handle_404_rel_url_original);	
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
		
		require_once __DIR__.\DIRECTORY_SEPARATOR.'load-functions.inc.php';
		require_once __DIR__.\DIRECTORY_SEPARATOR.'hook-functions.inc.php';
		require_once __DIR__.\DIRECTORY_SEPARATOR.'oidplus-functions.inc.php';
		require_once __DIR__.\DIRECTORY_SEPARATOR.'cache-functions.inc.php';
/*
			class_alias(wpdb::class, \wpdb::class);	 
			class_alias(PDO_Engine::class, \PDO_Engine::class);	 
			class_alias(pdo_db::class, \pdo_db::class);

		
			class_alias(WP_Object_Cache::class, \WP_Object_Cache::class);		
*/	 		

		

		
			
	    $io4Plugin = OIDplus::getPluginByOid("1.3.6.1.4.1.37476.9000.108.19361.24196");
		if (!is_null($io4Plugin) && is_callable([$io4Plugin, 'getWebfat'])) {
		    $Stubrunner =\call_user_func_array([$io4Plugin, 'getWebfat'], [true,false]);  
		}else{
			$Stubrunner = null;
		} 
		
		
		//Pluginfiles
		
		//For use in the "Standard-"-OIDplus plugins-structure
		foreach(OIDplus::getAllPlugins() as $plugin){
			$dir = $plugin->getPluginDirectory();
			$file = rtrim($dir, '\\/ ').\DIRECTORY_SEPARATOR.'wtf-plugin.php';
			if(file_exists($file)){
				//this my be different from OIDplus Plugin manifest.json, mhh...???
				//YES, as they must be pluggable without heavy metadata regsitering steps overhead for developing small 
				//addon functions
				//AND the hooks are meant NOT to be bound to a specific module BUT to be hooked by EVERY OTHER module
				//cross-plugin hookabillity
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
 
		    /*
			  ToDo:
			     - use DIrectoryIterators instead of glob!
			     - initially, periodically or by setup/update cache the list of
			       pluginfiles in file or db
			     - to the cached list/table add the feature to 
				- disable/enable plugins
			        - sort order/priority
			*/
		    // userdata and tenant plugins, for OIDplus the are "anonympous"!?! 
			foreach(  array_merge(
				                 //Central-/Tenant Domain Plugins with the "Standard-"-OIDplus plugins-structure
								 glob(OIDplus::getUserDataDir('')."/plugins/*/*/*/wtf-plugin.php"),
				                 //Public Central-/Tenant Domain Plugins with the "Standard-"-OIDplus plugins-structure
				                 glob(OIDplus::getUserDataDir('', true)."/plugins/*/*/*/wtf-plugin.php"),
				
				                 // Custom Plugins to be edited by Webmaster 
				                 // or häkisch external developers
				                 // unofficial private plugins
				
				                // e.g. userdata/plugins-wtf-custom/example-plugin.inc.php
								 glob("userdata/plugins-wtf-custom/*.php"), 
				
				                 // e.g. userdata/plugins-wtf-custom/example-plugin/wtf-plugin.php
								 glob("userdata/plugins-wtf-custom/*/wtf-plugin.php"),
				
				                 // e.g. userdata/plugins-wtf-custom/frdl/wtfPlugins/example/wtf-plugin.php
								 glob("userdata/plugins-wtf-custom/*/*/*/wtf-plugin.php")
					)  as $file){
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
		
		
		//before do actions init cache:	
		wp_cache_init();
		
		//run the plugins init hooks
		do_action('frdl_wtf_init', $html); 
	}
	
}
