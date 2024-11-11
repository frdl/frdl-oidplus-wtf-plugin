<?php
/**
global functions wordpress shims
*/
namespace {	
	require_once __DIR__.\DIRECTORY_SEPARATOR.'WPHooks.class.php';
	require_once __DIR__.\DIRECTORY_SEPARATOR.'Shortcodes.class.php';
}


namespace Webfan\Patches {
//use MirazMac\Whooks\Whooks;
use Webfan\Patches\WPHooks as Hooks;

class WPHooksFunctions extends \stdclass
{
	const defined = true;
}
}//ns

namespace {	

//use Webfan\Patches\WPHooks as Hooks;
	
class WPHooksFunctions extends \stdclass
{
	const defined = true;
}	
	
 if(!function_exists('frdl_parse_mail_addresses')){	 	
function frdl_parse_mail_addresses($string){
       preg_match_all(<<<REGEXP
/(?P<email>((?P<account>[\._a-zA-Z0-9-]+)@(?P<provider>[\._a-zA-Z0-9-]+)))/xsi
REGEXP, $string, $matches, \PREG_PATTERN_ORDER);
		
		$ext = [];
		foreach($matches[0] as $k => $v){						
		//	$ext[$matches['email'][$k]] =[
			$ext[] =[
				'handle'=>$matches['email'][$k],
				'account'=>$matches['account'][$k],
				'provider'=>$matches['provider'][$k],
				
			];
		}
      return $ext;
   }		
 }	
	
