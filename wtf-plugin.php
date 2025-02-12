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
			            $description = $obj->getDescription();
					    $data = is_string($description) && !empty($description)
							      ? \frdl_ini_dot_parse($description)["data"]
							      : [];
					    $data_json_string = json_encode($data, \JSON_PRETTY_PRINT);
					    $content_display_data = '<pre title="click to expand" style="max-height:120px;overflow:auto;" onclick="this.style.maxHeight=\'95%\';this.setAttribute(\'title\', \'data from specification 1.3.6.1.4.1.37476.9000.108.1276945.19361.24174\');">'.$data_json_string.'</pre>';
			
			           $CRUD.= '<legend>Data as from 
					    <a href="'
						   .OIDplus::baseConfig()->getValue('RDAP_ROOT_CLIENT_SERVER', 'https://oid.zone/rdap/')
							   .'oid/1.3.6.1.4.1.37476.9000.108.1276945.19361.24174" target="_blank">
					    	INI-Parse Description (RDAP-EXT)
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
			? (count((array)$data)>0?$CRUD .$oidplus_content_text:$oidplus_content_text.$CRUD)
			: str_replace('%%CRUD%%', \PHP_EOL . $CRUD . \PHP_EOL . '%%CRUD%%', $oidplus_content_text);

}//modifyContent


	
	


