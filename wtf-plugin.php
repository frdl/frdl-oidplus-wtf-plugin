<?php
/**
 * Plugin Name: WTF 
 * Description: This is an example OIDplus/WTF-Plugin aware and enabled Plugin.
 * Version: 0.0.1
 * Author: Frdlweb
 * Author URI: https://frdl.de
 * License: MIT
 */


namespace Frdlweb\OIDplus\WTFunctions\plugin{
	
use ViaThinkSoft\OIDplus\Core\OIDplus;	
use ViaThinkSoft\OIDplus\Core\OIDplusConfig;	
use ViaThinkSoft\OIDplus\Core\OIDplusException;	
use ViaThinkSoft\OIDplus\Core\OIDplusObject;	
use ViaThinkSoft\OIDplus\Core\OIDplusPagePluginPublic;	
use ViaThinkSoft\OIDplus\Core\OIDplusPagePluginRa;	
use ViaThinkSoft\OIDplus\Core\OIDplusPlugin;   
use Frdlweb\OIDplus\Plugins\AdminPages\IO4\OIDplusPagePublicIO4;


 \defined('INSIDE_OIDPLUS') or die;


 
 function refresh_header_shortcode($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'url' => OIDplus::webpath(null, OIDplus::PATH_RELATIVE_TO_ROOT).'?goto=oidplus:system',
            'refresh' => 5,
			'title'=>'Go to...',
			'class'=>'btn btn-primary',
        ),
        $atts
    );

 
     header(sprintf('Refresh:%2$d; url=%1$s' , $atts['url'], $atts['refresh']));
    return sprintf('<a href="%1$s" title="%2$s" class="%4$s">%3$s</a>', $atts['url'], $atts['title'], $atts['title'], $atts['class']);
}

 function object_repository_link($atts) {
    global $oidplus_current_page_context;
	 
    $atts = shortcode_atts(
        array(
            'url' => OIDplus::webpath(null, OIDplus::PATH_ABSOLUTE_CANONICAL).'?goto='.urlencode($oidplus_current_page_context['data']['id']),
			'title'=>'Link to this page in the repository',
			'class'=>'btn btn-link',
        ),
        $atts
    );

  
    return sprintf('<a href="%1$s" title="%2$s" class="%4$s">%3$s</a>', $atts['url'], $atts['title'], $atts['title'], $atts['class']);
}
	
 function iframe_shortcode($atts) {
    global $oidplus_current_page_context;
	 
    $atts = shortcode_atts(
        array(
            'url' => 'https://rdap.frdlweb.de/',
			'width'=>'100%',
			'height'=>'640',
        ),
        $atts
    );

  
    return sprintf('<iframe src="%1$s" width="%2$s" height="%3$s" style="border:none;"></iframe>', 
				   $atts['url'], $atts['width'], $atts['height']);
}
	

	
function modifyContent(string $id) {
	    global $oidplus_content_title;
	    global $oidplus_content_icon;
	    global $oidplus_content_text;	
	
	   $CRUD = '';
	
	    $obj = OIDplusObject::parse($id);
	
		if($obj){
					    $data = \frdl_ini_dot_parse($obj->getDescription())["data"];
					    $data_json_string = json_encode($data, \JSON_PRETTY_PRINT);
					    $content_display_data = '<pre>'.$data_json_string.'</pre>';
			
			           $CRUD.= '<legend>Data from 
					    <a href="'
						   .OIDplus::baseConfig()->getValue('RDAP_ROOT_CLIENT_SERVER', 'https://oid.zone/rdap/')
							   .'oid/1.3.6.1.4.1.37476.9000.108.1276945.19361.24174" target="_blank">
					    	1.3.6.1.4.1.37476.9000.108.1276945.19361.24174
						</a>
					   </legend>';
			           $CRUD.= $content_display_data;
			
			  switch($obj::ns()){
				  case 'ipv4' :
				  case 'ipv6' :
					  //   putenv('IO4_WORKSPACE_SCOPE=@global');
					  //   putenv('IO4_WORKSPACE_SCOPE=@www');
					  //   putenv('IO4_WORKSPACE_SCOPE=@cwd');
					  //   $content.= //getenv('IO4_WORKSPACE_SCOPE').
						//	 'Please note that the IPs listed here are for internal use and'
						// .' may NOT be accessable via the REAL-IP in the internet as you might expect';
					  break;
				  default:
					    
					  break;
			  }
			
		
		}
		
	 


		$oidplus_content_text = (false === strpos($oidplus_content_text, '%%CRUD%%'))
			? $oidplus_content_text . $CRUD 
			: str_replace('%%CRUD%%', \PHP_EOL . $CRUD . \PHP_EOL . '%%CRUD%%', $oidplus_content_text);

}//modifyContent


	
	
function prepare_shortcode(){
	add_shortcode('IncludeFrame', __NAMESPACE__.'\iframe_shortcode');
	add_shortcode('RefreshHeader', __NAMESPACE__.'\refresh_header_shortcode');    
	add_shortcode('ObjectRepositoryLink', __NAMESPACE__.'\object_repository_link');    
	add_shortcode('ListAllShortcodes', '\display_shortcodes');
}	
	

	
//you can use autowiring as from container->invoker->call( \callable | closure(autowired arguments), [parameters]) !!!
return (function( ){
 add_action(	'oidplus_prepare_shortcode',	__NAMESPACE__.'\prepare_shortcode',	0, null);	 
 add_action(	'oidplus_modifyContent',	__NAMESPACE__.'\modifyContent',	0, null);	 
});
	
}//namespace of the plugin