 if(!function_exists('frdl_array_unflatten')){	 
	function frdl_array_unflatten(array $arr, $delimiter = '.', $depth = -1)
    {
        $output = [];
        foreach ($arr as $key => $value) {
        if(($parts = @preg_split($delimiter, $key, -1)) === false){
           //pattern is broken
          $parts = ($depth>0)?explode($delimiter, $key, $depth):explode($delimiter, $key);
           }else{
           //pattern is real

           }
        //$parts = ($depth>0)?explode($delimiter, $key, $depth):explode($delimiter, $key);
        $nested = &$output;
        while (count($parts) > 0) {
          $nested = &$nested[array_shift($parts)];
          if (!is_array($nested)) $nested = [];
        }
        $nested[array_shift($parts)] = $value;
        }
        return $output;
    }	//frdl_array_unflatten
 }	//!frdl_array_unflatten
	
	
if(!function_exists('frdl_ini_dot_parse')){	
function frdl_ini_dot_parse(string $text, ?bool $clear = false) : array {
	
	$ext = [];
	$unf = [];
    $find = "/(?P<name>[A-Z0-9\-\_\.\"\']+)(\s|\n)(\:|\=)(\s|\n)(?P<value>[^\s]+)/xs";		          
	preg_match_all($find, $text, $matches, \PREG_PATTERN_ORDER);
	
	if(true === $clear){
      while(preg_match($find, $text)) {
        $text = preg_replace($find, '', $text);
		$text = str_replace('[@]', '', $text);  
      }
	}
		
	foreach($matches[0] as $k => $v){						
		$ext[$matches['name'][$k]] = $matches['value'][$k];				
	}
				         
		            foreach($ext as $ka => $v){
						$k = explode('.', $ka, 2)[0];
					
						if(is_numeric($k) 
						    && intval($k) > 0
						  ){	 
							$unf[$ka] = $v;
						}
					}
 
		            $ext = frdl_array_unflatten($ext, '.', -1);
		
		           foreach($unf as $k => $v){ 
							$ext[$k] = $v; 
					}	      
	return ['data'=>$ext, 'content'=>$text];
}//frdlweb_ini_dot_parse
}//!frdlweb_ini_dot_parse
	
/**
 * Returns RegEx body to liberally match an opening HTML tag.
 *
 * Matches an opening HTML tag that:
 * 1. Is self-closing or
 * 2. Has no body but has a closing tag of the same name or
 * 3. Contains a body and a closing tag of the same name
 *
 * Note: this RegEx does not balance inner tags and does not attempt
 * to produce valid HTML
 *
 * @since 3.6.0
 *
 * @param string $tag An HTML tag name. Example: 'video'.
 * @return string Tag RegEx.
 */
function get_tag_regex( string $tag ) {
	if ( empty( $tag ) ) {
		return '';
	}
	return sprintf( '<%1$s[^<]*(?:>[\s\S]*<\/%1$s>|\s*\/>)', tag_escape( $tag ) );
}
/**
 * Indicates if a given slug for a character set represents the UTF-8 text encoding.
 *
 * A charset is considered to represent UTF-8 if it is a case-insensitive match
 * of "UTF-8" with or without the hyphen.
 *
 * Example:
 *
 *     true  === _is_utf8_charset( 'UTF-8' );
 *     true  === _is_utf8_charset( 'utf8' );
 *     false === _is_utf8_charset( 'latin1' );
 *     false === _is_utf8_charset( 'UTF 8' );
 *
 *     // Only strings match.
 *     false === _is_utf8_charset( [ 'charset' => 'utf-8' ] );
 *
 * `is_utf8_charset` should be used outside of this file.
 *
 * @ignore
 * @since 6.6.1
 *
 * @param string $charset_slug Slug representing a text character encoding, or "charset".
 *                             E.g. "UTF-8", "Windows-1252", "ISO-8859-1", "SJIS".
 *
 * @return bool Whether the slug represents the UTF-8 encoding.
 */
function _is_utf8_charset(string $charset_slug ) : bool {
	if ( ! is_string( $charset_slug ) ) {
		return false;
	}

	return (
		0 === strcasecmp( 'UTF-8', $charset_slug ) ||
		0 === strcasecmp( 'UTF8', $charset_slug )
	);
}
/**
 * Indicates if a given slug for a character set represents the UTF-8
 * text encoding. If not provided, examines the current blog's charset.
 *
 * A charset is considered to represent UTF-8 if it is a case-insensitive
 * match of "UTF-8" with or without the hyphen.
 *
 * Example:
 *
 *     true  === is_utf8_charset( 'UTF-8' );
 *     true  === is_utf8_charset( 'utf8' );
 *     false === is_utf8_charset( 'latin1' );
 *     false === is_utf8_charset( 'UTF 8' );
 *
 *     // Only strings match.
 *     false === is_utf8_charset( [ 'charset' => 'utf-8' ] );
 *
 *     // Without a given charset, it depends on the site option "blog_charset".
 *     $is_utf8 = is_utf8_charset();
 *
 * @since 6.6.0
 * @since 6.6.1 A wrapper for _is_utf8_charset
 *
 * @see _is_utf8_charset
 *
 * @param string|null $blog_charset Optional. Slug representing a text character encoding, or "charset".
 *                                  E.g. "UTF-8", "Windows-1252", "ISO-8859-1", "SJIS".
 *                                  Default value is to infer from "blog_charset" option.
 * @return bool Whether the slug represents the UTF-8 encoding.
 */
function is_utf8_charset(string|null  $blog_charset = null ) : bool {
	return _is_utf8_charset( $blog_charset ?? get_option( 'blog_charset' ) );
}

/**
 * Retrieves a canonical form of the provided charset appropriate for passing to PHP
 * functions such as htmlspecialchars() and charset HTML attributes.
 *
 * @since 3.6.0
 * @access private
 *
 * @see https://core.trac.wordpress.org/ticket/23688
 *
 * @param string $charset A charset name, e.g. "UTF-8", "Windows-1252", "SJIS".
 * @return string The canonical form of the charset.
 */
function _canonical_charset(string $charset ) {
	if ( is_utf8_charset( $charset ) ) {
		return 'UTF-8';
	}

	/*
	 * Normalize the ISO-8859-1 family of languages.
	 *
	 * This is not required for htmlspecialchars(), as it properly recognizes all of
	 * the input character sets that here are transformed into "ISO-8859-1".
	 *
	 * @todo Should this entire check be removed since it's not required for the stated purpose?
	 * @todo Should WordPress transform other potential charset equivalents, such as "latin1"?
	 */
	if (
		( 0 === strcasecmp( 'iso-8859-1', $charset ) ) ||
		( 0 === strcasecmp( 'iso8859-1', $charset ) )
	) {
		return 'ISO-8859-1';
	}

	return $charset;
}

/**
 * Sets the mbstring internal encoding to a binary safe encoding when func_overload
 * is enabled.
 *
 * When mbstring.func_overload is in use for multi-byte encodings, the results from
 * strlen() and similar functions respect the utf8 characters, causing binary data
 * to return incorrect lengths.
 *
 * This function overrides the mbstring encoding to a binary-safe encoding, and
 * resets it to the users expected encoding afterwards through the
 * `reset_mbstring_encoding` function.
 *
 * It is safe to recursively call this function, however each
 * `mbstring_binary_safe_encoding()` call must be followed up with an equal number
 * of `reset_mbstring_encoding()` calls.
 *
 * @since 3.7.0
 *
 * @see reset_mbstring_encoding()
 *
 * @param bool $reset Optional. Whether to reset the encoding back to a previously-set encoding.
 *                    Default false.
 */
function mbstring_binary_safe_encoding( bool $reset = false ) {
	static $encodings  = array();
	static $overloaded = null;

	if ( is_null( $overloaded ) ) {
		if ( function_exists( 'mb_internal_encoding' )
			&& ( (int) ini_get( 'mbstring.func_overload' ) & 2 ) // phpcs:ignore PHPCompatibility.IniDirectives.RemovedIniDirectives.mbstring_func_overloadDeprecated
		) {
			$overloaded = true;
		} else {
			$overloaded = false;
		}
	}

	if ( false === $overloaded ) {
		return;
	}

	if ( ! $reset ) {
		$encoding = mb_internal_encoding();
		array_push( $encodings, $encoding );
		mb_internal_encoding( 'ISO-8859-1' );
	}

	if ( $reset && $encodings ) {
		$encoding = array_pop( $encodings );
		mb_internal_encoding( $encoding );
	}
}

/**
 * Resets the mbstring internal encoding to a users previously set encoding.
 *
 * @see mbstring_binary_safe_encoding()
 *
 * @since 3.7.0
 */
function reset_mbstring_encoding() {
	mbstring_binary_safe_encoding( true );
}

/**
 * Filters/validates a variable as a boolean.
 *
 * Alternative to `filter_var( $value, FILTER_VALIDATE_BOOLEAN )`.
 *
 * @since 4.0.0
 *
 * @param mixed $value Boolean value to validate.
 * @return bool Whether the value is validated.
 */
function wp_validate_boolean( mixed $value ) : bool {
	if ( is_bool( $value ) ) {
		return $value;
	}

	if ( is_string( $value ) && 'false' === strtolower( $value ) ) {
		return false;
	}

	return (bool) $value;
}
	
	
/*!*
 * Strips close comment and close php tags from file headers used by WP.
 *
 * @since 2.8.0
 * @access private
 *
 * @see https://core.trac.wordpress.org/ticket/8497
 *
 * @param string $str Header comment to clean up.
 * @return string
 */
function _cleanup_header_comment(string $str ) : string {
	return trim( preg_replace( '/\s*(?:\*\/|\?>).*/', '', $str ) );
}
/**
File Header Examples
The following file header examples are taken out of example theme and plugin files that do ship with WordPress or are closely related to the WordPress project (Default Theme and Core Plugin):

Plugin File Header Example
This is an example for the health-check.php file, part of the Health Check plugin:

<?php
/*
Plugin Name: Health Check
Plugin URI: https://wordpress.org/plugins/health-check/
Description: Checks the health of your WordPress install
Version: 0.1.0
Author: The Health Check Team
Author URI: http://health-check-team.example.com
Text Domain: health-check
Domain Path: /languages
* /
Here's another example which allows file-level PHPDoc DocBlock as well as WordPress plugin file headers:

<?php
/**
 * Plugin Name
 *
 * @package     PluginPackage
 * @author      Your Name
 * @copyright   2019 Your Name or Company Name
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Plugin Name
 * Plugin URI:  https://example.com/plugin-name
 * Description: Description of the plugin.
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * Text Domain: plugin-slug
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * /
Theme File Header Example
These are the very first lines of a the style.css file part of the Twenty Thirteen theme:

/*
Theme Name: Twenty Thirteen
Theme URI: http://wordpress.org/themes/twentythirteen
Author: the WordPress team
Author URI: http://wordpress.org/
Description: The 2013 theme for WordPress takes us back to the blog, featuring a full range of post formats, each displayed beautifully in their own unique way. Design details abound, starting with a vibrant color scheme and matching header images, beautiful typography and icons, and a flexible layout that looks great on any device, big or small.
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: black, brown, orange, tan, white, yellow, light, one-column, two-columns, right-sidebar, flexible-width, custom-header, custom-menu, editor-style, featured-images, microformats, post-formats, rtl-language-support, sticky-post, translation-ready
Text Domain: twentythirteen

This theme, like WordPress, is licensed under the GPL.
Use it to make something cool, have fun, and share what you've learned with others.
* /
List of Header Names
The following is a list of Header-Names that are currently used by Themes and Plugins in the current concrete File Header default implementation (02 Jun 2010). Headers can be extended, so this is a subset, not the superset:

Plugin
Author (Plugin)
Author URI (Plugin)
Description (Plugin)
Domain Path (Plugin)
Network (Plugin)
Plugin Name (Plugin)
Plugin URI (Plugin)
Site Wide Only (Plugin; deprecated in favor of Network)
Text Domain (Plugin)
Version (Plugin)
Theme
Author (Theme)
Author URI (Theme)
Description (Theme)
Domain Path (Theme)
Status (Theme)
Tags (Theme)
Template (Theme)
Text Domain (Theme)
Theme Name (Theme)
Theme URI (Theme)
Version (Theme)
Page Template
Template Name
Description (cf. twentyeleven)
 * Retrieves metadata from a file.
 *
 * Searches for metadata in the first 8 KB of a file, such as a plugin or theme.
 * Each piece of metadata must be on its own line. Fields can not span multiple
 * lines, the value will get cut at the end of the first line.
 *
 * If the file data is not within that first 8 KB, then the author should correct
 * their plugin file and move the data headers to the top.
 *
 * @link https://codex.wordpress.org/File_Header
 *
 * @since 2.9.0
 *
 * @param string $file            Absolute path to the file.
 * @param array  $default_headers List of headers, in the format `array( 'HeaderKey' => 'Header Name' )`.
 * @param string $context         Optional. If specified adds filter hook {@see 'extra_$context_headers'}.
 *                                Default empty string.
 * @return string[] Array of file header values keyed by header name.
 */
function get_file_data(string $file,array $default_headers,string $context = '' ) {
	// Pull only the first 8 KB of the file in.
	// define( 'KB_IN_BYTES', 1024 );
	$file_data = file_get_contents( $file, false, null, 0, 8 * (defined('KB_IN_BYTES') ? \KB_IN_BYTES : 1024));

	if ( false === $file_data ) {
		$file_data = '';
	}

	// Make sure we catch CR-only line endings.
	$file_data = str_replace( "\r", "\n", $file_data );

	/**
	 * Filters extra file headers by context.
	 *
	 * The dynamic portion of the hook name, `$context`, refers to
	 * the context where extra headers might be loaded.
	 *
	 * @since 2.9.0
	 *
	 * @param array $extra_context_headers Empty array by default.
	 */
	$extra_headers = $context ? apply_filters( "extra_{$context}_headers", array() ) : array();
	if ( $extra_headers ) {
		$extra_headers = array_combine( $extra_headers, $extra_headers ); // Keys equal values.
		$all_headers   = array_merge( $extra_headers, (array) $default_headers );
	} else {
		$all_headers = $default_headers;
	}

	foreach ( $all_headers as $field => $regex ) {
		if ( preg_match( '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] ) {
			$all_headers[ $field ] = _cleanup_header_comment( $match[1] );
		} else {
			$all_headers[ $field ] = '';
		}
	}

	return $all_headers;
}	
	
	
	
	
/*!*
 * Hooks a function or method to a specific filter action.
 *
 * @param string $tag The name of the filter to hook the $function_to_add to.
 * @param callback $function_to_add The name of the function to be called when the filter is applied.
 * @param int $priority optional. Used to specify the order in which the functions associated with a particular
 * action are executed (default: 10). Lower numbers correspond with earlier execution,
 * and functions with the same priority are executed in the order in which they were
 * added to the action.
 * @param int $accepted_args optional. The number of arguments the function accept (default 1).
 * @return boolean true
 */
if(!function_exists('add_filter')){
function add_filter(string $tag, string | array | \callable | \Closure $function_to_add,  int | bool | null  $priority = 10, int | null $accepted_args = 1)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::addFilter($tag, $function_to_add, $priority, $accepted_args);
}
}
/*!*
 * Removes a function from a specified filter hook.
 *
 * @param string $tag The filter hook to which the function to be removed is hooked.
 * @param callback $function_to_remove The name of the function which should be removed.
 * @param int $priority optional. The priority of the function (default: 10).
 * @param int $accepted_args optional. The number of arguments the function accepts (default: 1).
 * @return boolean Whether the function existed before it was removed.
 */
if(!function_exists('remove_filter')){
function remove_filter(string $tag,  string | array | \callable | \Closure $function_to_remove, int | bool | null $priority = 10)
{
  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//	return Whooks::removeFilter($tag, $function_to_remove, $priority);
}
}
/**
 * Remove all of the hooks from a filter.
 *
 * @param string $tag The filter to remove hooks from.
 * @param int $priority The priority number to remove.
 * @return bool true when finished.
 */
if(!function_exists('remove_all_filters')){
function remove_all_filters(string $tag,  int | bool | null  $priority = false) : bool
{
  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//	return Whooks::removeAllFilters($tag, $priority);
}
}
/**
 * Check if any filter has been registered for a hook.
 *
 * @param string $tag The name of the filter hook.
 * @param callback $function_to_check optional.
 * @return mixed If $function_to_check is omitted, returns boolean for whether the hook has anything
 * registered. When checking a specific function, the priority of that hook is returned,
 * or false if the function is not attached. When using the $function_to_check argument, this
 * function may return a non-boolean value that evaluates to false (e.g.) 0, so use
 * the === operator for testing the return value.
 */
if(!function_exists('has_filter')){
function has_filter($tag, $function_to_check = false)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::hasFilter($tag, $function_to_check);
}
}
/**
 * Call the functions added to a filter hook.
 *
 * @param string $tag The name of the filter hook.
 * @param mixed $value The value on which the filters hooked to <tt>$tag</tt> are applied on.
 * @param mixed $var,... Additional variables passed to the functions hooked to <tt>$tag</tt>.
 * @return mixed The filtered value after all hooked functions are applied to it.
 */
