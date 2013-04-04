<?php

class Builder_Dropdown_Field extends Builder_Field_Base
{

	public function get_info()
	{
		return array(
			'name' => 'Drop Down',
			'description' => 'Drop down list of choices'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('options', 'Dropdown Options', 'left', db_text)->comment('Use one line per option');
		$host->add_field('empty_option', 'Empty Option', 'right', db_varchar)->comment('Label to use when no dropdown option is selected.');
	}

	public function init_config_data($host) 
	{ 
		$host->empty_option = '- select -';
	}

	public function get_summary()
	{
		$host = $this->get_host_object();
		return str_replace(array("\r", "\n"), ', ', $host->options);
	}

}