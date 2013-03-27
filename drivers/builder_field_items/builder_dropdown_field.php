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
	}

}