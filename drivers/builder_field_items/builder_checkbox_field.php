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
		$host->add_field('options', 'Checkbox Options', 'left', db_text)->comment('Use one line per option');
	}

}