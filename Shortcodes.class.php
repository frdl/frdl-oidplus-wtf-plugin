<?php
/**
 * Author: Frdlweb
 * Author URI: https://frdl.de
 * License: MIT
 */
namespace Frdlweb\OIDplus\Plugins\PublicPages\WTFunctions; 
//namespace Webfan;

use Frdlweb\OIDplus\Plugins\PublicPages\WTFunctions\WPHooks as Hooks;
/**
 * This is a port of WordPress' brilliant shortcode feature
 * for use outside of WordPress. The code has remained largely unchanged
 *
 * Class Shortcodes
 *
 * @package Shortcodes
 * https://github.com/Badcow/Shortcodes
 *
 * Extended by Melanie Wehowski, Frdlweb/Webfan <https://frdl.de>
 */
class Shortcodes
{
	
	
	protected $Hooks = null;
  //Huggable Autoloader is fuckable autoloader: public function __construct(?Hooks $Hooks = null)
 	public function __construct($Hooks = null)
    {
	  $this->Hooks = $Hooks;
   }
	
	
	public function hook(?Hooks $Hooks = null){
		if(!is_null($Hooks)){
			 $this->Hooks = $Hooks;
		}
		
		 if(is_null($this->Hooks)){
			 $this->Hooks =  Hooks::getInstance($this);
		 }
		return $this->Hooks;
	}

	/*	
	public function __call($method, $params)
    {
       return \call_user_func_array([$this->hook(), $method], $params);
    }	
   */
    /**
     * The regex for attributes.
     *
     * This regex covers the following attribute situations:
     *  - key = "value"
     *  - key = 'value'
     *  - key = value
     *  - "value"
     *  - value
     *
     * @var string
     */
    protected $attrPattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';

    /**
     * Indexed array of tags: shortcode callbacks
     *
     * @var array
     */
    protected $shortcodes = array();

	/**
	// [bartag foo="foo-value"]
function bartag_func( $atts ) {
	$a = shortcode_atts( array(
		'foo' => 'something',
		'bar' => 'something else',
	), $atts );

	return "foo = {$a['foo']}";
}
add_shortcode( 'bartag', 'bartag_func' );
*/
  public function shortcode_atts( $pairs, $atts, $shortcode = '' ) {
	$atts = (array) $atts;
	$out  = array();
	foreach ( $pairs as $name => $default ) {
		if ( array_key_exists( $name, $atts ) ) {
			$out[ $name ] = $atts[ $name ];
		} else {
			$out[ $name ] = $default;
		}
	}

	if ( $shortcode ) {
		/**
		 * Filters shortcode attributes.
		 *
		 * If the third parameter of the shortcode_atts() function is present then this filter is available.
		 * The third parameter, $shortcode, is the name of the shortcode.
		 *
		 * @since 3.6.0
		 * @since 4.4.0 Added the `$shortcode` parameter.
		 *
		 * @param array  $out       The output array of shortcode attributes.
		 * @param array  $pairs     The supported attributes and their defaults.
		 * @param array  $atts      The user defined shortcode attributes.
		 * @param string $shortcode The shortcode name.
		 */
		$out =$this->hook()->apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
	}

	return $out;
  }	
	
	
	public function add_shortcode($tag, $function)
    {
       return $this->addShortcode($tag, $function);
    }
	
	public function remove_shortcode($tag)
    {
       return $this->removeShortcode($tag);
    }

	public function remove_all_shortcodes(): bool 
	{ 
      $this->shortcodes = [];

      return true;
    }	
	
    /**
     * @param string $tag
     * @param callable $function
     * @throws \ErrorException
     */
    public function addShortcode($tag, $function)
    {
        if (!is_callable($function)) {
            throw new \ErrorException("Function must be callable");
        }

        $this->shortcodes[$tag] = $function;
	 return $this;
    }

    /**
     * @param string $tag
     */
    public function removeShortcode($tag)
    {
        if (array_key_exists($tag, $this->shortcodes)) {
            unset($this->shortcodes[$tag]);
        }
	 return $this;
    }

    /**
     * @return array
     */
    public function getShortcodes()
    {
        return $this->shortcodes;
    }

