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
		$host->add_field('default_value', 'Default Value', 'full', db_text)->size('small')->comment('Set a field value for when the form first loads.')->tab('Properties');
		$host->add_field('placeholder', 'Placeholder', 'full', db_varchar)->comment('Text to display inside the field when empty. ')->tab('Properties');
	}

	public function display_control()
	{
		$str = array();
		$str[] = '<div class="control-group">';
		$str[] = '<label class="control-label">';
		$str[] = $this->label;
		$str[] = '</label>';
		$str[] = '<div class="controls">';

		$str[] = '<textarea name="" placeholder="'.$this->placeholder.'">'.$this->default_value.'</textarea>';

		$str[] = '</div>';

		// Comment
		if (strlen($this->comment))
			$str[] = '<span class="help-block">'.$this->comment.'</span>';

		$str[] = '</div>';

		return implode(PHP_EOL, $str);
	}
}