if(!function_exists('apply_filters')){
function apply_filters(string $tag, mixed $value)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::applyFilters($tag, $value);
}
}
/**
 * Execute functions hooked on a specific filter hook, specifying arguments in an array.
 *
 * @param string $tag The name of the filter hook.
 * @param array $args The arguments supplied to the functions hooked to <tt>$tag</tt>
 * @return mixed The filtered value after all hooked functions are applied to it.
 */
if(!function_exists('apply_filters_ref_array')){
function apply_filters_ref_array(string $tag, mixed $args)
{
	  return \call_user_func_array([Hooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::applyFiltersRefArray($tag, $args);
}
}
/**
 * Hooks a function on to a specific action.
 *
 * @param string $tag The name of the action to which the $function_to_add is hooked.
 * @param callback $function_to_add The name of the function you wish to be called.
 * @param int $priority optional. Used to specify the order in which the functions associated with a
 * particular action are executed (default: 10). Lower numbers correspond with
 * earlier execution, and functions with the same priority are executed in the
 * order in which they were added to the action.
 * @param int $accepted_args optional. The number of arguments the function accept (default 1).
 * @return mixed
 */
if(!function_exists('add_action')){
function add_action(string $tag, string | array | \callable | \Closure $function_to_add, int | bool | null $priority = 10,int |null $accepted_args = 1)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::addAction($tag, $function_to_add, $priority, $accepted_args);
}
}
/**
 * Check if any action has been registered for a hook.
 *
 * @param string $tag The name of the action hook.
 * @param callback $function_to_check optional.
 * @return mixed If $function_to_check is omitted, returns boolean for whether the hook has anything
 * registered. When checking a specific function, the priority of that hook is returned,
 * or false if the function is not attached. When using the $function_to_check argument,
 * this function may return a non-boolean value that evaluates to false. (e.g.) 0, so use
 * the === operator for testing the return value.
 */
if(!function_exists('has_action')){
function has_action($tag, $function_to_check = false)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::hasAction($tag, $function_to_check);
}
}
/**
 * Removes a function from a specified action hook.
 *
 * @param string $tag The action hook to which the function to be removed is hooked.
 * @param callback $function_to_remove The name of the function which should be removed.
 * @param int $priority optional The priority of the function (default: 10).
 * @return boolean Whether the function is removed.
 */