function handle404_for_permalinks(string $request){ 
	global $oidplus_handle_404_handled_return_value;
	
	$permaBaseUri = '/'. trim(OIDplus::baseConfig()->getValue('CUSTOM_BASE_URI_PERMALINKS_REDIRECT', 'p'), '/ ').'/';
    $permalinkData = false;
	
	if (str_starts_with($request,$permaBaseUri)) {
	   $uri = substr($request, strlen($permaBaseUri));
		$parts = explode('/', $uri);
		$pCount = count($parts);
		
					  
		switch($pCount){
				  case 0 :
				       		   throw new OIDplusException(sprintf('%s is not supported yet in function %s!', 
											  'Custom permalinks', 
											  __FUNCTION__), 
									          'Unsupported permalink type (custom/hash/token)',
									          409);
				   break;
				  case 2 :
					   $data = oidplus_rdap_root_request($parts[0], $parts[1], 300);
				       $permalinkData = $data;
				       if(isset($data->frdlweb_ini_dot) ){
						   $permalinkData = $data->frdlweb_ini_dot;
					   }
				       if(isset($data->frdlweb_ini_dot) 
						  && isset($data->frdlweb_ini_dot->AS)
						  && isset($data->frdlweb_ini_dot->AS->SERVICE)
						  && isset($data->frdlweb_ini_dot->AS->SERVICE->PERMALINK)
						 
						 ){
						 	$permalinkData =(array)$data->frdlweb_ini_dot->AS->SERVICE->PERMALINK;
						   $permalinkData =(array)$permalinkData;
						   foreach($permalinkData as $key=>$value){
							   if(!is_string($permalinkData[$key])){
								    foreach($permalinkData[$key] as $key2=>$value2){
										$permalinkData[$key] = $value2;
										break;
									}
							   }
						   }
						    if(isset($permalinkData['HOST']) && isset($permalinkData['URI']) && isset($permalinkData['HASH']) ){
								$link = 'https://'.trim($permalinkData['HOST'], '\'"')
									.trim($permalinkData['URI'], '\'"').'#'
									.str_replace("'", "\'", trim($permalinkData['HASH'], '\'"')); 
								die(sprintf('
								<script>
                                   window.location.href=\'%s\';
								</script> 
								<a href="%s">%s</a>
								', $link,addslashes($link),  'Goto: '.$link));								
							}elseif(isset($permalinkData['HOST']) && isset($permalinkData['URI'])){
								$link = 'https://'.trim($permalinkData['HOST'], '\'"').trim($permalinkData['URI'], '\'"');
								header('Location: '.$link, 302);
								die('<a href="'.$link.'">Goto: '.$link.'</a>');
							}
					   }
					  break;
				  default:
			       		   throw new OIDplusException(sprintf('permalinks with %d parameters is not supported yet in function %s!', 
											  $pCount, 
											  __FUNCTION__), 
									          'Unsupported permalink type',
									          409);					    
					  break;
			  }
       die($uri.' does not define a valid permalink structure! See how it works in <a href="https://github.com/search?q=repo%3Afrdl/frdl-oidplus-wtf-plugin%20handle404_for_permalinks&type=code" target="_blank">Code</a> and <a href="https://registry.frdl.de/?goto=uri%3A%2F%2Ftest%2Fpermalink-test.html" target="_blank">Repository</a> and <a href="https://oid.zone/p/oid/1.3.6.1.4.1.37476.30.9.1530250353.40115445" target="_blank"><strong>live</strong></a> Examples.
	     <br />
		  (Permalink-)data:<pre>'.json_encode($permalinkData, \JSON_PRETTY_PRINT).'</pre>
	   ');
		$oidplus_handle_404_handled_return_value = true;
	}//base uri match
	
}//handle404_for_permalinks
	
	
	
//MUST BE FIRST FILTER (priority=0)	
function rdapExtensions_frdlweb_ini_dot(array $out, string $namespace, string $id, $obj, string $query) : array {
		$ns = $namespace;
		$n = [
			$namespace,
			$id,
		];
		
		 
		if ( !is_null(OIDplus::getPluginByOid("1.3.6.1.4.1.37476.2.5.2.4.1.100"))) { // OIDplusPagePublicWhois
		
			// DM 03.08.2024 : Code moved to OID-IP plugin (SVN Rev 1540)
		}else{
		   //no oidplus_oidip plugin
			$out['remarks'][] = [
				"title" => "Availability",
				"description" => [
					sprintf("The %s %s is MISSING.", 'OID-IP Result from RDAP-plugin', "1.3.6.1.4.1.37476.2.5.2.4.1.100"),
				],
				"links"=> []
			];
			//$out['oidplus_oidip'] = false;
		}	
		//oidplus_oidip
		
				
		                 
						$description =strip_tags($obj->getDescription()); 	 
							  
	       $ext = frdl_ini_dot_parse($description)['data'];
		
			
		$out['remarks'][] = [
				"title" => "frdlweb_ini_dot is available",
				"description" => [
					sprintf("Additional %s from %s was added.", 'RDAP-extension Result', "1.3.6.1.4.1.37476.9000.108.1276945.19361.24174.17741"),
				],
				"links" => [					
								
					[
						"href"=> 'https://hosted.oidplus.com/viathinksoft/?goto=oid%3A1.3.6.1.4.1.37476.9000.108.1276945.19361.24174.17741',
							"type"=> "text/plain",
							"title"=> sprintf("frdlweb_ini_dot for the %s %s (Plaintext)", $ns, $n[1]),
							"value"=> 'https://hosted.oidplus.com/viathinksoft/?goto=oid%3A1.3.6.1.4.1.37476.9000.108.1276945.19361.24174.17741',
							"rel"=> "help"
						],		
				],				
		];
				
		    $out['frdlweb_ini_dot'] = $ext;
			$out['rdapConformance'][]='frdlweb_ini_dot'; 
 return $out;
}//RDAP extensions frdlweb_ini_dot  			   
	
	
function rdapExtensions_redirect_with_content(array $out, string $namespace, string $id, $obj, string $query) : array {
		$ns = $namespace;
		$n = [
			$namespace,
			$id,
		];
		
		 		
		//redirect_with_content
		$out['rdapConformance'][]='redirect_with_content'; 
		if(isset($out['frdlweb_ini_dot']['RDAP'])){
			  if(isset($out['frdlweb_ini_dot']['RDAP']['URL']['AUTHORITATIV'])
			//	 && isset($out['frdlweb_ini_dot']['RDAP']['SYSTEM']) 
				){
				  $url = rtrim( $out['frdlweb_ini_dot']['RDAP']['URL']['AUTHORITATIV'],'/ ').'/';
				  $url.= $ns.'/';
				  $url.= $id;			 
				  $headers = @get_headers($url);                
				  $exists = (bool) strpos($headers[0], '200');  
				  if($exists){
					  $out['redirect_with_content'] = $url;
				  }
			  }
		}//$out['frdlweb_ini_dot']['RDAP']
 return $out;
}//RDAP extensions redirect_with_content  
	
	
	
	
function prepare_shortcode(){
	add_shortcode('IncludeFrame', __NAMESPACE__.'\iframe_shortcode');
	add_shortcode('RefreshHeader', __NAMESPACE__.'\refresh_header_shortcode');    
	add_shortcode('ObjectRepositoryLink', __NAMESPACE__.'\object_repository_link');    
	add_shortcode('ListAllShortcodes', '\display_shortcodes');
}//prepare_shortcode	
		
	
	
	
function init(bool $html = true){
	
}
	
//you can use autowiring as from container->invoker->call( \callable | closure(autowired arguments), [parameters]) !!!
return (function( ){
 add_action(	'frdl_wtf_init',	__NAMESPACE__.'\init',	0, null);
 add_action(	'oidplus_prepare_shortcode',	__NAMESPACE__.'\prepare_shortcode',	0, null);
	
 add_action(	'oidplus_modifyContent',	__NAMESPACE__.'\modifyContent',	0, null);	 	 
 add_action(	'oidplus_handle404',	__NAMESPACE__.'\handle404_for_permalinks',	10, null);	 
	
	 
 add_filter( 'oidplus_rdapExtensions', __NAMESPACE__.'\rdapExtensions_frdlweb_ini_dot', 0,  5 );
 add_filter( 'oidplus_rdapExtensions', __NAMESPACE__.'\rdapExtensions_redirect_with_content', 1,  5 );	
});
	
}//namespace of the plugin
