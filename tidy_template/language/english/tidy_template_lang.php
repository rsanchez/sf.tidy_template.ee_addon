<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang = array();

foreach (array_keys(Tidy_template_ext::$default_settings) as $key)
{
	$lang[$key] = '<a href="http://tidy.sourceforge.net/docs/quickref.html#'.$key.'" target="_blank">'.$key.'</a>';
}

/* End of file tidy_lang.php */
/* Location: ./system/expressionengine/third_party/tidy/language/english/tidy_lang.php */