	public function display_shortcodes($atts){ 
		
		   
	 $atts = shortcode_atts(
        array(
            'headline' =>'View All Available Shortcodes',
            'description' => 'This page will display all of the available shortcodes that you can use on this site.', 
        ),
        $atts
    );
		
		
		ob_start();
        ?>
        <div class="wrap">
        	<div class="icon32"><br></div>
			<h2><?php echo $atts['headline']; ?></h2>
			<div class="section panel">
				<p><?php echo $atts['description']; ?></p>
        	<table class="widefat importers">
        		<tr><td><strong>Shortcodes</strong></td></tr>
        <?php

	        foreach($this->getShortcodes() as $code => $function)
	        {
	        	?>
	        		<tr><td>[<?php echo $code; ?>]</td></tr>
	        	<?php
	        }
	    ?></table>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
    /**
     * @param $shortcode
     * @return bool
     */
    public function hasShortcode(string $content, string $tag): bool
  {
	 return $this->contentHasShortcode($content, $tag);
  }
 
	public function shortcode_exists(string $tag): bool
	{
      return array_key_exists($tag, $this->shortcodes);
	}
	public function shortcodeExists(string $tag): bool
	{
       return $this->shortcode_exists($tag);
	}	
	
    /**
     * Tests whether content has a particular shortcode
     *
     * @param $content
     * @param $tag
     * @return bool
     */
  public function has_shortcode(string $content, string $tag): bool
  {
	 return $this->contentHasShortcode($content, $tag);
  }
	
    public function contentHasShortcode(string $content, string $tag): bool
    {
        if (!$this->hasShortcode($tag)) {
            return false;
        }

        preg_match_all($this->shortcodeRegex(), $content, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return false;
        }

        foreach ($matches as $shortcode) {
            if ($tag === $shortcode[2]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Search content for shortcodes and filter shortcodes through their hooks.
     *
     * If there are no shortcode tags defined, then the content will be returned
     * without any filtering. This might cause issues when plugins are disabled but
     * the shortcode will still show up in the post or content.
     *
     * @param string $content Content to search for shortcodes
     * @return string Content with shortcodes filtered out.
     */
    public function process($content)
    {
        if (empty($this->shortcodes)) {
            return $content;
        }

        return preg_replace_callback($this->shortcodeRegex(), array($this, 'processTag'), $content);
    }
   public function do_shortcode(string $content): string
   {
	   return $this->process($content);
   }
    /**
     * Remove all shortcode tags from the given content.
     *
     * @uses $shortcode_tags
     *
     * @param string $content Content to remove shortcode tags.
     * @return string Content without shortcode tags.
     */
    public function stripAllShortcodes($content)
    {
        if (empty($this->shortcodes)) {
            return $content;
        }

        return preg_replace_callback($this->shortcodeRegex(), array($this, 'stripShortcodeTag'), $content);
    }
  public function strip_shortcodes(string $content) 
  {
	 return $this->stripAllShortcodes($content);
  }
    /**
     * Regular Expression callable for do_shortcode() for calling shortcode hook.
     *
     * @see get_shortcode_regex for details of the match array contents.
     *
     * @param array $tag Regular expression match array
     * @return mixed False on failure.
     */
    protected function processTag(array $tag)
    {
        // allow [[foo]] syntax for escaping a tag
        if ($tag[1] == '[' && $tag[6] == ']') {
            return substr($tag[0], 1, -1);
        }

        $tagName = $tag[2];
        $attr = $this->parseAttributes($tag[3]);

        if (isset($tag[5])) {
            // enclosing tag - extra parameter
            return $tag[1] . call_user_func($this->shortcodes[$tagName], $attr, $tag[5], $tagName) . $tag[6];
        } else {
            // self-closing tag
            return $tag[1] . call_user_func($this->shortcodes[$tagName], $attr, null, $tagName) . $tag[6];
        }
    }

	
	  
	public function shortcode_parse_atts(string $text): array
    {
	  	    return \call_user_func_array([$this, 'parseAttributes'], func_get_args());	
	}
	
    /**
     * Retrieve all attributes from the shortcodes tag.
     *
     * The attributes list has the attribute name as the key and the value of the
     * attribute as the value in the key/value pair. This allows for easier
     * retrieval of the attributes, since all attributes have to be known.
     *
     *
     * @param string $text
     * @return array List of attributes and their value.
     */
    protected function parseAttributes($text)
    {
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if (!preg_match_all($this->attrPattern, $text, $matches, PREG_SET_ORDER)) {
            return array(ltrim($text));
        }

        $attr = array();

        foreach ($matches as $match) {
            if (!empty($match[1])) {
                $attr[strtolower($match[1])] = stripcslashes($match[2]);
            } elseif (!empty($match[3])) {
                $attr[strtolower($match[3])] = stripcslashes($match[4]);
            } elseif (!empty($match[5])) {
                $attr[strtolower($match[5])] = stripcslashes($match[6]);
            } elseif (isset($match[7]) && strlen($match[7])) {
                $attr[] = stripcslashes($match[7]);
            } elseif (isset($match[8])) {
                $attr[] = stripcslashes($match[8]);
            }
        }

        return $attr;
    }

    /**
     * Strips a tag leaving escaped tags
     *
     * @param $tag
     * @return string
     */
    private function stripShortcodeTag($tag)
    {
        if ($tag[1] == '[' && $tag[6] == ']') {
            return substr($tag[0], 1, -1);
        }

        return $tag[1] . $tag[6];
    }

    /**
     * Retrieve the shortcode regular expression for searching.
     *
     * The regular expression combines the shortcode tags in the regular expression
     * in a regex class.
     *
     * The regular expression contains 6 different sub matches to help with parsing.
     *
     * 1 - An extra [ to allow for escaping shortcodes with double [[]]
     * 2 - The shortcode name
     * 3 - The shortcode argument list
     * 4 - The self closing /
     * 5 - The content of a shortcode when it wraps some content.
     * 6 - An extra ] to allow for escaping shortcodes with double [[]]
     *
     * @return string The shortcode search regular expression
     */
    public function shortcodeRegex()
    {
        $tagRegex = join('|', array_map('preg_quote', array_keys($this->shortcodes)));

        return
            '/'
            . '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagRegex)"                      // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)'                           // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
            . '/s';
    }
}
