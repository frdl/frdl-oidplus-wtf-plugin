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

// phpcs:disable PSR1.Files.SideEffects
\defined('INSIDE_OIDPLUS') or die;
// phpcs:enable PSR1.Files.SideEffects

class OIDplusPageWTFunctions extends OIDplusPagePluginPublic 
	implements INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3
	
	/* INTF_OID_1_3_6_1_4_1_37553_8_1_8_8_53354196964_1276945,
	           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_2,//  modifyContent 
	             INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_9,  //API

                   INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_7,//public function getAlternativesForQuery(string $id): array;
	 
	           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_3, /* beforeObject*, afterObject* * /
	           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_4, /* whois*Attributes * /
	           INTF_OID_1_3_6_1_4_1_37476_2_5_2_3_8  /* getNotifications * /*/
{
	
	
	public const BODY_REPLACER = '@@@@BODYCONTENTREPLACER@@@@';			   	

	
				   
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
			   
	
	
	
    public static function objectCMSPage(string | OIDplusObject $obj, ?bool $verbose = false, ?bool $die = false, ?string $id = null){
		global $oidplus_current_object_id;
		global $oidplus_current_page_context;
		global $oidplus_current_page_verbose;
		//print_r($obj);die();
		$page  = \frdl_ini_dot_parse(is_string($obj) ? $obj : $obj->getDescription(), true);
		$page['data']['id'] = is_string($obj) ? $id : $obj->nodeId();
		//$data = $page['data']; 
		// print_r($data);die();
	 
		
		$oidplus_current_object_id = $page['data']['id'];
		$oidplus_current_page_context = $page;
		$oidplus_current_page_verbose= $verbose;
			
		$html = $page['content']; 
		
		  if(!did_action('oidplus_prepare_shortcode')){
			  do_action('oidplus_prepare_shortcode', __METHOD__);
		  }
		$html = \do_shortcode($html);		
		$page['html'] = $html;
		
		unset($oidplus_current_object_id);
		unset($oidplus_current_page_context);
		unset($oidplus_current_page_verbose);
		
		if(true === $verbose){
			$format = isset($_GET['format']) ? $_GET['format'] : 'cms';
			 
			switch($format){
				case 'json' :
					 header('Content-Type: application/json');
					 echo json_encode($page, \JSON_PRETTY_PRINT);
					break;
				case 'cms' ===$format && function_exists('\io4\container') :
					 $HtmlCompiler = \io4\container()->get('HtmlDocument') ;
					 $tpl = $HtmlCompiler->compile(static::BODY_REPLACER); 
					 $pageHTML = str_replace(static::BODY_REPLACER, $page['html'], $tpl);
					 echo $pageHTML;
					break;
				case 'cms' ===$format && !function_exists('\io4\container') :	
				case 'html' :
				case 'body' :
					default :
					  echo $page['html'] ;
					break;
			}			
		}
		if(true === $die){
			die();
		}
		return $page;
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
           }, [	__DIR__.\DIRECTORY_SEPARATOR.'WPHooksFunctions.class.php']));		

		
		  if(!$isWPHooksFunctionsInstalled){
			 throw new \Exception('Could not init wp-functions-shim in '.__METHOD__.' '.__LINE__);  
		  }
		
		
		
		$isFunctionsInstalled 
		   = (//true === @\WPHooksFunctions::defined ||
			  function_exists('oidplus_quota_used_db') ||
			  \call_user_func_array(function(string $file){
	  	            require_once $file;
              return function_exists('oidplus_quota_used_db');	 
           }, [	__DIR__.\DIRECTORY_SEPARATOR.'oidplus-functions.php']));		

		
		  if(!$isFunctionsInstalled){
			 throw new \Exception('Could not init oidplus-functions in '.__METHOD__.' '.__LINE__);  
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
			$file = rtrim($dir, '\\/ ').\DIRECTORY_SEPARATOR.'wtf-plugin.php';
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
			foreach(  array_merge(glob(OIDplus::getUserDataDir('', true)."/plugins/*/*/*/wtf-plugin.php"),
								 glob(OIDplus::getUserDataDir('')."/plugins/*/*/*/wtf-plugin.php")
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
		
			 
		 
	}
	
}