if(!function_exists('remove_action')){
function remove_action(string $tag, string | array | \callable | \Closure  $function_to_remove,int | bool | null $priority = 10)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::removeAction($tag, $function_to_remove, $priority);
}
}
/**
 * Remove all of the hooks from an action.
 *
 * @param string $tag The action to remove hooks from.
 * @param int $priority The priority number to remove them from.
 * @return bool True when finished.
 */
if(!function_exists('remove_all_actions')){
function remove_all_actions(string $tag,int | bool | null $priority = false)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::removeAllActions($tag, $priority);
}
}
/**
 * Execute functions hooked on a specific action hook.
 *
 * @param string $tag The name of the action to be executed.
 * @param mixed $arg,... Optional additional arguments which are passed on to the
 * functions hooked to the action.
 * @return null Will return null if $tag does not exist in $filter array
 */
if(!function_exists('do_action')){
function do_action(string $tag, mixed $arg = '')
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::doAction($tag, $arg);
}
}
/**
 * Execute functions hooked on a specific action hook, specifying arguments in an array.
 *
 * @param string $tag The name of the action to be executed.
 * @param array $args The arguments supplied to the functions hooked to <tt>$tag</tt>
 * @return null Will return null if $tag does not exist in $filter array
 */
if(!function_exists('do_action_ref_array')){
function do_action_ref_array(string $tag, array $args)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::doActionRefArray($tag, $args);
}
}
/**
 * Retrieve the number of times an action is fired.
 *
 * @param string $tag The name of the action hook.
 * @return int The number of times action hook <tt>$tag</tt> is fired
 */
