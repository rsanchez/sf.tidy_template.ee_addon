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
	public $docs_url		= '';
	public $name			= 'Tidy Template';
	public $settings_exist	= 'n';
	public $version			= '0.1';
	
	private $EE;
	
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
	 * clean_template
	 *
	 * @param 
	 * @return 
	 */
	public function clean_template($template, $sub,	$site_id)
	{
		if (function_exists('tidy_parse_string')) {

			// Add Code for the update_template_end hook here.  
			// Specify configuration
			$config = array(
			 'indent'         => true,
			 'output-html'   => true,
			 'wrap'           => 200,
			 'markup'           => true);

			// Specify encoding
			$encoding = 'utf8';

			// repair HTML
			$template = tidy_repair_string($template, $config, $encoding);

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