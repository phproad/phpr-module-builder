<?php

class Builder_Radio_Field extends Builder_Field_Base
{
	public function get_info()
	{
		return array(
			'name' => 'Radio',
			'description' => 'Single choice radio buttons'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('options', 'Radio Options', 'left', db_text)->comment('Use one line per option');
	}

}