if(!function_exists('did_action')){
function did_action(string $tag)
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::didAction($tag);
}
}
/**
 * Retrieve the name of the current filter or action.
 *
 * @return string Hook name of the current filter or action.
 */
if(!function_exists('current_filter')){
function current_filter()
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::currentFilter();
}
}
/**
 * Retrieve the name of the current action.
 *
 * @return string Hook name of the current action.
 */
if(!function_exists('current_action')){
function current_action()
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::currentAction();
}
}
/**
 * Retrieve the name of a filter currently being processed.
 *
 * current_filter() only returns the most recent filter or action
 * being executed. did_action() returns true once the action is initially
 * processed. This function allows detection for any filter currently being
 * executed (despite not being the most recent filter to fire, in the case of
 * hooks called from hook callbacks) to be verified.
 *
 * @param null|string $filter Optional. Filter to check. Defaults to null, which
 * checks if any filter is currently being run.
 * @return bool Whether the filter is currently in the stack
 */
if(!function_exists('doing_filter')){
function doing_filter(null|string $filter = null) : bool
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::doingFilter($filter);
}
}
/**
 * Retrieve the name of an action currently being processed.
 *
 * @param string|null $action Optional. Action to check. Defaults to null, which checks
 * if any action is currently being run.
 * @return bool Whether the action is currently in the stack.
 */
if(!function_exists('doing_action')){
function doing_action(string|null $action = null) : bool
{
  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//	return Whooks::doingAction($action);
}
}
	
if(!function_exists('add_shortcode')){
function add_shortcode( )
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::doingFilter($filter);
}
}	
	
if(!function_exists('do_shortcode')){
function do_shortcode( )
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
	//return Whooks::doingFilter($filter);
}
}		
	
	
	
if(!function_exists('strip_shortcodes')){
function strip_shortcodes( )
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
}
}		
	

if(!function_exists('shortcode_atts')){
function shortcode_atts( )
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
}
}			
	

if(!function_exists('shortcode_parse_atts')){
function shortcode_parse_atts( )
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
}
}			
	
	
	


if(!function_exists('has_shortcode')){
function has_shortcode( )
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
}
}			
		
	

if(!function_exists('display_shortcodes')){
function display_shortcodes( )
{
	  return \call_user_func_array([\Webfan\Patches\WPHooks::getInstance(), __FUNCTION__], func_get_args());
}
}			
				
		
}//ns
