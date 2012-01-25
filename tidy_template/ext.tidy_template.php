<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Template Tidy Cleaner Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		Fred Carlsen
 * @link		
 */

class Tidy_template_ext {
	
	public $settings 		= array();
	public $description		= 'Clean template with HTML Tidy';
	public $docs_url		= 'https://github.com/sjelfull/sf.tidy_template.ee_addon';
	public $name			= 'Tidy Template';
	public $settings_exist	= 'y';
	public $version			= '0.2';
	
	private $EE;
	
	public static $default_settings = array(
		'add-xml-decl' => 'no',
		'add-xml-space' => 'no',
		'alt-text' => '',
		'anchor-as-name' => 'yes',
		'assume-xml-procins' => 'no',
		'bare' => 'no',
		'clean' => 'no',
		'css-prefix' => '',
		'decorate-inferred-ul' => 'no',
		'doctype' => 'auto',
		'drop-empty-paras' => 'yes',
		'drop-font-tags' => 'no',
		'drop-proprietary-attributes' => 'no',
		'enclose-block-text' => 'no',
		'enclose-text' => 'no',
		'escape-cdata' => 'no',
		'fix-backslash' => 'yes',
		'fix-bad-comments' => 'yes',
		'fix-uri' => 'yes',
		'hide-comments' => 'no',
		'hide-endtags' => 'no',
		'indent-cdata' => 'no',
		'input-xml' => 'no',
		'join-classes' => 'no',
		'join-styles' => 'yes',
		'literal-attributes' => 'no',
		'logical-emphasis' => 'no',
		'lower-literals' => 'yes',
		'merge-divs' => 'auto',
		'merge-spans' => 'auto',
		'ncr' => 'yes',
		'new-blocklevel-tags' => '',
		'new-empty-tags' => '',
		'new-inline-tags' => '',
		'new-pre-tags' => '',
		'numeric-entities' => 'no',
		'output-html' => 'no',
		'output-xhtml' => 'no',
		'output-xml' => 'no',
		'preserve-entities' => 'no',
		'quote-ampersand' => 'yes',
		'quote-marks' => 'no',
		'quote-nbsp' => 'yes',
		'repeated-attributes' => 'keep-last',
		'replace-color' => 'no',
		'show-body-only' => 'no',
		'uppercase-attributes' => 'no',
		'uppercase-tags' => 'no',
		'word-2000' => 'no',
		'accessibility-check' => '0',
		'show-errors' => '6',
		'show-warnings' => 'yes',
		'break-before-br' => 'no',
		'indent' => 'no',
		'indent-attributes' => 'no',
		'indent-spaces' => '2',
		'markup' => 'yes',
		'punctuation-wrap' => 'no',
		'sort-attributes' => 'none',
		'split' => 'no',
		'tab-size' => '8',
		'vertical-space' => 'no',
		'wrap' => '68',
		'wrap-asp' => 'yes',
		'wrap-attributes' => 'no',
		'wrap-jste' => 'yes',
		'wrap-php' => 'yes',
		'wrap-script-literals' => 'no',
		'wrap-sections' => 'yes',
		'ascii-chars' => 'no',
		//'char-encoding' => 'ascii',
		//'input-encoding' => 'latin1', //use built-in EE encoding
		//'language' => '',
		//'newline' => '',
		'output-bom' => 'auto',
		//'output-encoding' => 'ascii', //use built-in EE encoding
		//'error-file' => '',
		'force-output' => 'no',
		//'gnu-emacs' => 'no',
		//'gnu-emacs-file' => '',
		'keep-time' => 'no',
		//'output-file' => '',
		'quiet' => 'no',
		//'slide-style' => '',
		'tidy-mark' => 'yes',
		'write-back' => 'no',
	);
	
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}// ----------------------------------------------------------------------
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array();
		
		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'clean_template',
			'hook'		=> 'template_post_parse',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$this->EE->db->insert('extensions', $data);			
		
	}	

	// ----------------------------------------------------------------------
	
	/**
	 * settings
	 * 
	 * @access	public
	 * @return	void
	 */
	public function settings()
	{
		$special = array(
			'doctype' => array('omit', 'auto', 'strict', 'transitional', 'user'),
			'merge-divs' => array('auto', 'yes', 'no'),
			'merge-spans' => array('auto', 'yes', 'no'),
			'repeated-attributes' => array('keep-first', 'keep-last'),
			'accessibility-check' => array('0' => 'tidy_classic', '1' => 'priority_1_checks', '2' => 'priority_2_checks', '3' => 'priority_3_checks'),
			'output-bom' => array('auto', 'yes', 'no'),
		);
		
		$settings = array();
		
		foreach (self::$default_settings as $key => $value)
		{
			//it's a bool string
			if (in_array($value, array('yes', 'no')))
			{
				//give 'em a yes checkbox
				$settings[$key] = array('c', array('yes' => 'yes'), array($value));
			}
			//it's not just a text input
			else if (isset($special[$key]))
			{
				//select menu
				if (is_array($special[$key]))
				{
					$settings[$key] = array('s', array());
					
					foreach ($special[$key] as $i => $option)
					{
						if (is_int($i))
						{
							$settings[$key][1][$option] = $option;
						}
						else
						{
							$settings[$key][1][$i] = $option;
						}
					}
					
					$settings[$key][2] = $special[$key][0];
				}
				else
				{
					$settings[$key] = $special[$key];
				}
			}
			else
			{
				$settings[$key] = $value;
			}
		}
		
		return $settings;
	}
	
	/**
	 * clean_template
	 *
	 * @param 
	 * @return 
	 */
	public function clean_template($template, $sub,	$site_id)
	{
		if ($this->EE->extensions->last_call !== FALSE)
		{
			$template = $this->EE->extensions->last_call;
		}
		
		//only run on the final template
		if ( ! $sub && function_exists('tidy_repair_string'))
		{
			//grab the cached settings if they're not already there for some reason
			if (empty($this->settings))
			{
				$this->settings = $this->EE->extensions->s_cache[__CLASS__];
			}
			
			$options = $this->settings;
			
			//b/c of the way EE stores checkboxes as arrays
			foreach ($options as $key => $value)
			{
				if (is_array($value))
				{
					$options[$key] = (bool) $value;
					$value = ( ! empty($value)) ? 'yes' : 'no';
				}
				
				// strip settings which match the default settings
				if ($value === self::$default_settings[$key])
				{
					unset($options[$key]);
				}
			}
			
			//grab in-template parameters: {tidy:options merge-divs="yes"}
			if (strstr($template, '{tidy:options') && preg_match('/{tidy:options (.*?)}/', $template, $match))
			{
				$template = str_replace($match[0], '', $template);
				
				$options = array_merge($options, $this->EE->functions->assign_parameters($match[1]));
			}
			
			//use EE's config value for encoding
			$options['char-encoding'] = strtolower(str_replace(array(' ', '-'), '', $this->EE->config->item('charset')));
			
			$template = tidy_repair_string($template, $options, $options['char-encoding']);
		}

	 	return $template;
	}

	// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}	
	
	// ----------------------------------------------------------------------
}

/* End of file ext.template_tidy_cleaner.php */
/* Location: /system/expressionengine/third_party/template_tidy_cleaner/ext.template_tidy_cleaner.php */