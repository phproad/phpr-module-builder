<?php

class Builder_Checkbox_Field extends Builder_Field_Base
{
	public function get_info()
	{
		return array(
			'name' => 'Check Box',
			'description' => 'List of check boxes'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('options', 'Checkbox options', 'left', db_text)->comment('Use one line per option');
		$host->add_field('allow_custom', 'Allow custom input', 'right', db_bool)->comment('Allow the user to type in their own option.');
	}

	public function get_summary()
	{
		$host = $this->get_host_object();
		return str_replace(array("\r", "\n"), ', ', $host->options);
	}

}