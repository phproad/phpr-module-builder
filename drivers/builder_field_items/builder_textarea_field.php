<?php

class Builder_Textarea_Field extends Builder_Field_Base
{

	public function get_info()
	{
		return array(
			'name' => 'Text Area',
			'description' => 'Multiple line text input'
		);
	}

	public function build_config_ui($host, $context = null)
	{
		$host->add_field('default_value', 'Default Value', 'full', db_text)->size('small');
		$host->add_field('placeholder', 'Placeholder', 'full', db_varchar);
	}

}